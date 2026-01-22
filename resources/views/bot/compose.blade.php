@extends('layouts.app')

@section('page-title', __('Send Bot Message'))

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="card-title mb-0">
                    <i class="fas fa-robot"></i> Compose Regular Message
                </h5>
                <a href="{{ route('bot.compose', ['mode' => 'rating']) }}" class="btn btn-outline-primary">
                    <i class="fas fa-star"></i> Switch to Rating Mode
                </a>
            </div>
            
            <form id="bot-message-form">
                @csrf
                
                <!-- Filters Section -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <label>All Users</label>
                        <div class="switch">
                            <input type="hidden" value="0" name="all_users">
                            <input type="checkbox" name="all_users" id="switch-all-users" class="switch" value="1">
                            <label for="switch-all-users"></label>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="groupFilter">Group</label>
                        <select name="group_id" id="groupFilter" class="form-control">
                            <option value="">Select a Group</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="role_id">Role</label>
                        <select name="role_id" id="role_id" class="form-control" disabled>
                            <option value="">All Roles</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="county_id">County</label>
                        <select name="county_id" id="county_id" class="form-control" disabled>
                            <option value="">All Counties</option>
                            @foreach($counties as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <span id="recipient-count" class="ml-3"></span>
                    </div>
                </div>

                <!-- Message Template -->
                <div class="form-group">
                    <label>Template (Optional)</label>
                    <select class="form-control" id="template-select">
                        <option value="">-- Select Template --</option>
                        @foreach($templates as $template)
                            <option value="{{ $template->id }}" data-message="{{ $template->message }}">
                                {{ $template->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Message Content -->
                <div class="form-group">
                    <label>Message <span class="text-danger">*</span></label>
                    <textarea name="message" id="message-content" class="form-control" rows="5" maxlength="1000" required></textarea>
                    <div class="d-flex justify-content-between mt-2">
                        <small class="form-text text-muted">
                            <span id="char-count">0</span> / 1000 characters
                        </small>
                        <small class="form-text text-muted">
                            SMS: <span id="sms-count">1</span>
                        </small>
                    </div>
                </div>
               
                <div class="form-group">
                    <label>Attachments (Optional)</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="attachments" name="attachments[]" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                        <label class="custom-file-label" for="attachments">Choose files...</label>
                    </div>
                    <small class="form-text text-muted">
                        Supported formats: PDF, JPG, PNG, DOC, DOCX (Max 5MB per file)
                    </small>
                    <div id="attachment-preview" class="mt-2"></div>
                </div>

                <input type="hidden" name="recipients" id="recipients-data">
                <input type="hidden" name="message_type" value="regular">

                <div class="form-group">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('bot.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-success" id="send-btn" disabled>
                            <i class="fas fa-paper-plane"></i> Send Messages
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    let recipients = [];
    let attachmentFiles = [];
    const maxCharsPerSms = 160;

    // Character count
    function updateCharAndSmsCount() {
        const message = $('#message-content').val();
        const charCount = message.length;
        const smsCount = Math.ceil(charCount / maxCharsPerSms) || 1;
        $('#char-count').text(charCount);
        $('#sms-count').text(smsCount);
    }

    $('#message-content').on('input', updateCharAndSmsCount);

    // Template selection
    $('#template-select').on('change', function() {
        const selectedOption = $(this).find(':selected');
        const message = selectedOption.data('message');
        if (message) {
            $('#message-content').val(message);
            updateCharAndSmsCount();
        }
    });

    // Auto-load recipients when filters change
    function autoLoadRecipients() {
        const filters = {
            group_id: $('#groupFilter').val(),
            role_id: $('#role_id').val(),
            county_id: $('#county_id').val(),
            all_users: $('#switch-all-users').is(':checked') ? 1 : 0
        };

        if (!filters.all_users && !filters.group_id && !filters.role_id && !filters.county_id) {
            $('#recipient-count').html('');
            $('#send-btn').prop('disabled', true);
            return;
        }

        $.ajax({
            url: '{{ route("bot.recipients") }}',
            method: 'POST',
            data: {
                ...filters,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                recipients = response.recipients;
                $('#recipient-count').html(
                    `<span class="badge badge-success">${response.count} recipients loaded</span>`
                );
                $('#recipients-data').val(JSON.stringify(recipients));
                $('#send-btn').prop('disabled', response.count === 0);
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error loading recipients'
                });
            }
        });
    }

    // All users switch
    $('#switch-all-users').change(function() {
        if ($(this).is(':checked')) {
            $('#groupFilter, #role_id, #county_id').prop('disabled', true).val('');
        } else {
            $('#groupFilter').prop('disabled', false);
            $('#role_id, #county_id').prop('disabled', true);
        }
        autoLoadRecipients();
    });

    // Auto-load on filter change
    $('#groupFilter, #role_id, #county_id').change(function() {
        if ($(this).attr('id') === 'groupFilter' && $(this).val()) {
            $('#role_id, #county_id').prop('disabled', true).val('');
        } else if (!$('#groupFilter').val()) {
            $('#role_id, #county_id').prop('disabled', false);
        }
        autoLoadRecipients();
    });

    // Attachment handling
    $('#attachments').on('change', function() {
        const files = Array.from(this.files);
        const maxSize = 5 * 1024 * 1024;
        
        attachmentFiles = [];
        $('#attachment-preview').empty();
        
        files.forEach((file, index) => {
            if (file.size > maxSize) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Too Large',
                    text: `${file.name} exceeds 5MB limit`
                });
                return;
            }
            
            attachmentFiles.push(file);
            
            $('#attachment-preview').append(`
                <div class="badge badge-info mr-2 mb-2" id="file-${index}">
                    <i class="fas fa-file"></i> ${file.name}
                    <button type="button" class="close ml-2" data-index="${index}">
                        <span>&times;</span>
                    </button>
                </div>
            `);
        });
        
        const fileCount = attachmentFiles.length;
        $('.custom-file-label').text(fileCount > 0 ? `${fileCount} file(s) selected` : 'Choose files...');
    });

    // Remove attachment
    $(document).on('click', '#attachment-preview .close', function() {
        const index = $(this).data('index');
        attachmentFiles.splice(index, 1);
        $(this).closest('.badge').remove();
        
        $('#attachments').val('');
        $('.custom-file-label').text(attachmentFiles.length > 0 ? `${attachmentFiles.length} file(s) selected` : 'Choose files...');
    });

    // Form submission
    $('#bot-message-form').on('submit', function(e) {
        e.preventDefault();

        if (recipients.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Recipients',
                text: 'Please select filters to load recipients'
            });
            return;
        }

        const message = $('#message-content').val();
        if (!message) {
            Swal.fire({
                icon: 'warning',
                title: 'No Message',
                text: 'Please enter a message'
            });
            return;
        }

        $('#send-btn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Sending...');

        const formData = new FormData();
        formData.append('message', message);
        formData.append('recipients', JSON.stringify(recipients));
        formData.append('message_type', 'regular');
        formData.append('filters', JSON.stringify({
            role_id: $('#role_id').val(),
            county_id: $('#county_id').val(),
            group_id: $('#groupFilter').val(),
        }));
        formData.append('_token', '{{ csrf_token() }}');
        
        attachmentFiles.forEach((file, index) => {
            formData.append(`attachments[${index}]`, file);
        });

        $.ajax({
            url: '{{ route("bot.send") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: `${response.total_count} messages queued for sending.`,
                    timer: 3000
                }).then(() => {
                    window.location.href = '{{ route("bot.index") }}';
                });
            },
            error: function(xhr) {
                const error = xhr.responseJSON?.message || 'Error sending messages';
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error
                });
                $('#send-btn').prop('disabled', false).html('<i class="fas fa-paper-plane"></i> Send Messages');
            }
        });
    });
});
</script>
@endpush
@endsection