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
use Vanguard\Contact;
use Vanguard\County;
use Vanguard\Jobs\SendBotMessageBatch;
use Vanguard\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BotController extends Controller
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
        ];

        return view('bot.index', compact('batches', 'stats'));
    }

    public function compose()
    {
        $templates = BotTemplate::where('is_active', true)->get();
        $roles = Role::all();
        $counties = County::pluck('name', 'id');
        $groups = Group::where('type', Group::TYPE_CONTACT)->get();

        return view('bot.compose', compact('templates', 'roles', 'counties', 'groups'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'recipients' => 'required|array|min:1',
            'recipients.*.phone' => 'nullable|string',
            'recipients.*.name' => 'nullable|string',
            'recipients.*.email' => 'nullable|email',
            'filters' => 'nullable|array',
        ]);

        $batchId = 'BOT-' . strtoupper(Str::random(10));

        // Filter recipients with at least phone or email
        $validRecipients = collect($request->recipients)->filter(fn($r) => !empty($r['phone']) || !empty($r['email']));

        $batch = BotMessageBatch::create([
            'batch_id' => $batchId,
            'user_id' => auth()->id(),
            'total_count' => $validRecipients->count(),
            'sent_count' => 0,
            'failed_count' => 0,
            'status' => 'processing',
            'filters' => $request->filters ?? [],
        ]);

        $messageIds = [];

        foreach ($validRecipients as $recipient) {
            $message = BotMessage::create([
                'batch_id' => $batchId,
                'user_id' => auth()->id(),
                'name' => $recipient['name'] ?? null,
                'phone' => $recipient['phone'] ?? null,
                'recipient' => $recipient['email'] ?? null,
                'message' => $request->message,
                'category' => 'bot',
                'date' => now(),
                'group_id' => $request->filters['group_id'] ?? null,
                'status' => BotMessage::STATUS_QUEUED,
                'status_message' => 'Queued',
            ]);

            $messageIds[] = $message->id;
        }

        // Dispatch in batches of 100 for Redis queue
        foreach (array_chunk($messageIds, 100) as $chunk) {
            SendBotMessageBatch::dispatch($chunk)->onQueue('bot-messages');
        }

        return response()->json([
            'success' => true,
            'message' => 'Messages queued successfully',
            'batch_id' => $batchId,
            'total_count' => $validRecipients->count(),
            'skipped_count' => count($request->recipients) - $validRecipients->count(),
        ]);
    }

    public function batchDetails($batchId)
    {
        $batch = BotMessageBatch::where('batch_id', $batchId)
            ->with(['messages' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->firstOrFail();

        return view('bot.batch-details', compact('batch'));
    }

    public function getRecipients(Request $request)
    {
        $recipients = collect();

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
            $query = User::where('status', 'Active');

            if ($request->role_id) $query->where('role_id', $request->role_id);
            if ($request->county_id) $query->where('county_id', $request->county_id);
            if ($request->sub_county_id) $query->where('sub_county_id', $request->sub_county_id);
            if ($request->ward_id) $query->where('ward_id', $request->ward_id);

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
            'recipients' => $recipients,
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
}
