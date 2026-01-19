<?php

namespace Vanguard\Jobs;

use Vanguard\BotMessage;
use Vanguard\BotMessageBatch;
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

                // Check if message is a rating
                $isRating = !empty($message->is_rating) || !empty($message->rating_type);

                if ($isRating) {
                    // Build rating configuration
                    $rating = [
                        'question'      => $message->message,
                        'rating_type'   => $message->rating_type ?? 'thumbs',
                        'allow_skip'    => (bool) ($message->allow_skip ?? true),
                        'allow_comment' => (bool) ($message->allow_comment ?? false),
                    ];

                    // Add scale fields only if rating_type is 'scale'
                    if (($message->rating_type ?? 'thumbs') === 'scale') {
                        $rating['scale_min'] = (int) ($message->scale_min ?? 1);
                        $rating['scale_max'] = (int) ($message->scale_max ?? 5);
                    }

                    // Build the rating payload
                    $payload = [
                        'message_type' => 'rating',
                        'channel'      => ['telegram', 'whatsapp'],
                        'rating'       => $rating,
                        'audience'     => ['phone_numbers' => [$phoneNumber]],
                        'rate_limit_per_second' => 10,
                        'metadata' => $metadata,
                    ];

                    Log::info("✅ Sending RATING message", [
                        'message_id'  => $message->id,
                        'rating_type' => $rating['rating_type'],
                        'question'    => $rating['question'],
                        'payload'     => $payload,
                    ]);

                } else {
                    // Announcement message
                    $payload = [
                        'message_type' => 'announcement',
                        'channel'      => ['telegram', 'whatsapp'],
                        'body'         => $message->message,
                        'audience'     => ['phone_numbers' => [$phoneNumber]],
                        'rate_limit_per_second' => 10,
                        'metadata' => $metadata,
                    ];

                    if (!empty($message->name)) {
                        $payload['title'] = $message->name;
                    }

                    Log::info("✅ Sending ANNOUNCEMENT message", [
                        'message_id' => $message->id,
                    ]);
                }

                $response = Http::timeout(30)
                    ->acceptJson()
                    ->post($apiUrl . '/api/v1/broadcast', $payload);

                if (!$response->successful()) {
                    throw new \Exception(
                        "Bot API error {$response->status()}: " . $response->body()
                    );
                }

                $data = $response->json();

                $message->update([
                    'status'         => BotMessage::STATUS_SENT,
                    'status_message' => 'Sent',
                    'message_id'     => $data['request_id'] ?? null,
                    'sent_at'        => now(),
                    'date'           => now(),
                    'response'       => json_encode($data),
                ]);

                $successCount++;
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

        Log::info("SendBotMessageBatch completed", [
            'sent'   => $successCount,
            'failed' => $failCount,
        ]);
    }

    protected function formatPhoneNumber(string $phone): string
    {
        $phone = trim($phone);

        if (!str_starts_with($phone, '+')) {
            if (str_starts_with($phone, '0')) {
                return '+254' . substr($phone, 1);
            }
            if (str_starts_with($phone, '254')) {
                return '+' . $phone;
            }
            return '+' . $phone;
        }

        return $phone;
    }

    public function failed(\Throwable $exception)
    {
        Log::error("🚨 Batch failed permanently", [
            'exception' => $exception->getMessage(),
        ]);
    }
}