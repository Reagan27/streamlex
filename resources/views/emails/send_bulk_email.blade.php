@include('partials.messages')

<div class="card">
    <div class="card-body">
        <h5 class="card-title">@lang('Filters')</h5>
        <form method="POST" action="{{ route('emails.send_bulk') }}" id="bulkEmailForm">
            @csrf

            <div class="row">
                <div class="col-md-3">
                    <label>@lang('All Users')</label>
                    <div class="d-flex align-items-center">
                        <input type="hidden" value="0" name="all_users">
                        <input type="checkbox" name="all_users" id="switch-all-users" class="switch" value="1">
                        <label for="switch-all-users"></label>
                    </div>
                </div>

                <div class="col-md-3">
                    <label for="groupFilter">@lang('Group')</label>
                    <select name="group" id="groupFilter" class="form-control">
                        <option value="">@lang('Select a Group')</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="roleFilter">@lang('Role')</label>
                    <select name="role" id="roleFilter" class="form-control">
                        <option value="">@lang('Select a Role')</option>
                        <option value="Regional Coordinator">@lang('Regional Coordinator')</option>
                        <option value="County Coordinator">@lang('County Coordinator')</option>
                        <option value="Supervisor">@lang('Supervisor')</option>
                        <option value="Field Officer">@lang('Field Officer')</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="countyFilter">@lang('County')</label>
                    <select name="county" id="countyFilter" class="form-control">
                        <option value="">@lang('Select a County')</option>
                        @foreach($counties as $countyId => $countyName)
                            <option value="{{ $countyId }}">{{ $countyName }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group mt-4">
                <label for="subject">@lang('Subject')</label>
                <input type="text" name="subject" class="form-control" id="subject" required>
            </div>

            <div class="form-group mt-4">
                <label for="emailMessage">@lang('Message')</label>
                <textarea name="message" class="form-control" rows="5" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary" disabled>@lang('Send Bulk Email')</button>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#roleFilter, #countyFilter').prop('disabled', false);
    $('#switch-all-users').prop('checked', false);

    $('#switch-all-users').change(function() {
        if ($(this).is(':checked')) {
            $('#groupFilter, #roleFilter, #countyFilter').prop('disabled', true).val('');
            $('#bulkEmailForm button[type="submit"]').prop('disabled', false);
        } else {
            $('#groupFilter, #roleFilter, #countyFilter').prop('disabled', false);
            $('#bulkEmailForm button[type="submit"]').prop('disabled', true);
        }
    });

    $('#groupFilter').change(function() {
        if ($(this).val()) {
            $('#roleFilter, #countyFilter').prop('disabled', true).val('');
        } else {
            $('#roleFilter, #countyFilter').prop('disabled', false);
        }
        $('#bulkEmailForm button[type="submit"]').prop('disabled', !$(this).val());
    });

    $('#roleFilter, #countyFilter').change(function() {
        if (!$('#switch-all-users').is(':checked') && ($('#roleFilter').val() || $('#countyFilter').val())) {
            $('#bulkEmailForm button[type="submit"]').prop('disabled', false);
        } else {
            $('#bulkEmailForm button[type="submit"]').prop('disabled', true);
        }
    });

    $('#bulkEmailForm').on('submit', function(event) {
        var message = $('textarea[name="message"]').val();
        var subject = $('#subject').val();

        if (message.trim() === '') {
            alert('Please enter a message before sending.');
            event.preventDefault();
            return;
        }

        if (subject.trim() === '') {
            alert('Please enter a subject before sending.');
            event.preventDefault();
            return;
        }

        if ($('#switch-all-users').is(':checked')) {
            if (!confirm('Are you sure you want to send bulk emails to all users excluding those in groups?')) {
                event.preventDefault();
            }
        }
    });
});
</script>
