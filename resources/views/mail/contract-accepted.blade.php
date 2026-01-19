@component('mail::message')
# Congratulations, {{ $user->first_name }}!

Your contract has been accepted. You can now view and download your contract by logging into your account.

To view your contract:
1. Log in to your account
2. Navigate to My Contract
3. Click on "View Contract" and Download

If you have any questions or concerns, please don't hesitate to contact our support team.

@lang('Regards'),<br>
{{ setting('app_name') }}
@endcomponent