<?php

namespace Vanguard\Http\Controllers\Web;

use Vanguard\BotMessage;
use Vanguard\BotMessageBatch;
use Vanguard\BotRating;
use Vanguard\BotIssue;
use Vanguard\BotTemplate;
use Vanguard\User;
use Vanguard\Group;
use Vanguard\Role;
use Vanguard\County;
use Vanguard\Contact;
use Vanguard\Jobs\SendBotMessageBatch;
use Vanguard\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class BotAdminController extends Controller
{
    public function index()
    {
        $batches = BotMessageBatch::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total_batches' => BotMessageBatch::count(),
            'total_messages' => BotMessage::count(),
            'sent_today' => BotMessage::where('status', BotMessage::STATUS_SENT)
                ->whereDate('created_at', today())
                ->count(),
            'pending' => BotMessage::where('status', BotMessage::STATUS_QUEUED)->count(),
            'total_ratings' => BotRating::count(),
            'ratings_today' => BotRating::whereDate('created_at', today())->count(),
        ];

        return view('bot.index', compact('batches', 'stats'));
    }

    public function compose()
    {
        $templates = BotTemplate::where('is_active', true)->get();
        $groups = Group::all();
        $roles = Role::all();
        $counties = County::pluck('name', 'id');

        return view('bot.compose', compact('templates', 'groups', 'roles', 'counties'));
    }

   public function send(Request $request)
    {
        Log::info('🔍 Bot send request received', [
            'message_type' => $request->input('message_type'),
            'has_rating_config' => $request->has('rating_config'),
            'rating_config_raw' => $request->input('rating_config'),
        ]);

        $recipients = $request->input('recipients');

        if (is_string($recipients)) {
            $recipients = json_decode($recipients, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Failed to decode recipients JSON', [
                    'error' => json_last_error_msg(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Invalid recipients format: ' . json_last_error_msg()
                ], 422);
            }
        }

        if (!is_array($recipients)) {
            return response()->json([
                'success' => false,
                'message' => 'Recipients must be an array'
            ], 422);
        }

      
        $validator = \Validator::make([
            'message' => $request->input('message'),
            'recipients' => $recipients,
            'message_type' => $request->input('message_type', 'regular'),
        ], [
            'message' => 'required|string|max:1000',
            'recipients' => 'required|array|min:1',
            'recipients.*.phone' => 'nullable|string',
            'recipients.*.name' => 'nullable|string',
            'recipients.*.email' => 'nullable|string',
            'message_type' => 'required|in:regular,rating',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

     
        $attachmentUrls = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                try {
                    $path = $file->store('bot-attachments', 'public');
                    $attachmentUrls[] = [
                        'name' => $file->getClientOriginalName(),
                        'url' => asset('storage/' . $path),
                        'type' => $file->getMimeType(),
                        'size' => $file->getSize(),
                    ];
                } catch (\Exception $e) {
                    Log::error('Failed to upload attachment', [
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }

       
        $validRecipients = collect($recipients)->filter(fn($r) => !empty($r['phone']));

        if ($validRecipients->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No valid recipients found. All recipients must have a phone number.'
            ], 422);
        }

        $batchId = 'BOT-' . strtoupper(Str::random(10));
        $messageType = $request->input('message_type', 'regular');

       
        $ratingConfig = null;
        if ($messageType === 'rating') {
            $ratingConfigStr = $request->input('rating_config');
            if ($ratingConfigStr) {
                $ratingConfig = json_decode($ratingConfigStr, true);
            }

          
            if (!is_array($ratingConfig)) {
                $ratingConfig = [
                    'rating_type' => 'thumbs',
                    'scale_min' => 1,
                    'scale_max' => 5,
                    'allow_comment' => false,
                    'allow_skip' => true,
                ];
            }

          
            if (!in_array($ratingConfig['rating_type'] ?? '', ['thumbs', 'scale'])) {
                $ratingConfig['rating_type'] = 'thumbs';
            }
            if (!isset($ratingConfig['scale_min'])) $ratingConfig['scale_min'] = 1;
            if (!isset($ratingConfig['scale_max'])) $ratingConfig['scale_max'] = 5;
            $ratingConfig['allow_comment'] = (bool)($ratingConfig['allow_comment'] ?? false);
            $ratingConfig['allow_skip'] = (bool)($ratingConfig['allow_skip'] ?? true);

            Log::info('✅ Rating config validated', [
                'rating_config' => $ratingConfig,
            ]);
        }

        // Build filters
        $filters = is_array($request->input('filters')) ? $request->input('filters') : [];
        $filters['message_type'] = $messageType;
        if ($ratingConfig) $filters['rating_config'] = $ratingConfig;

        // Create batch
        $batch = BotMessageBatch::create([
            'batch_id' => $batchId,
            'user_id' => auth()->id(),
            'total_count' => $validRecipients->count(),
            'sent_count' => 0,
            'failed_count' => 0,
            'status' => 'processing',
            'filters' => $filters,
        ]);

        Log::info('✅ Batch created', [
            'batch_id' => $batchId,
            'filters' => $filters,
        ]);

        // Create messages
        $messageIds = [];
        foreach ($validRecipients as $recipient) {
            $messageData = [
                'batch_id' => $batchId,
                'user_id' => auth()->id(),
                'name' => $recipient['name'] ?? null,
                'phone' => $recipient['phone'],
                'recipient' => $recipient['email'] ?? null,
                'message' => $request->input('message'),
                'attachments' => !empty($attachmentUrls) ? $attachmentUrls : null,
                'category' => $messageType === 'rating' ? 'rating' : 'bot',
                'date' => now(),
                'group_id' => $request->input('filters.group_id') ?? null,
                'status' => BotMessage::STATUS_QUEUED,
                'status_message' => 'Queued for sending',
            ];

            // ⭐ CRITICAL: ADD RATING FIELDS TO MESSAGE
            if ($messageType === 'rating' && $ratingConfig) {
                $messageData['is_rating'] = true;
                $messageData['rating_type'] = $ratingConfig['rating_type'];
                $messageData['scale_min'] = $ratingConfig['scale_min'];
                $messageData['scale_max'] = $ratingConfig['scale_max'];
                $messageData['allow_comment'] = $ratingConfig['allow_comment'];
                $messageData['allow_skip'] = $ratingConfig['allow_skip'];

                Log::info('⭐ Creating RATING message with data', [
                    'is_rating' => true,
                    'rating_type' => $ratingConfig['rating_type'],
                    'phone' => $recipient['phone'],
                ]);
            }

            $message = BotMessage::create($messageData);
            $messageIds[] = $message->id;
        }

        Log::info('✅ Messages created', [
            'count' => count($messageIds),
            'message_type' => $messageType,
        ]);

        // Dispatch jobs in chunks
        foreach (array_chunk($messageIds, 100) as $chunk) {
            SendBotMessageBatch::dispatch($chunk)->onQueue('bot-messages');
        }

        return response()->json([
            'success' => true,
            'message' => $messageType === 'rating'
                ? 'Rating requests queued successfully'
                : 'Messages queued successfully',
            'batch_id' => $batchId,
            'total_count' => $validRecipients->count(),
            'skipped_count' => count($recipients) - $validRecipients->count(),
            'attachments_count' => count($attachmentUrls),
            'message_type' => $messageType,
        ]);
    }


    public function batchDetails($batchId)
    {
        $batch = BotMessageBatch::where('batch_id', $batchId)
            ->with(['messages' => fn($q) => $q->orderBy('created_at', 'desc')])
            ->firstOrFail();

        
        $isRatingBatch = isset($batch->filters['message_type']) 
            && $batch->filters['message_type'] === 'rating';

       
        $ratings = [];
        $ratedPhones = [];
        $unratedPhones = [];
        
        if ($isRatingBatch) {
            // Get all phone numbers from this batch
            $allPhones = $batch->messages->pluck('phone')->unique();
            
            // Get ratings for these phone numbers
            $ratings = BotRating::whereIn('phone_number', $allPhones)
                ->where('created_at', '>=', $batch->created_at)
                ->with('user')
                ->get();
            
            $ratedPhones = $ratings->pluck('phone_number')->unique();
            $unratedPhones = $allPhones->diff($ratedPhones);
        }

        return view('bot.batch-details', compact('batch', 'isRatingBatch', 'ratings', 'ratedPhones', 'unratedPhones'));
    }

    public function getRecipients(Request $request)
    {
        Log::info('Getting recipients', [
            'filters' => $request->all()
        ]);

        $recipients = collect();

        try {
            if ($request->filled('group_id')) {
                $recipients = Contact::where('group_id', $request->group_id)
                    ->select('id', 'name', 'phone', 'email')
                    ->get()
                    ->map(fn($contact) => [
                        'id' => $contact->id,
                        'name' => $contact->name,
                        'phone' => $contact->phone,
                        'email' => $contact->email,
                    ]);
            } else {
                $query = User::where('status', 'Active')->whereNotNull('phone');

                if ($request->role_id) {
                    $query->where('role_id', $request->role_id);
                }
                
                if ($request->county_id) {
                    $query->where('county_id', $request->county_id);
                }
                
                if ($request->sub_county_id) {
                    $query->where('sub_county_id', $request->sub_county_id);
                }
                
                if ($request->ward_id) {
                    $query->where('ward_id', $request->ward_id);
                }

                $recipients = $query->select('id', 'first_name', 'last_name', 'phone', 'email')
                    ->get()
                    ->map(fn($user) => [
                        'id' => $user->id,
                        'name' => $user->first_name . ' ' . $user->last_name,
                        'phone' => $user->phone,
                        'email' => $user->email,
                    ]);
            }

            return response()->json([
                'success' => true,
                'count' => $recipients->count(),
                'recipients' => $recipients->values()->toArray(),
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting recipients', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error retrieving recipients: ' . $e->getMessage()
            ], 500);
        }
    }

    public function ratings()
    {
        $ratings = BotRating::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total_ratings' => BotRating::count(),
            'average_score' => BotRating::avg('rating_score'),
            'thumbs_up' => BotRating::where('thumb_rating', 'up')->count(),
            'thumbs_down' => BotRating::where('thumb_rating', 'down')->count(),
        ];

        return view('bot.ratings', compact('ratings', 'stats'));
    }

    public function issues()
    {
        $issues = BotIssue::with(['user', 'assignedUser'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('bot.issues', compact('issues'));
    }

    public function updateIssueStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,resolved,closed',
            'resolution_notes' => 'nullable|string',
        ]);

        $issue = BotIssue::findOrFail($id);

        $issue->update([
            'status' => $request->status,
            'resolution_notes' => $request->resolution_notes,
            'resolved_at' => in_array($request->status, ['resolved', 'closed']) ? now() : null,
        ]);

        return response()->json([
            'success' => true,
            'issue' => $issue,
        ]);
    }

    public function templates()
    {
        $templates = BotTemplate::orderBy('name')->paginate(20);
        return view('bot.templates', compact('templates'));
    }

    public function storeTemplate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'message' => 'required|string',
            'placeholders' => 'nullable|array',
        ]);

        $template = BotTemplate::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'message' => $request->message,
            'placeholders' => $request->placeholders ?? [],
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'template' => $template,
        ]);
    }

    /**
     * Get rating statistics for a specific batch
     */
    public function getBatchRatingStats($batchId)
    {
        $batch = BotMessageBatch::where('batch_id', $batchId)->firstOrFail();
        
        $phones = BotMessage::where('batch_id', $batchId)->pluck('phone');
        
        $ratings = BotRating::whereIn('phone_number', $phones)
            ->where('created_at', '>=', $batch->created_at)
            ->get();
        
        $stats = [
            'total_sent' => $phones->count(),
            'total_rated' => $ratings->count(),
            'unrated_count' => $phones->count() - $ratings->count(),
            'average_score' => $ratings->avg('rating_score'),
            'thumbs_up' => $ratings->where('thumb_rating', 'up')->count(),
            'thumbs_down' => $ratings->where('thumb_rating', 'down')->count(),
        ];
        
        return response()->json([
            'success' => true,
            'stats' => $stats,
        ]);
    }
}