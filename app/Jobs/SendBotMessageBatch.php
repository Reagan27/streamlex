<?php

namespace Vanguard\Jobs;

use Vanguard\BotMessage;
use Vanguard\BotMessageBatch;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendBotMessageBatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $messageIds;

    public $timeout = 600;
    public $tries = 3;
    public $backoff = [60, 180, 360];

    public function __construct(array $messageIds)
    {
        $this->messageIds = $messageIds;
    }

   public function handle()
{
    Log::info("SendBotMessageBatch started", [
        'message_count' => count($this->messageIds),
    ]);

    $messages = BotMessage::whereIn('id', $this->messageIds)->get();

    if ($messages->isEmpty()) {
        Log::error("No bot messages found", [
            'message_ids' => $this->messageIds,
        ]);
        return;
    }

    $apiUrl = config('services.bot.api_url', 'https://babot.eassysoft.com');

    if (empty($apiUrl)) {
        Log::error("BOT_API_URL not configured");
        return;
    }

    $delayBetweenMessages = 1;
    $successCount = 0;
    $failCount = 0;

    foreach ($messages as $message) {
        try {
            $phoneNumber = $this->formatPhoneNumber($message->phone);

            // Build metadata
            $metadata = [
                'message_id' => (string) $message->id,
                'phone'      => $phoneNumber,
                'source'     => 'fos',
            ];
            
            if ($message->user_id !== null) {
                $metadata['fos_user_id'] = (string) $message->user_id;
            }
            
            if ($message->attachments && is_array($message->attachments)) {
                $metadata['attachments'] = $message->attachments;
            }

            // IMPROVED DETECTION: Check multiple fields
            $isRating = ($message->is_rating == true) || 
                        ($message->category === 'rating') || 
                        (!empty($message->rating_type));

            Log::info("🔍 Processing message", [
                'message_id' => $message->id,
                'is_rating_field' => $message->is_rating,
                'rating_type_field' => $message->rating_type,
                'category' => $message->category,
                'isRating_detected' => $isRating,
            ]);

            if ($isRating) {
                $payload = $this->buildRatingPayload($message, $phoneNumber, $metadata);
                Log::info("✅ Sending RATING request", [
                    'message_id'  => $message->id,
                    'rating_type' => $message->rating_type,
                    'phone' => $phoneNumber,
                ]);
            } else {
                $payload = $this->buildAnnouncementPayload($message, $phoneNumber, $metadata);
                Log::info("✅ Sending ANNOUNCEMENT", [
                    'message_id' => $message->id,
                    'phone' => $phoneNumber,
                ]);
            }

            // Send to Bot API
            $response = Http::timeout(30)
                ->acceptJson()
                ->post($apiUrl . '/api/v1/broadcast', $payload);

            if (!$response->successful()) {
                throw new \Exception(
                    "Bot API error {$response->status()}: " . $response->body()
                );
            }

            $data = $response->json();

            // Update message as sent
            $message->update([
                'status'         => BotMessage::STATUS_SENT,
                'status_message' => 'Sent',
                'message_id'     => $data['request_id'] ?? null,
                'sent_at'        => now(),
                'date'           => now(),
                'response'       => json_encode($data),
            ]);

            $successCount++;
            
            Log::info("✅ Message sent successfully", [
                'message_id' => $message->id,
                'type' => $isRating ? 'RATING' : 'ANNOUNCEMENT',
            ]);
            
            // Rate limiting
            sleep($delayBetweenMessages);

        } catch (\Throwable $e) {
            $failCount++;

            Log::error("❌ Message send failed", [
                'message_id' => $message->id,
                'error'      => $e->getMessage(),
            ]);

            $message->update([
                'status'         => BotMessage::STATUS_FAILED,
                'status_message' => 'Failed: ' . substr($e->getMessage(), 0, 255),
                'response'       => $e->getMessage(),
            ]);
        }
    }

    // Update batch statistics
    $this->updateBatchStatistics($messages->first()->batch_id ?? null, $successCount, $failCount);

    Log::info("SendBotMessageBatch completed", [
        'sent'   => $successCount,
        'failed' => $failCount,
    ]);
}

    /**
     * Build rating payload according to Bot API format
     */
    protected function buildRatingPayload(BotMessage $message, string $phoneNumber, array $metadata): array
    {
        $ratingType = $message->rating_type ?? 'thumbs';
        
        // Build rating object according to Bot API format
        $rating = [
            'question'      => $message->message,
            'rating_type'   => $ratingType,
            'allow_skip'    => (bool) ($message->allow_skip ?? true),
            'allow_comment' => (bool) ($message->allow_comment ?? false),
        ];

        // Only add scale fields if rating type is 'scale'
        if ($ratingType === 'scale') {
            $rating['scale_min'] = (int) ($message->scale_min ?? 1);
            $rating['scale_max'] = (int) ($message->scale_max ?? 5);
        }

        // Build the complete payload matching Bot API format
        $payload = [
            'message_type' => 'rating',
            'request_id' => (string) $message->id,
            'channel'      => ['whatsapp','telegram'], // Array format as per API spec
            'rating'       => $rating,
            'audience'     => [
                'phone_numbers' => [$phoneNumber]
            ],
            'rate_limit_per_second' => 10,
            'metadata'     => $metadata,
        ];

        Log::debug("Rating payload built", [
            'message_id' => $message->id,
            'rating_type' => $ratingType,
            'payload' => $payload,
        ]);

        return $payload;
    }

    /**
     * Build announcement payload
     */
    protected function buildAnnouncementPayload(BotMessage $message, string $phoneNumber, array $metadata): array
    {
        $payload = [
            'message_type' => 'announcement',
            'request_id' => (string) $message->id,
            'channel'      => ['whatsapp','telegram'], // Array format
            'body'         => $message->message,
            'audience'     => [
                'phone_numbers' => [$phoneNumber]
            ],
            'rate_limit_per_second' => 10,
            'metadata'     => $metadata,
        ];

        // Add title if name is provided
        if (!empty($message->name)) {
            $payload['title'] = $message->name;
        }

        return $payload;
    }

    /**
     * Format phone number to international format
     */
    protected function formatPhoneNumber(string $phone): string
    {
        $phone = trim($phone);

        // If already has +, return as is
        if (str_starts_with($phone, '+')) {
            return $phone;
        }

        // Handle Kenyan numbers
        if (str_starts_with($phone, '0')) {
            // 0712345678 -> +254712345678
            return '+254' . substr($phone, 1);
        }
        
        if (str_starts_with($phone, '254')) {
            // 254712345678 -> +254712345678
            return '+' . $phone;
        }

        // Default: add + prefix
        return '+' . $phone;
    }

    /**
     * Update batch statistics
     */
    protected function updateBatchStatistics(?string $batchId, int $successCount, int $failCount): void
    {
        if (!$batchId) {
            return;
        }

        try {
            $batch = BotMessageBatch::where('batch_id', $batchId)->first();
            
            if ($batch) {
                $batch->increment('sent_count', $successCount);
                $batch->increment('failed_count', $failCount);
                
                // Update batch status if all messages processed
                $totalProcessed = $batch->sent_count + $batch->failed_count;
                if ($totalProcessed >= $batch->total_count) {
                    $batch->update([
                        'status' => $batch->failed_count > 0 ? 'completed_with_errors' : 'completed'
                    ]);
                }
                
                Log::info("Batch statistics updated", [
                    'batch_id' => $batchId,
                    'sent_count' => $batch->sent_count,
                    'failed_count' => $batch->failed_count,
                    'total_count' => $batch->total_count,
                ]);
            }
        } catch (Exception $e) {
            Log::error("Failed to update batch statistics", [
                'batch_id' => $batchId,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Handle job failure
     */
    public function failed(\Throwable $exception)
    {
        Log::error("Batch job failed permanently", [
            'message_ids' => $this->messageIds,
            'exception' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);

        // Mark all messages in this batch as failed
        BotMessage::whereIn('id', $this->messageIds)
            ->where('status', BotMessage::STATUS_QUEUED)
            ->update([
                'status' => BotMessage::STATUS_FAILED,
                'status_message' => 'Job failed: ' . substr($exception->getMessage(), 0, 255),
            ]);
    }
}