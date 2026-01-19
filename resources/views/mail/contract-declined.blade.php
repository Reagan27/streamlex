@component('mail::message')
# Dear {{ $user->first_name }},

Your contract details have been declined. Please login and review the following:

**Decline Reason:** {{ $declineReason }}

If you have any questions or concerns, please don't hesitate to contact our support team.

@lang('Regards'),<br>
{{ setting('app_name') }}
@endcomponent