<?php

namespace Vanguard\Http\Controllers\Api;

use Vanguard\BotMessage;
use Vanguard\BotMessageBatch;
use Vanguard\BotRating;
use Vanguard\BotIssue;
use Vanguard\User;
use Vanguard\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BotWebhookController extends Controller
{
    
    public function messageStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message_id' => 'required|string',
            'status' => 'required|string|in:delivered,undelivered,failed,sent,accepted',
            'phone' => 'nullable|string',
            'status_message' => 'nullable|string',
            'error_code' => 'nullable|string', 
            'error_message' => 'nullable|string', 
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            
            $message = BotMessage::where('message_id', $request->message_id)->first();

            if (!$message && $request->phone) {
                
                $message = BotMessage::where('phone', $request->phone)
                    ->whereNull('message_id')
                    ->orderBy('created_at', 'desc')
                    ->first();
            }

            if (!$message) {
                Log::warning("Bot message not found for status update", [
                    'message_id' => $request->message_id,
                    'phone' => $request->phone
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Message not found'
                ], 404);
            }

            // Map bot status to our status codes
            $statusMap = [
                'accepted' => BotMessage::STATUS_QUEUED,
                'sent' => BotMessage::STATUS_SENT,
                'delivered' => BotMessage::STATUS_DELIVERED,
                'undelivered' => BotMessage::STATUS_UNDELIVERED,
                'failed' => BotMessage::STATUS_FAILED,
            ];

            $newStatus = $statusMap[$request->status] ?? BotMessage::STATUS_FAILED;
            
            // Build status message with error details if present
            $statusMessage = $request->status_message ?? ucfirst($request->status);
            if ($request->error_code || $request->error_message) {
                $statusMessage .= " - Error: " . ($request->error_message ?? $request->error_code);
            }

            $message->update([
                'status' => $newStatus,
                'status_message' => $statusMessage,
                'message_id' => $message->message_id ?? $request->message_id,
                'response' => json_encode($request->all()),
            ]);

            // Update batch counts
            if ($newStatus === BotMessage::STATUS_FAILED && $message->status !== BotMessage::STATUS_FAILED) {
                $this->updateBatchCount($message->batch_id, 'failed');
            } elseif ($newStatus === BotMessage::STATUS_DELIVERED) {
                // Optionally track delivered count
            }

            Log::info("Bot message status updated", [
                'message_id' => $request->message_id,
                'status' => $request->status,
                'phone' => $message->phone,
                'new_status_code' => $newStatus
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error("Error updating message status", [
                'error' => $e->getMessage(),
                'message_id' => $request->message_id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage()
            ], 500);
        }
    }

    
    /**
     * Receive rating submission from Bot
     * Now linked to a specific message via message_id
     */
    public function receiveRating(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message_id' => 'required|string',  // Changed to required
            'session_id' => 'nullable|string',  // Changed to optional
            'phone_number' => 'nullable|string',  // Changed to optional
            'rating_type' => 'required|string',
            'rating_score' => 'nullable|integer|min:1|max:5',
            'thumb_rating' => 'nullable|in:up,down',
            'comment' => 'nullable|string|max:1000',
            'batch_id' => 'nullable|string',
            'rateable_id' => 'nullable|integer',
            'rateable_type' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Find the message by message_id
            $message = BotMessage::where('id', $request->message_id)
                ->orWhere('message_id', $request->message_id)
                ->first();

            if (!$message) {
                Log::warning("Message not found for rating", [
                    'message_id' => $request->message_id
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Message not found'
                ], 404);
            }

            // Get user from message or phone number
            $user = null;
            if ($message->user_id) {
                $user = User::find($message->user_id);
            } elseif ($request->phone_number) {
                $user = User::where('phone', $request->phone_number)->first();
            } elseif ($message->phone) {
                $user = User::where('phone', $message->phone)->first();
            }

            // Use phone from message if not provided in request
            $phoneNumber = $request->phone_number ?? $message->phone;
            
            // Use session_id from request or generate one from message
            $sessionId = $request->session_id ?? 'msg_' . $message->id;

            // Create rating
            $rating = BotRating::create([
                'session_id' => $sessionId,
                'user_id' => $user ? $user->id : null,
                'phone_number' => $phoneNumber,
                'rating_type' => $request->rating_type,
                'rateable_id' => $request->rateable_id ?? $message->id,
                'rateable_type' => $request->rateable_type ?? BotMessage::class,
                'rating_score' => $request->rating_score,
                'thumb_rating' => $request->thumb_rating,
                'comment' => $request->comment,
            ]);

            Log::info("Bot rating received", [
                'rating_id' => $rating->id,
                'message_id' => $request->message_id,
                'phone' => $phoneNumber,
                'rating_type' => $request->rating_type,
                'score' => $request->rating_score,
                'thumb' => $request->thumb_rating
            ]);

            // Associate with batch if available
            if ($message->batch_id) {
                $batch = BotMessageBatch::where('batch_id', $message->batch_id)->first();
                if ($batch) {
                    Log::info("Rating associated with batch", [
                        'batch_id' => $message->batch_id,
                        'rating_id' => $rating->id
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'rating_id' => $rating->id,
                'message' => 'Rating received successfully'
            ], 201);

        } catch (\Exception $e) {
            Log::error("Error receiving rating", [
                'error' => $e->getMessage(),
                'message_id' => $request->message_id,
                'phone' => $request->phone_number
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error saving rating: ' . $e->getMessage()
            ], 500);
        }
    }

    
    public function receiveIssue(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string',
            'category' => 'required|in:system_failure,mentorship_coaching,operations,training,payment,harassment,general',
            'description' => 'required|string|max:2000',
            'priority' => 'nullable|in:low,medium,high,urgent',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
           
            $user = User::where('phone', $request->phone_number)->first();

          
            $issue = BotIssue::create([
                'user_id' => $user->id ?? null,
                'phone_number' => $request->phone_number,
                'category' => $request->category,
                'description' => $request->description,
                'status' => 'pending',
            ]);

            Log::info("Bot issue received", [
                'issue_id' => $issue->issue_id,
                'phone' => $request->phone_number,
                'category' => $request->category
            ]);

            return response()->json([
                'success' => true,
                'issue_id' => $issue->issue_id,
                'message' => 'Issue submitted successfully'
            ], 201);

        } catch (\Exception $e) {
            Log::error("Error receiving issue", [
                'error' => $e->getMessage(),
                'phone' => $request->phone_number
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error saving issue: ' . $e->getMessage()
            ], 500);
        }
    }

    
    public function getIssueStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'issue_id' => 'nullable|string',
            'phone_number' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        if (!$request->issue_id && !$request->phone_number) {
            return response()->json([
                'success' => false,
                'message' => 'Provide either issue_id or phone_number'
            ], 400);
        }

        try {
            $query = BotIssue::query();

            if ($request->issue_id) {
                $query->where('issue_id', $request->issue_id);
            } elseif ($request->phone_number) {
                $query->where('phone_number', $request->phone_number);
            }

            $issues = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'count' => $issues->count(),
                'issues' => $issues->map(function($issue) {
                    return [
                        'issue_id' => $issue->issue_id,
                        'category' => $issue->category,
                        'description' => $issue->description,
                        'status' => $issue->status,
                        'created_at' => $issue->created_at->toIso8601String(),
                        'resolved_at' => $issue->resolved_at ? $issue->resolved_at->toIso8601String() : null,
                        'resolution_notes' => $issue->resolution_notes,
                    ];
                })
            ]);

        } catch (\Exception $e) {
            Log::error("Error getting issue status", [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error retrieving issues: ' . $e->getMessage()
            ], 500);
        }
    }

    
    public function getBatchStats(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'batch_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $batch = BotMessageBatch::where('batch_id', $request->batch_id)->first();

            if (!$batch) {
                return response()->json([
                    'success' => false,
                    'message' => 'Batch not found'
                ], 404);
            }

          
            $messages = BotMessage::where('batch_id', $request->batch_id)->get();
            
           
            $isRatingBatch = isset($batch->filters['message_type']) 
                && $batch->filters['message_type'] === 'rating';
            
            $ratingStats = null;
            if ($isRatingBatch) {
                $phones = $messages->pluck('phone')->unique();
                $ratings = BotRating::whereIn('phone_number', $phones)
                    ->where('created_at', '>=', $batch->created_at)
                    ->get();
                
                $ratingStats = [
                    'total_sent' => $phones->count(),
                    'total_rated' => $ratings->count(),
                    'unrated_count' => $phones->count() - $ratings->count(),
                    'response_rate' => $phones->count() > 0 
                        ? round(($ratings->count() / $phones->count()) * 100, 2) 
                        : 0,
                    'average_score' => $ratings->avg('rating_score'),
                    'thumbs_up' => $ratings->where('thumb_rating', 'up')->count(),
                    'thumbs_down' => $ratings->where('thumb_rating', 'down')->count(),
                ];
            }

            return response()->json([
                'success' => true,
                'batch' => [
                    'batch_id' => $batch->batch_id,
                    'status' => $batch->status,
                    'total_count' => $batch->total_count,
                    'sent_count' => $batch->sent_count,
                    'failed_count' => $batch->failed_count,
                    'created_at' => $batch->created_at->toIso8601String(),
                ],
                'is_rating_batch' => $isRatingBatch,
                'rating_stats' => $ratingStats,
            ]);

        } catch (\Exception $e) {
            Log::error("Error getting batch stats", [
                'error' => $e->getMessage(),
                'batch_id' => $request->batch_id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error retrieving batch stats: ' . $e->getMessage()
            ], 500);
        }
    }

   
