<?php

namespace Vanguard\Support\Plugins\Dashboard\Widgets;

use Vanguard\Plugins\Widget;
use Illuminate\Contracts\View\View;
use Vanguard\Message;
use Illuminate\Support\Facades\Log;

class MessageWidget extends Widget
{
    public ?string $width = '4';
    protected string|\Closure|array $permissions = 'assets.my';
    protected int $messageCount = 5;

    public function render(): View
    {
        try {
            $messages = $this->getMessages();

            return view('plugins.dashboard.widgets.messages', [
                'messages' => $messages,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in MessageWidget: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => auth()->id(),
            ]);

            return view('plugins.dashboard.widgets.messages', [
                'messages' => collect(),
                'error' => __('Unable to load messages at this time.'),
            ]);
        }
    }

    protected function getMessages()
    {
        $currentUser = auth()->user();
        $query = Message::query();

        if ($currentUser->hasRole('Admin')) {
            // Admin sees all messages
        } else {
            // Regular user sees messages where they are the recipient or sender
            $query->where(function ($q) use ($currentUser) {
                $q->where('recipient', $currentUser->phone)
                  ->orWhere('user_id', $currentUser->id);
            });
        }

        $messages = $query->latest('created_at')
            ->take($this->messageCount)
            ->select('id', 'message', 'category', 'created_at', 'recipient', 'user_id')
            ->with('user:id,first_name,last_name')
            ->get();

        Log::info('MessageWidget query result', [
            'user_id' => $currentUser->id,
            'role' => $currentUser->role->name,
            'message_count' => $messages->count(),
        ]);

        return $messages;
    }

    public function setMessageCount(int $count): self
    {
        $this->messageCount = $count;
        return $this;
    }
}