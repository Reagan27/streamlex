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
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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

    /**
     * Show the compose message form
     * Supports both regular messages and rating requests via ?mode=rating
     */
    public function compose(Request $request)
    {
        $templates = BotTemplate::where('is_active', true)->get();
        $groups = Group::all();
        $roles = Role::all();
        $counties = County::pluck('name', 'id');

        // Check if rating mode is requested
        $isRatingMode = $request->query('mode') === 'rating';

        if ($isRatingMode) {
            return view('bot.compose-rating', compact('templates', 'groups', 'roles', 'counties'));
        }

        return view('bot.compose', compact('templates', 'groups', 'roles', 'counties'));
    }

    public function send(Request $request)
    {
        try {
            Log::info('Bot send request received', [
                'message_type' => $request->input('message_type'),
                'has_rating_config' => $request->has('rating_config'),
            ]);

            // Parse and validate recipients
            $recipients = $this->parseRecipients($request);
            
            // Validate request based on message type
            $validatedData = $this->validateRequest($request, $recipients);
            
            // Process attachments (only for regular messages)
            $attachmentUrls = $this->processAttachments($request);
            
            // Filter valid recipients
            $validRecipients = $this->filterValidRecipients($recipients);
            
            if ($validRecipients->isEmpty()) {
                return $this->errorResponse('No valid recipients found. All recipients must have a phone number.', 422);
            }
            
            // Parse rating config if needed
            $ratingConfig = null;
            if ($request->input('message_type') === 'rating') {
                $ratingConfig = $this->parseRatingConfig($request);
            }
            
            // Create batch
            $batch = $this->createBatch($request, $validRecipients, $ratingConfig);
            
            // Create messages
            $messageIds = $this->createMessages($request, $validRecipients, $batch, $attachmentUrls, $ratingConfig);
            
            // Dispatch jobs
            $this->dispatchJobs($messageIds);
            
            // Return success response
            return $this->successResponse($batch, $validRecipients, count($recipients), count($attachmentUrls));
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Bot send error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
    
    /**
     * Parse and decode recipients from request
     */
    private function parseRecipients(Request $request): array
    {
        $recipients = $request->input('recipients');
        
        if (is_string($recipients)) {
            $recipients = json_decode($recipients, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Failed to decode recipients JSON', [
                    'error' => json_last_error_msg(),
                ]);
                
                throw new \InvalidArgumentException('Invalid recipients format: ' . json_last_error_msg());
            }
        }
        
        if (!is_array($recipients)) {
            throw new \InvalidArgumentException('Recipients must be an array');
        }
        
        return $recipients;
    }
    
    /**
     * Validate request based on message type
     */
    private function validateRequest(Request $request, array $recipients): array
    {
        $messageType = $request->input('message_type', 'regular');
        
        // Base validation rules
        $rules = [
            'message' => $messageType === 'rating' 
                ? 'required|string|max:500' 
                : 'required|string|max:1000',
            'recipients' => 'required|array|min:1',
            'recipients.*.phone' => 'required|string',
            'recipients.*.name' => 'nullable|string',
            'recipients.*.email' => 'nullable|email',
            'message_type' => ['required', Rule::in(['regular', 'rating'])],
            'filters' => 'nullable',
            'filters.group_id' => 'nullable|integer|exists:groups,id',
            'filters.role_id' => 'nullable|integer|exists:roles,id',
            'filters.county_id' => 'nullable|integer',
        ];
        
        $messages = [
            'message.required' => $messageType === 'rating' 
                ? 'Rating question is required.' 
                : 'Message content is required.',
            'message.max' => $messageType === 'rating' 
                ? 'Rating question cannot exceed 500 characters.' 
                : 'Message cannot exceed 1000 characters.',
            'recipients.required' => 'At least one recipient is required.',
            'recipients.*.phone.required' => 'All recipients must have a phone number.',
        ];
        
        // Add rating-specific validation
        if ($messageType === 'rating') {
            $rules['rating_config'] = 'required|string';
            $messages['rating_config.required'] = 'Rating configuration is required for rating messages.';
        }
        
        // Add attachment validation for regular messages
        if ($messageType === 'regular' && $request->hasFile('attachments')) {
            $rules['attachments'] = 'array|max:5';
            $rules['attachments.*'] = 'file|max:5120|mimes:pdf,jpg,jpeg,png,doc,docx';
            $messages['attachments.max'] = 'You can upload a maximum of 5 attachments.';
            $messages['attachments.*.max'] = 'Each attachment must not exceed 5MB.';
            $messages['attachments.*.mimes'] = 'Attachments must be PDF, JPG, PNG, DOC, or DOCX files.';
        }
        
        $validator = Validator::make(
            array_merge($request->all(), ['recipients' => $recipients]),
            $rules,
            $messages
        );
        
        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }
        
        return $validator->validated();
    }
    
    /**
     * Parse and validate rating configuration
     */
    private function parseRatingConfig(Request $request): array
    {
        $ratingConfigStr = $request->input('rating_config');
        $ratingConfig = json_decode($ratingConfigStr, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Invalid rating configuration JSON: ' . json_last_error_msg());
        }
        
        if (!is_array($ratingConfig)) {
            throw new \InvalidArgumentException('Rating configuration must be an array');
        }
        
        // Validate required fields
        $this->validateRatingConfigFields($ratingConfig);
        
        // Normalize rating config to match bot API format
        return $this->normalizeRatingConfig($ratingConfig);
    }
    
    /**
     * Validate rating configuration fields
     */
    private function validateRatingConfigFields(array $config): void
    {
        $requiredFields = ['rating_type', 'allow_comment', 'allow_skip'];
        
        foreach ($requiredFields as $field) {
            if (!array_key_exists($field, $config)) {
                throw new \InvalidArgumentException("Rating configuration missing required field: {$field}");
            }
        }
        
        // Validate rating_type value (this is what frontend sends)
        if (!in_array($config['rating_type'], ['thumbs', 'scale'])) {
            throw new \InvalidArgumentException('Rating type must be either "thumbs" or "scale"');
        }
        
        // Validate scale fields if rating type is scale
        if ($config['rating_type'] === 'scale') {
            if (!isset($config['scale_min']) || !isset($config['scale_max'])) {
                throw new \InvalidArgumentException('Scale rating type requires scale_min and scale_max');
            }
            
            $scaleMin = (int)$config['scale_min'];
            $scaleMax = (int)$config['scale_max'];
            
            if ($scaleMin >= $scaleMax) {
                throw new \InvalidArgumentException('scale_max must be greater than scale_min');
            }
            
            if ($scaleMin < 1 || $scaleMax > 10) {
                throw new \InvalidArgumentException('Scale values must be between 1 and 10');
            }
        }
    }
    
    /**
     * Normalize rating configuration to match both DB and Bot API format
     */
    private function normalizeRatingConfig(array $config): array
    {
        $normalized = [
            'rating_type' => $config['rating_type'], // 'thumbs' or 'scale'
            'allow_comment' => (bool)$config['allow_comment'],
            'allow_skip' => (bool)$config['allow_skip'],
        ];
        
        // Only include scale fields if rating type is scale
        if ($config['rating_type'] === 'scale') {
            $normalized['scale_min'] = (int)$config['scale_min'];
            $normalized['scale_max'] = (int)$config['scale_max'];
        }
        
        Log::info('Rating config validated and normalized', [
            'original' => $config,
            'normalized' => $normalized
        ]);
        
        return $normalized;
    }
    
    /**
     * Process file attachments
     */
    private function processAttachments(Request $request): array
    {
        $attachmentUrls = [];
        
        // Only regular messages can have attachments
        if ($request->input('message_type') === 'rating' || !$request->hasFile('attachments')) {
            return $attachmentUrls;
        }
        
        foreach ($request->file('attachments') as $file) {
            try {
                $path = $file->store('bot-attachments', 'public');
                
                $attachmentUrls[] = [
                    'name' => $file->getClientOriginalName(),
                    'url' => asset('storage/' . $path),
                    'type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ];
                
                Log::info('Attachment uploaded', [
                    'path' => $path, 
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize()
                ]);
                
            } catch (\Exception $e) {
                Log::error('Failed to upload attachment', [
                    'error' => $e->getMessage(),
                    'file' => $file->getClientOriginalName()
                ]);
                
                throw new \Exception("Failed to upload attachment: {$file->getClientOriginalName()}");
            }
        }
        
        return $attachmentUrls;
    }
    
    /**
     * Filter recipients to only include those with phone numbers
     */
    private function filterValidRecipients(array $recipients)
    {
        return collect($recipients)->filter(function ($recipient) {
            return !empty($recipient['phone']);
        });
    }
    
    /**
     * Create message batch
     */
    private function createBatch(Request $request, $validRecipients, ?array $ratingConfig): BotMessageBatch
    {
        $batchId = 'BOT-' . strtoupper(Str::random(10));
        $messageType = $request->input('message_type', 'regular');
        
        // Build filters
        $filters = $this->buildFilters($request, $messageType, $ratingConfig);
        
        $batch = BotMessageBatch::create([
            'batch_id' => $batchId,
            'user_id' => auth()->id(),
            'total_count' => $validRecipients->count(),
            'sent_count' => 0,
            'failed_count' => 0,
            'status' => 'processing',
            'filters' => $filters,
        ]);
        
        Log::info('Batch created', [
            'batch_id' => $batchId,
            'message_type' => $messageType,
            'recipient_count' => $validRecipients->count(),
            'filters' => $filters,
        ]);
        
        return $batch;
    }
    
    /**
     * Build filters array for batch
     */
    private function buildFilters(Request $request, string $messageType, ?array $ratingConfig): array
    {
        $rawFilters = $request->input('filters');
        
        // Handle both string and array formats
        if (is_string($rawFilters)) {
            $filters = json_decode($rawFilters, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $filters = [];
            }
        } else {
            $filters = (array)$rawFilters;
        }
        
        // Clean up filters - remove empty values
        $filters = array_filter($filters ?? [], function ($value) {
            return !is_null($value) && $value !== '';
        });
        
        $filters['message_type'] = $messageType;
        
        if ($ratingConfig) {
            $filters['rating_config'] = $ratingConfig;
        }
        
        return $filters;
    }
    
    /**
     * Create individual messages for each recipient
     */
    private function createMessages(
        Request $request, 
        $validRecipients, 
        BotMessageBatch $batch, 
        array $attachmentUrls,
        ?array $ratingConfig
    ): array {
        $messageIds = [];
        $messageType = $request->input('message_type', 'regular');
        $message = $request->input('message');
        
        $filters = is_string($request->input('filters')) 
            ? json_decode($request->input('filters'), true) 
            : (array)$request->input('filters', []);
        
        foreach ($validRecipients as $recipient) {
            $messageData = $this->buildMessageData(
                $batch,
                $recipient,
                $message,
                $attachmentUrls,
                $messageType,
                $ratingConfig,
                $filters
            );
            
            $createdMessage = BotMessage::create($messageData);
            $messageIds[] = $createdMessage->id;
            
            Log::debug('Message created', [
                'message_id' => $createdMessage->id,
                'phone' => $recipient['phone'],
                'is_rating' => $messageType === 'rating',
            ]);
        }
        
        Log::info('All messages created', [
            'count' => count($messageIds),
            'message_type' => $messageType,
            'batch_id' => $batch->batch_id,
        ]);
        
        return $messageIds;
    }
    
     private function buildMessageData(
    BotMessageBatch $batch,
    array $recipient,
    string $message,
    array $attachmentUrls,
    string $messageType,
    ?array $ratingConfig,
    array $filters
): array {
    $messageData = [
        'batch_id' => $batch->batch_id,
        'user_id' => auth()->id(),
        'name' => $recipient['name'] ?? null,
        'phone' => $recipient['phone'],
        'recipient' => $recipient['email'] ?? null,
        'message' => $message,
        'attachments' => !empty($attachmentUrls) ? $attachmentUrls : null,
        'category' => $messageType === 'rating' ? 'rating' : 'bot',
        'date' => now(),
        'group_id' => $filters['group_id'] ?? null,
        'status' => BotMessage::STATUS_QUEUED,
        'status_message' => 'Queued for sending',
    ];

    // CRITICAL: Add rating-specific fields if this is a rating message
    if ($messageType === 'rating' && $ratingConfig) {
        $messageData['is_rating'] = true;
        $messageData['rating_type'] = $ratingConfig['rating_type'];
        $messageData['allow_comment'] = (bool)$ratingConfig['allow_comment'];
        $messageData['allow_skip'] = (bool)$ratingConfig['allow_skip'];
        
        // Only add scale fields if rating type is 'scale'
        if ($ratingConfig['rating_type'] === 'scale') {
            $messageData['scale_min'] = (int)($ratingConfig['scale_min'] ?? 1);
            $messageData['scale_max'] = (int)($ratingConfig['scale_max'] ?? 5);
        } else {
            // For thumbs rating, set defaults
            $messageData['scale_min'] = 1;
            $messageData['scale_max'] = 5;
        }
        
        Log::debug('Rating message data prepared', [
            'rating_type' => $ratingConfig['rating_type'],
            'phone' => $recipient['phone'],
            'has_scale' => isset($ratingConfig['scale_min']),
            'is_rating' => true,
            'category' => 'rating',
        ]);
    } else {
        // ✅ FIXED: Set default values instead of null for regular messages
        $messageData['is_rating'] = false;
        $messageData['rating_type'] = null;
        $messageData['scale_min'] = 1;          // Changed from null
        $messageData['scale_max'] = 5;          // Changed from null
        $messageData['allow_comment'] = false;  // Changed from null - THIS FIXES YOUR ERROR
        $messageData['allow_skip'] = false;     // Changed from null
        
        Log::debug('Regular message data prepared', [
            'phone' => $recipient['phone'],
            'is_rating' => false,
        ]);
    }
    
    return $messageData;
}
    
    /**
     * Dispatch jobs to process messages
     */
    private function dispatchJobs(array $messageIds): void
    {
        $chunkSize = 100;
        $chunks = array_chunk($messageIds, $chunkSize);
        
        foreach ($chunks as $chunk) {
            SendBotMessageBatch::dispatch($chunk)->onQueue('bot-messages');
        }
        
        Log::info('Jobs dispatched', [
            'total_messages' => count($messageIds),
            'chunk_size' => $chunkSize,
            'total_chunks' => count($chunks),
        ]);
    }
    
    /**
     * Return success response
     */
    private function successResponse(
        BotMessageBatch $batch, 
        $validRecipients, 
        int $totalRecipients,
        int $attachmentCount
    ) {
        $messageType = $batch->filters['message_type'] ?? 'regular';
        
        return response()->json([
            'success' => true,
            'message' => $messageType === 'rating'
                ? 'Rating requests queued successfully'
                : 'Messages queued successfully',
            'batch_id' => $batch->batch_id,
            'total_count' => $validRecipients->count(),
            'skipped_count' => $totalRecipients - $validRecipients->count(),
            'attachments_count' => $attachmentCount,
            'message_type' => $messageType,
        ]);
    }
    
    /**
     * Return error response
     */
    private function errorResponse(string $message, int $statusCode = 422)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $statusCode);
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