public function recordAnnouncementView(Request $request)
{
    $validator = Validator::make($request->all(), [
        'message_id' => 'required|string',
        'phone_number' => 'nullable|string',
        'session_id' => 'nullable|string',
        'viewed_at' => 'nullable|date',
        'platform' => 'nullable|string|in:whatsapp,telegram,sms',
        'metadata' => 'nullable|array',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        // Find the message
        $message = BotMessage::where('id', $request->message_id)
            ->orWhere('message_id', $request->message_id)
            ->first();

        if (!$message) {
            Log::warning("Message not found for announcement view", [
                'message_id' => $request->message_id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Message not found'
            ], 404);
        }

        // Get user
        $user = null;
        if ($message->user_id) {
            $user = User::find($message->user_id);
        } elseif ($request->phone_number) {
            $user = User::where('phone', $request->phone_number)->first();
        } elseif ($message->phone) {
            $user = User::where('phone', $message->phone)->first();
        }

        $phoneNumber = $request->phone_number ?? $message->phone;

        // Check for duplicate view
        $existingView = BotAnnouncementView::where('message_id', $message->id)
            ->where('phone_number', $phoneNumber)
            ->first();

        if ($existingView) {
            // Update the view timestamp
            $existingView->update([
                'viewed_at' => $request->viewed_at ?? now(),
                'metadata' => $request->metadata,
            ]);

            Log::info("Announcement view updated", [
                'view_id' => $existingView->id,
                'message_id' => $request->message_id,
                'phone' => $phoneNumber
            ]);

            return response()->json([
                'success' => true,
                'view_id' => $existingView->id,
                'message' => 'Announcement view updated',
                'is_duplicate' => true
            ]);
        }

        // Create new view record
        $view = BotAnnouncementView::create([
            'message_id' => $message->id,
            'user_id' => $user ? $user->id : null,
            'phone_number' => $phoneNumber,
            'session_id' => $request->session_id,
            'viewed_at' => $request->viewed_at ?? now(),
            'platform' => $request->platform,
            'metadata' => $request->metadata,
        ]);

        Log::info("Announcement view recorded", [
            'view_id' => $view->id,
            'message_id' => $request->message_id,
            'phone' => $phoneNumber,
            'platform' => $request->platform
        ]);

        return response()->json([
            'success' => true,
            'view_id' => $view->id,
            'message' => 'Announcement view recorded successfully',
            'is_duplicate' => false
        ], 201);

    } catch (\Exception $e) {
        Log::error("Error recording announcement view", [
            'error' => $e->getMessage(),
            'message_id' => $request->message_id
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Error recording view: ' . $e->getMessage()
        ], 500);
    }
}

   
public function updateIssueStatus(Request $request)
{
    $validator = Validator::make($request->all(), [
        'issue_id' => 'required|string',
        'status' => 'required|in:pending,in_progress,resolved,closed',
        'resolution_notes' => 'nullable|string|max:2000',
        'assigned_to' => 'nullable|integer|exists:users,id',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        $issue = BotIssue::where('issue_id', $request->issue_id)->first();

        if (!$issue) {
            return response()->json([
                'success' => false,
                'message' => 'Issue not found'
            ], 404);
        }

        $updateData = [
            'status' => $request->status,
        ];

        if ($request->has('resolution_notes')) {
            $updateData['resolution_notes'] = $request->resolution_notes;
        }

        if ($request->has('assigned_to')) {
            $updateData['assigned_to'] = $request->assigned_to;
        }

        
        if (in_array($request->status, ['resolved', 'closed'])) {
            $updateData['resolved_at'] = now();
        }

        $issue->update($updateData);

        Log::info("Issue status updated from Bot", [
            'issue_id' => $request->issue_id,
            'status' => $request->status,
            'assigned_to' => $request->assigned_to
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Issue status updated successfully',
            'issue' => [
                'issue_id' => $issue->issue_id,
                'status' => $issue->status,
                'resolved_at' => $issue->resolved_at ? $issue->resolved_at->toIso8601String() : null,
            ]
        ]);

    } catch (\Exception $e) {
        Log::error("Error updating issue status", [
            'error' => $e->getMessage(),
            'issue_id' => $request->issue_id
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Error updating issue status: ' . $e->getMessage()
        ], 500);
    }
}

    private function updateBatchCount($batchId, $type)
    {
        $batch = BotMessageBatch::where('batch_id', $batchId)->first();
        
        if ($batch) {
            if ($type === 'sent') {
                $batch->increment('sent_count');
            } else {
                $batch->increment('failed_count');
            }

            if (($batch->sent_count + $batch->failed_count) >= $batch->total_count) {
                $batch->update(['status' => 'completed']);
            }
        }
    }
}