<?php

namespace Vanguard\Http\Controllers\Web\Auth;

use Auth;
use Illuminate\Contracts\Auth\Authenticatable as BaseAuthenticatable;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Notifications\ReportSubmissionReminder;
use Vanguard\BackToOfficeReport;
use Vanguard\Events\User\LoggedIn;
use Vanguard\Events\User\LoggedOut;
use Vanguard\GeneralReport;
use Vanguard\Http\Controllers\Controller;
use Vanguard\ReportReminderLog;
use Vanguard\Http\Requests\Auth\LoginRequest;
use Vanguard\Repositories\Session\SessionRepository;
use Vanguard\Repositories\User\UserRepository;
use Vanguard\Services\Auth\ThrottlesLogins;
use Vanguard\User;

class LoginController extends Controller
{
    use ThrottlesLogins;

    public function __construct(private readonly UserRepository $users)
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Show the application login form.
     */
    public function show(): View
    {
        return view('auth.login', [
            'socialProviders' => config('auth.social.providers'),
        ]);
    }

    public function login(LoginRequest $request, SessionRepository $sessions): Response|RedirectResponse
    {
        // In case that request throttling is enabled, we have to check if user can perform this request.
        // We'll key this by the username and the IP address of the client making these requests into this application.
        $throttles = (bool) setting('throttle_enabled');

        //Redirect URL that can be passed as hidden field.
        $to = $request->has('to') ? '?to='.$request->get('to') : '';

        if ($throttles && $this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }

        $credentials = $request->getCredentials();

        if (! Auth::validate($credentials)) {
            // If the login attempt was unsuccessful we will increment the number of attempts
            // to log in and redirect the user back to the login form. Of course, when this
            // user surpasses their maximum number of attempts they will get locked out.
            if ($throttles) {
                $this->incrementLoginAttempts($request);
            }

            return redirect()->to('login'.$to)
                ->withErrors(trans('auth.failed'));
        }

        $user = Auth::getProvider()->retrieveByCredentials($credentials);

        if ($user->isBanned()) {
            return redirect()->to('login'.$to)
                ->withErrors(trans('auth.banned'));
        }

        $maxSessions = setting('max_active_sessions');
        if ($maxSessions && $sessions->getActiveSessionsCount($user->id) >= $maxSessions) {
            return redirect()->to('login'.$to)
                ->withErrors(trans('auth.max_sessions_reached'));
        }

        Auth::login($user, setting('remember_me') && $request->get('remember'));

        return $this->authenticated($request, $throttles, $user);
    }

    /**
     * Send the response after the user was authenticated.
     */
    protected function authenticated(
        Request $request,
        bool $throttles,
        BaseAuthenticatable $user,
    ): Response|RedirectResponse {
        if ($throttles) {
            $this->clearLoginAttempts($request);
        }

        $redirectPage = $request->get('to');

        if (setting('2fa.enabled') && $user->twoFactorEnabled()) {
            return $this->logoutAndRedirectToTokenPage($request, $user, $redirectPage);
        }

        event(new LoggedIn);

        $this->dispatchReportSubmissionReminder($user);

        if ($redirectPage) {
            return redirect()->to($redirectPage);
        }

        return redirect()->intended();
    }

    protected function dispatchReportSubmissionReminder(BaseAuthenticatable $user): void
    {
        if (! $user instanceof User) {
            return;
        }

        foreach (['general', 'back_to_office'] as $type) {
            if ($this->hasOutstandingReport($user, $type)) {
                $label = $type === 'general' ? 'General' : 'Back to Office';
                $message = "You have not yet submitted your {$label} report for this month. Please submit it as soon as possible.";

                $lastSent = ReportReminderLog::where('user_id', $user->id)
                    ->where('report_type', $type)
                    ->latest('sent_at')
                    ->first();

                if (! $lastSent || $lastSent->sent_at->diffInDays(now()) >= 3) {
                    $user->notify(new ReportSubmissionReminder($type, $message));
                    ReportReminderLog::create([
                        'user_id' => $user->id,
                        'report_type' => $type,
                        'sent_at' => now(),
                    ]);
                }

                session()->flash('info', $message);
                break;
            }
        }
    }

    protected function hasOutstandingReport(User $user, string $reportType): bool
    {
        $query = $reportType === 'general'
            ? GeneralReport::where('created_by', $user->id)
            : BackToOfficeReport::where('created_by', $user->id);

        return ! $query->whereIn('status', ['submitted', 'approved'])
            ->where('created_at', '>=', now()->subDays(30))
            ->exists();
    }

    protected function logoutAndRedirectToTokenPage(Request $request, $user, ?string $redirectPage): RedirectResponse
    {
        Auth::logout();

        $request->session()->put('auth.2fa.id', $user->id);

        if ($redirectPage) {
            $request->session()->put('auth.redirect_to', $redirectPage);
        }

        return redirect()->route('auth.token');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(): RedirectResponse
    {
        event(new LoggedOut);

        Auth::logout();

        return redirect('login');
    }
}
