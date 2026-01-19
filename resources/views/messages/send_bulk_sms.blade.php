@include('partials.messages')

<div class="filters-section mb-4">
    <!-- Combined Filter and Bulk SMS Form -->
    <form method="POST" action="{{ route('messages.send_bulk') }}" id="bulkSmsForm">
        @csrf

        <!-- Filtering Section -->
        <div class="row">
            <!-- All Users Switch -->
            <div class="col-md-3">
                <label>All Users</label>
                <div class="d-flex align-items-center">
                    <div class="switch">
                        <input type="hidden" value="0" name="all_users">
                        <input type="checkbox" name="all_users" id="switch-all-users" class="switch" value="1">
                        <label for="switch-all-users"></label>
                    </div>
                </div>
            </div>

            <!-- Filter by Group -->
            <div class="col-md-3">
                <label for="groupFilter">Group</label>
                <select name="group" id="groupFilter" class="form-control">
                    <option value="">Select a Group</option>
                    @foreach($groups as $group)
                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter by Role -->
            <div class="col-md-3">
                <label for="roleFilter">Role</label>
                <select name="role" id="roleFilter" class="form-control" disabled>
                    <option value="">Select a Role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter by County -->
            <div class="col-md-3">
                <label for="countyFilter">County</label>
                <select name="county" id="countyFilter" class="form-control" disabled>
                    <option value="">Select a County</option>
                    @foreach($counties as $countyId => $countyName)
                        <option value="{{ $countyId }}">{{ $countyName }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Bulk SMS Section -->
        <div class="form-group mt-4">
            <label for="message">Message</label>
            <textarea name="message" id="message" class="form-control" rows="5" required></textarea>
            <div class="d-flex justify-content-between mt-2">
                <small id="charCount">Characters: 0</small>
                <small id="smsCount">SMS: 1</small>
            </div>
        </div>
        <button type="submit" class="btn btn-primary" disabled>Send Bulk SMS</button>
    </form>
</div>

<!-- JavaScript for Form Handling -->
<script>
$(document).ready(function() {
    const maxCharsPerSms = 160;

    function updateCharAndSmsCount() {
        const message = $('#message').val();
        const charCount = message.length;
        const smsCount = Math.ceil(charCount / maxCharsPerSms);

        $('#charCount').text('Characters: ' + charCount);
        $('#smsCount').text('SMS: ' + smsCount);
    }

    $('#message').on('input', function() {
        updateCharAndSmsCount();
    });

    $('#roleFilter, #countyFilter').prop('disabled', false);
    $('#switch-all-users').prop('checked', false);

    $('#switch-all-users').change(function() {
        if ($(this).is(':checked')) {
            $('#groupFilter, #roleFilter, #countyFilter').prop('disabled', true).val('');
            $('#bulkSmsForm button[type="submit"]').prop('disabled', false);
        } else {
            $('#groupFilter, #roleFilter, #countyFilter').prop('disabled', false);
            $('#bulkSmsForm button[type="submit"]').prop('disabled', true);
        }
    });

    $('#groupFilter').change(function() {
        if ($(this).val()) {
            $('#roleFilter, #countyFilter').prop('disabled', true).val('');
        } else {
            $('#roleFilter, #countyFilter').prop('disabled', false);
        }
        $('#bulkSmsForm button[type="submit"]').prop('disabled', !$(this).val());
    });

    $('#roleFilter, #countyFilter').change(function() {
        if (!$('#switch-all-users').is(':checked') && ($('#roleFilter').val() || $('#countyFilter').val())) {
            $('#bulkSmsForm button[type="submit"]').prop('disabled', false);
        } else {
            $('#bulkSmsForm button[type="submit"]').prop('disabled', true);
        }
    });

    $('#bulkSmsForm').on('submit', function(event) {
        var message = $('#message').val();

        if (message.trim() === '') {
            alert('Please enter a message before sending.');
            event.preventDefault();
            return;
        }

        if ($('#switch-all-users').is(':checked')) {
            if (!confirm('Are you sure you want to send bulk SMS to all users excluding those in groups?')) {
                event.preventDefault();
            }
        }
    });
});
</script>
