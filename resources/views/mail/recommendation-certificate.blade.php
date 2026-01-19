@component('mail::message')
# Dear {{ $user->first_name }},

We are pleased to inform you that a {{ $type }} has been generated for you.

@if($type === 'recommendation')
Your overall performance details:
- Overall Rating: {{ number_format($overallRating, 2) }}
- Overall Percentage: {{ number_format($overallPercentage, 2) }}%
@else
This certificate of service acknowledges your time with our organization.
@endif

You can find your {{ $type }} attached to this email.

If you have any questions or need further information, please don't hesitate to contact our HR department.

@lang('Regards'),<br>
{{ setting('app_name') }}
@endcomponent