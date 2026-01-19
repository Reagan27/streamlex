<?php

namespace Vanguard\Jobs;

use Vanguard\Message;
use Vanguard\MessageBatch;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $all;
    protected $recipients;

    public $timeout = 600; // Timeout set to 10 minutes (adjust as needed)

    public function __construct($all, $recipients)
    {
        $this->all = $all;
        $this->recipients = $recipients;
    }

    public function handle()
    {
        $chunkSize = 2; // Customize the chunk size based on API limits and requirements
        $delayBetweenChunks = 10; // Delay between each chunk (in seconds)
        $successfulSends = 0;

        foreach (array_chunk($this->recipients->toArray(), $chunkSize) as $chunk) {
            foreach ($chunk as $recipient) {
                try {
                    $smsRequest = [
                        'username' => config('services.africastalking.username'),
                        'to' => $recipient['phone'],
                        'from' => config('services.africastalking.sender_id'),
                        'message' => $this->all['message'],
                    ];

                    $response = Http::withHeaders([
                        'Conteant-Type' => 'application/x-www-form-urlencoded',
                        'Accept' => 'application/json',
                        'apiKey' => config('services.africastalking.api_key'),
                    ])->asForm()->post('https://api.africastalking.com/version1/messaging', $smsRequest);

                    if ($response->failed()) {
                        Log::error("Failed to send SMS to {$recipient['phone']}", ['error' => $response->body()]);
                        continue;
                    }

                    $messageData = $response->json();
                    $recipientData = $messageData['SMSMessageData']['Recipients'][0];
                    $messageCost = floatval(preg_replace('/[^\d.]/', '', $recipientData['cost']));

                    Message::create([
                        'phone' => $recipient['phone'],
                        'user_id' => $recipient['id'] ?? null,
                        'recipient' => $recipient['email'] ?? null,
                        'message' => $this->all['message'],
                        'batch_id' => $this->all['batchId'],
                        'status' => $recipientData['statusCode'],
                        'category' => $this->all['category'],
                        'message_cost' => $messageCost,
                        'message_id' => $recipientData['messageId'],
                        'status_message' => $recipientData['status'],
                    ]);

                    // Increment successfulSends only for successful status code 101
                    if ($recipientData['statusCode'] == 101) {
                        $successfulSends++;
                    }

                } catch (\Exception $e) {
                    Log::error("Exception occurred while sending SMS to {$recipient['phone']}: " . $e->getMessage());
                }
            }

            // Update sent count in the batch record after processing each chunk
            MessageBatch::where('batchId', $this->all['batchId'])->increment('sent', $successfulSends);

            // Reset successful sends count after each batch update
            $successfulSends = 0;

            // Delay to avoid rate limits
            sleep($delayBetweenChunks);
        }
    }
}
