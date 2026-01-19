@component('mail::message')
# Congratulations, {{ $user->first_name }}!

Your contract has been approved. You can now log in to your account.

1. Log in to your account
2. Reset your password by clicking here

@component('mail::button', ['url' => route('password.reset', ['token' => $token])])
@lang('Reset Password')
@endcomponent

or within the system
3. Engage with our platform

If you have any questions or concerns, please don't hesitate to contact our support team.

@lang('Regards'),<br>
{{ config('app.name') }}
@endcomponent