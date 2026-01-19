@include('partials.messages')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<div class="card">
    <div class="card-body">
        <h5 class="card-title">@lang('Filters')</h5>
        <!-- Filters form -->
        <form id="filterForm" method="GET">
            <div class="row">
                <!-- Group Filter -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="group_id">@lang('Group')</label>
                        <select name="group_id" id="group_id" class="form-control">
                            <option value="">@lang('Select Group')</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Role Filter -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="role_id">@lang('Role')</label>
                        <select name="role_id" id="role_id" class="form-control">
                            <option value="">@lang('Select Role')</option>
                            @foreach($roles as $roleId => $roleName)
                                <option value="{{ $roleId }}">{{ $roleName }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- County Filter -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="county_id">@lang('County')</label>
                        <select name="county_id" id="county_id" class="form-control">
                            <option value="">@lang('Select County')</option>
                            @foreach($counties as $countyId => $countyName)
                                <option value="{{ $countyId }}">{{ $countyName }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Send Select Email form -->
<form method="POST" action="{{ route('emails.send_select') }}" class="mt-4">
    @csrf
    <div class="form-group">
        <label for="selectedRecipients">@lang('Selected Recipients')</label>
        <select name="selectedRecipients[]" id="selectedRecipients" class="form-control" multiple required>
        </select>
    </div>

    <div class="form-group">
        <label for="subject">@lang('Subject')</label>
        <input type="text" name="subject" class="form-control" id="subject" required>
    </div>

    <div class="form-group">
        <label for="message">@lang('Message')</label>
        <textarea name="message" class="form-control" rows="5" required></textarea>
    </div>

    <button type="submit" class="btn btn-dark">@lang('Send Select Email')</button>
</form>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    $('#selectedRecipients').select2({
        placeholder: "@lang('Select Recipients')",
        allowClear: true
    });

    $('#role_id, #county_id').prop('disabled', false);

    $('#group_id').change(function() {
        if ($(this).val()) {
            $('#role_id, #county_id').prop('disabled', true);
            loadUsersByGroup($(this).val());
        } else {
            $('#role_id, #county_id').prop('disabled', false);
            $('#selectedRecipients').empty().select2();
        }
    });

    $('#role_id, #county_id').change(function() {
        if (!$('#group_id').val()) {
            var role_id = $('#role_id').val();
            var county_id = $('#county_id').val();
            loadUsersByFilters(role_id, county_id);
        }
    });

    function loadUsersByGroup(group_id) {
        $.ajax({
            url: "{{ route('emails.filter_users') }}",
            method: "GET",
            data: { group_id: group_id },
            success: function(users) {
                $('#selectedRecipients').empty(); 
                if (users.length > 0) {
                    users.forEach(function(user) {
                        $('#selectedRecipients').append(
                            `<option value="${user.email}">${user.name} (${user.email})</option>`
                        );
                    });
                    $('#selectedRecipients').select2();
                } else {
                    $('#selectedRecipients').append('<option disabled>No users found</option>');
                }
            },
            error: function() {
                console.error("An error occurred while fetching users by group.");
            }
        });
    }

    function loadUsersByFilters(role_id, county_id) {
        $.ajax({
            url: "{{ route('emails.filter_users') }}",
            method: "GET",
            data: {
                role_id: role_id,
                county_id: county_id
            },
            success: function(users) {
                $('#selectedRecipients').empty();
                if (users.length > 0) {
                    users.forEach(function(user) {
                        $('#selectedRecipients').append(
                            `<option value="${user.id}">${user.name} (${user.email})</option>`
                        );
                    });
                    $('#selectedRecipients').select2();
                } else {
                    $('#selectedRecipients').append('<option disabled>No users found</option>');
                }
            },
            error: function() {
                console.error("An error occurred while fetching users by filters.");
            }
        });
    }
});
</script>
