@component('mail::message')
# @lang('Hello!')

You're receiving this email because your account was recently created at Selister.
Please use the default password 12345678 to complete the onboarding process.
Once completed, you can access and start using the application.

@component('mail::button', ['url' => config('app.url')])
Access CPHRM
@endcomponent

If you have any questions or concerns, please don't hesitate to contact our support team.

@lang('Regards'),<br>
{{ config('app.name') }}
@endcomponent