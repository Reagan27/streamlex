@component('mail::message')
# Dear {{ $user->first_name }},

We have reviewed your performance appraisals and would like to provide you with some feedback.

Your current performance metrics:
- Overall Rating: {{ number_format($overallRating, 2) }}
- Overall Percentage: {{ number_format($overallPercentage, 2) }}%

At this time, we are unable to provide a recommendation letter based on these results. We encourage you to continue working on improving your performance in the following areas:
- Motivation
- Resourcefulness
- Leadership
- Discipline
- Teamwork

Your supervisor will be in touch to discuss strategies for improvement and to set goals for the upcoming review period.

If you have any questions or would like to discuss this further, please don't hesitate to contact your supervisor or the HR department.

@lang('Regards'),<br>
{{ setting('app_name') }}
@endcomponent