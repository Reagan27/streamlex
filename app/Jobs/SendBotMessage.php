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

class SendBotMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $messageId;
    public $timeout = 120;
    public $tries = 3;

    public function __construct($messageId)
    {
        $this->messageId = $messageId;
    }

    public function handle()
    {
        $message = BotMessage::find($this->messageId);
        
        if (!$message) {
            Log::error("Bot message not found: {$this->messageId}");
            return;
        }

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . config('services.bot.api_key'),
                    'Accept' => 'application/json',
                ])
                ->post(config('services.bot.api_url') . '/send-message', [
                    'phone' => $message->phone,
                    'message' => $message->message,
                    'batch_id' => $message->batch_id,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                
            
                $messageCost = isset($data['cost']) 
                    ? floatval(preg_replace('/[^\d.]/', '', $data['cost']))
                    : null;
                
                $message->update([
                    'status' => BotMessage::STATUS_SENT, 
                    'status_message' => $data['status'] ?? 'Sent',
                    'message_id' => $data['message_id'] ?? null,
                    'message_cost' => $messageCost,
                    'sent_at' => now(),
                    'date' => now(),
                    'response' => json_encode($data),
                ]);

                $this->updateBatchCount($message->batch_id, 'sent');
                
                Log::info("Bot message sent successfully: {$this->messageId}");
            } else {
                throw new \Exception('Bot API returned error: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error("Failed to send bot message {$this->messageId}: " . $e->getMessage());
            
            $message->update([
                'status' => BotMessage::STATUS_FAILED,
                'status_message' => 'Failed: ' . $e->getMessage(),
                'response' => $e->getMessage(),
            ]);

            $this->updateBatchCount($message->batch_id, 'failed');
            
            throw $e;
        }
    }

    protected function updateBatchCount($batchId, $type)
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

    public function failed(\Throwable $exception)
    {
        $message = BotMessage::find($this->messageId);
        
        if ($message) {
            $message->update([
                'status' => BotMessage::STATUS_FAILED,
                'status_message' => 'Failed permanently',
                'response' => $exception->getMessage(),
            ]);
            
            $this->updateBatchCount($message->batch_id, 'failed');
        }
        
        Log::error("Bot message job failed permanently: {$this->messageId}", [
            'exception' => $exception->getMessage()
        ]);
    }
}