@extends('layouts.app')

@section('page-title', __('Appraisal History'))
@section('page-heading', __('Appraisal History for ' . $user->first_name . ' ' . $user->last_name))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('appraisals.index') }}">@lang('Appraisals')</a>
    </li>
    <li class="breadcrumb-item active">
        @lang('History')
    </li>
@stop

@section('content')

@include('partials.messages')

<div class="card">
    <div class="card-body">
 
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('appraisals.create', $user) }}" class="btn btn-primary mr-2">
                @lang('Add Appraisal')
            </a>
            @if($user->completed)
                <button type="button" class="btn btn-secondary" id="generateRecommendationBtn">
                    @lang('Generate Recommendation')
                </button>
            @endif
        </div>
        </div>
        <div class="table-responsive">
            <table class="table table-borderless table-striped">
                <thead>
                    <tr>
                        <th>@lang('Appraisal Date')</th>
                        <th>@lang('Week')</th>
                        <th>@lang('Overall Rating')</th>
                        <th>@lang('Status')</th>
                        <th class="text-center">@lang('Action')</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($appraisals))
                        @foreach ($appraisals as $appraisal)
                            <tr>
                                <td>{{ $appraisal->appraisal_date->format('Y-m-d') }}</td>
                                <td>{{ preg_replace('/^week\s*/i', '', $appraisal->period) }}</td>
                                <td>{{ number_format($appraisal->overall_rating, 2) }}</td>
                                <td>{{ $appraisal->status ? 'Completed' : 'Pending' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('appraisals.show', $appraisal) }}" class="btn btn-icon"
                                       title="@lang('View Appraisal')" data-toggle="tooltip" data-placement="top">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5"><em>@lang('No appraisals found.')</em></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

{!! $appraisals->render() !!}

<div class="modal fade" id="recommendationModal" tabindex="-1" role="dialog" aria-labelledby="recommendationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="recommendationModalLabel">Generate Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="documentTypeSelection">
                    <p>Select the type of document to generate:</p>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="documentType" id="recommendationType" value="recommendation" checked>
                        <label class="form-check-label" for="recommendationType">
                            Recommendation Letter
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="documentType" id="certificateType" value="certificate">
                        <label class="form-check-label" for="certificateType">
                            Certificate of Service
                        </label>
                    </div>
                    <button id="selectTypeBtn" class="btn btn-primary mt-3">Next</button>
                </div>
                <div id="emailConfirmation" style="display: none;">
                    <p>Please confirm or update the email address:</p>
                    <input type="email" id="userEmail" class="form-control" value="{{ $user->email }}">
                    <button id="proceedBtn" class="btn btn-primary mt-3">Proceed</button>
                </div>
                <div id="documentResult" style="display: none;">
                    <p id="resultMessage"></p>
                    <div id="supervisorInfo" style="display: none;">
                        <h6>Supervisor Information:</h6>
                        <p><strong>Name:</strong> <span id="supervisorName"></span></p>
                        <p><strong>Email:</strong> <span id="supervisorEmail"></span></p>
                        <p><strong>Phone:</strong> <span id="supervisorPhone"></span></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="sendDocumentBtn" class="btn btn-primary" style="display: none;">Send Document</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
@parent
<script>
$(document).ready(function() {
    var selectedType = 'recommendation';

    $('#generateRecommendationBtn').click(function() {
        $('#recommendationModal').modal('show');
        $('#documentTypeSelection').show();
        $('#emailConfirmation').hide();
        $('#documentResult').hide();
        $('#sendDocumentBtn').hide();
    });

    $('input[name="documentType"]').change(function() {
        selectedType = $(this).val();
    });

    $('#selectTypeBtn').click(function() {
        $('#documentTypeSelection').hide();
        $('#emailConfirmation').show();
    });

    $('#proceedBtn').click(function() {
        var email = $('#userEmail').val();
        $.ajax({
            url: "{{ route('appraisals.generate', $user) }}",
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                email: email,
                type: selectedType
            },
            success: function(response) {
                $('#emailConfirmation').hide();
                $('#documentResult').show();
                if (response.meetsRequirements) {
                    if (selectedType === 'recommendation') {
                        $('#resultMessage').html('User meets the requirements.<br>Overall Rating: ' + response.overallRating + '<br>Overall Percentage: ' + response.overallPercentage + '%');
                    } else {
                        $('#resultMessage').text('Certificate of Service is ready to be generated.');
                    }
                    $('#sendDocumentBtn').show();

                    // Display supervisor information
                    if (response.supervisor) {
                        $('#supervisorName').text(response.supervisor.first_name + ' ' + response.supervisor.last_name);
                        $('#supervisorEmail').text(response.supervisor.email);
                        $('#supervisorPhone').text(response.supervisor.phone);
                        $('#supervisorInfo').show();
                    } else {
                        $('#supervisorInfo').hide();
                    }
                } else {
                    if (selectedType === 'recommendation') {
                        $('#resultMessage').html('User does not meet the requirements for a recommendation letter.<br>Overall Rating: ' + response.overallRating + '<br>Overall Percentage: ' + response.overallPercentage + '%');
                    } else {
                        $('#resultMessage').text('Unable to generate Certificate of Service: ' + response.message);
                    }
                    $('#sendDocumentBtn').hide();
                    $('#supervisorInfo').hide();
                }
            },
            error: function(xhr, status, error) {
                var errorMessage = xhr.responseJSON ? xhr.responseJSON.message : 'An unknown error occurred';
                $('#resultMessage').text('Error: ' + errorMessage);
                $('#documentResult').show();
                $('#emailConfirmation').hide();
                $('#sendDocumentBtn').hide();
                $('#supervisorInfo').hide();
            }
        });
    });

    $('#sendDocumentBtn').click(function() {
        var email = $('#userEmail').val();
        $.ajax({
            url: "{{ route('appraisals.generate', $user) }}",
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                email: email,
                type: selectedType,
                send: true
            },
            success: function(response) {
                alert(response.message);
                $('#recommendationModal').modal('hide');
            },
            error: function(xhr, status, error) {
                var errorMessage = xhr.responseJSON ? xhr.responseJSON.message : 'An unknown error occurred';
                alert('Error: ' + errorMessage);
            }
        });
    });
});
</script>
@endsection