<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Vanguard\Http\Controllers\Controller;
use App\Models\PolicyAcknowledgement; // Correct namespace for the model
use Illuminate\Support\Facades\Log;



class PolicyAcknowledgementController extends Controller
{
    public function __construct()
    {
        Log::info('PolicyAcknowledgementController loaded');
    }

    public function show()
    {
        $user = Auth::user();
        $acknowledgement = PolicyAcknowledgement::where('user_id', $user->id)->first();

        if (!$acknowledgement) {
            return view('policy_acknowledgement', [
                'acknowledgement' => null,
                'alreadyAcknowledged' => false,
            ]);
        }

        $alreadyAcknowledged = $acknowledgement !== null;
        return view('policy_acknowledgement', compact('acknowledgement', 'alreadyAcknowledged'));
    }

    public function acknowledge(Request $request)
    {
        $request->validate([
            'confirm_read' => 'required',
            'confirm_comply' => 'required',
            'confirm_accurate' => 'required',
            'employee_name' => 'required|string|max:255',
        ]);
        $user = Auth::user();
        $ack = PolicyAcknowledgement::firstOrCreate(
            ['user_id' => $user->id],
            [
                'employee_name' => $request->employee_name,
                'acknowledged_at' => now(),
            ]
        );
        return redirect()->route('policy.acknowledgement')->with('success', 'Policy acknowledged successfully.');
    }
}
