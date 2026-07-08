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
        Log::info("📥 BaBOT broadcast status webhook received", [
            'payload' => $request->all()
        ]);

       
        $messageIdentifier = $request->input('request_id') ?? $request->input('message_id');
        $status = $request->input('status');

        if (!$messageIdentifier) {
            Log::warning("❌ Missing request_id/message_id in status webhook", [
                'payload' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Missing request_id or message_id'
            ], 422);
        }

        try {
            // Find message by ID or message_id field
            $message = BotMessage::where('id', $messageIdentifier)
                ->orWhere('message_id', $messageIdentifier)
                ->first();

            if (!$message) {
                Log::warning("❌ Message not found for status update", [
                    'identifier' => $messageIdentifier
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Message not found'
                ], 404);
            }

            // Map BaBOT status to FOS status
            $statusMap = [
                'queued' => BotMessage::STATUS_QUEUED,
                'sent' => BotMessage::STATUS_SENT,
                'delivered' => BotMessage::STATUS_DELIVERED,
                'failed' => BotMessage::STATUS_FAILED,
                'undelivered' => BotMessage::STATUS_UNDELIVERED,
            ];

            $newStatus = $statusMap[strtolower($status)] ?? BotMessage::STATUS_FAILED;
            
            $message->update([
                'status' => $newStatus,
                'status_message' => ucfirst($status),
                'response' => json_encode($request->all()),
            ]);

            Log::info("✅ Message status updated successfully", [
                'message_id' => $message->id,
                'new_status' => $newStatus,
                'status_name' => $status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error("❌ Error updating message status", [
                'error' => $e->getMessage(),
                'identifier' => $messageIdentifier
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage()
            ], 500);
        }
    }

    
    /**
     * 🔧 FIXED: Receive rating submissions from BaBOT
     * Endpoint: POST /api/v1/ratings/responses
     */
    public function receiveRating(Request $request)
    {
        Log::info("⭐ BaBOT rating response received", [
            'payload' => $request->all(),
            'headers' => $request->headers->all()
        ]);

        // ✅ RELAXED VALIDATION - Accept what Bot actually sends
        $validator = Validator::make($request->all(), [
            'request_id' => 'required|string',
            'phone_number' => 'required|string',  // Changed to required
            'rating_type' => 'required|string',
            'rating_score' => 'nullable|integer|min:1|max:10',  // Increased max to 10
            'thumb_rating' => 'nullable|in:up,down,thumbs_up,thumbs_down',  // Added variants
            'comment' => 'nullable|string|max:1000',
            'session_id' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            Log::warning("❌ Rating validation failed", [
                'errors' => $validator->errors()->toArray(),
                'payload' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // ✅ IMPROVED MESSAGE LOOKUP - Check message_id first (what Bot sends)
            $message = BotMessage::where('message_id', $request->request_id)
                ->orWhere('id', $request->request_id)
                ->first();

            if (!$message) {
                Log::warning("❌ Message not found for rating", [
                    'request_id' => $request->request_id,
                    'phone' => $request->phone_number
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Message not found'
                ], 404);
            }

            // ✅ NORMALIZE RATING TYPE
            $normalizedRatingType = $this->normalizeRatingType($request->rating_type);

            // ✅ NORMALIZE THUMB RATING
            $normalizedThumbRating = $this->normalizeThumbRating($request->thumb_rating);

            // Get user from message or phone number
            $user = null;
            if ($message->user_id) {
                $user = User::find($message->user_id);
            } else {
                $user = User::where('phone', $request->phone_number)->first();
            }

            $sessionId = $request->session_id ?? 'msg_' . $message->id;

            // ✅ CREATE RATING WITH NORMALIZED VALUES
            $rating = BotRating::create([
                'session_id' => $sessionId,
                'user_id' => $user ? $user->id : null,
                'phone_number' => $request->phone_number,
                'rating_type' => $normalizedRatingType,
                'rateable_id' => $message->id,
                'rateable_type' => BotMessage::class,
                'rating_score' => $request->rating_score,
                'thumb_rating' => $normalizedThumbRating,
                'comment' => $request->comment,
            ]);

            Log::info("✅ Rating saved successfully", [
                'rating_id' => $rating->id,
                'message_id' => $message->id,
                'phone' => $request->phone_number,
                'rating_type' => $normalizedRatingType,
                'original_rating_type' => $request->rating_type,
                'score' => $request->rating_score,
                'thumb' => $normalizedThumbRating,
                'original_thumb' => $request->thumb_rating,
                'has_comment' => !empty($request->comment)
            ]);

            return response()->json([
                'success' => true,
                'rating_id' => $rating->id,
                'message' => 'Rating received successfully'
            ], 201);

        } catch (\Exception $e) {
            Log::error("❌ Error saving rating", [
                'error' => $e->getMessage(),
                'request_id' => $request->request_id,
                'phone' => $request->phone_number,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error saving rating: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Normalize rating type from Bot to DB format
     */
    private function normalizeRatingType(?string $ratingType): string
    {
        if (!$ratingType) return 'thumbs';
        
        $type = strtolower(trim($ratingType));
        
        // Map Bot variants to DB format
        $typeMap = [
            'thumb' => 'thumbs',
            'thumbs' => 'thumbs',
            'star' => 'scale',
            'stars' => 'scale',
            'scale' => 'scale',
            'number' => 'scale',
            'numeric' => 'scale',
            'rating' => 'scale',
        ];
        
        $normalized = $typeMap[$type] ?? 'thumbs';
        
        Log::debug("Rating type normalized", [
            'original' => $ratingType,
            'normalized' => $normalized
        ]);
        
        return $normalized;
    }

    /**
     * Normalize thumb rating from Bot to DB format
     */
    private function normalizeThumbRating(?string $thumbRating): ?string
    {
        if (!$thumbRating) return null;
        
        $thumb = strtolower(trim($thumbRating));
        
        // Map Bot variants to DB format
        $thumbMap = [
            'up' => 'up',
            'thumbs_up' => 'up',
            'thumbup' => 'up',
            'thumb_up' => 'up',
            '👍' => 'up',
            'like' => 'up',
            'down' => 'down',
            'thumbs_down' => 'down',
            'thumbdown' => 'down',
            'thumb_down' => 'down',
            '👎' => 'down',
            'dislike' => 'down',
        ];
        
        $normalized = $thumbMap[$thumb] ?? null;
        
        Log::debug("Thumb rating normalized", [
            'original' => $thumbRating,
            'normalized' => $normalized
        ]);
        
        return $normalized;
    }
    
    /**
     * Receive issue submission from BaBOT
     */
    public function receiveIssue(Request $request)
    {
        Log::info("🐛 BaBOT issue received", [
            'payload' => $request->all()
        ]);

        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string',
            'category' => 'required|in:system_failure,mentorship_coaching,operations,training,payment,harassment,general',
            'description' => 'required|string|max:2000',
            'priority' => 'nullable|in:low,medium,high,urgent',
        ]);

        if ($validator->fails()) {
            Log::warning("❌ Issue validation failed", [
                'errors' => $validator->errors()->toArray()
            ]);
            
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

            Log::info("✅ Issue saved successfully", [
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
            Log::error("❌ Error saving issue", [
                'error' => $e->getMessage(),
                'phone' => $request->phone_number
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error saving issue: ' . $e->getMessage()
            ], 500);
        }
    }

    
    /**
     * Get issue status
     */
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

    
    /**
     * Get batch statistics
     */
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

    /**
     * Record announcement view
     */
    public function recordAnnouncementView(Request $request)
    {
        Log::info("👁️ Announcement view received", [
            'payload' => $request->all()
        ]);

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

            $user = null;
            if ($message->user_id) {
                $user = User::find($message->user_id);
            } elseif ($request->phone_number) {
                $user = User::where('phone', $request->phone_number)->first();
            } elseif ($message->phone) {
                $user = User::where('phone', $message->phone)->first();
            }

            $phoneNumber = $request->phone_number ?? $message->phone;

            Log::info("✅ Announcement view recorded", [
                'message_id' => $request->message_id,
                'phone' => $phoneNumber,
                'platform' => $request->platform
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Announcement view recorded successfully'
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

    /**
     * Update issue status
     */
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

            Log::info("Issue status updated from BaBOT", [
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