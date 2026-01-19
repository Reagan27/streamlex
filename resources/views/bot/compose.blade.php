@extends('layouts.app')

@section('page-title', __('Send Bot Message'))

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">
                <i class="fas fa-robot"></i> Compose Bot Message
            </h5>
            
            <form id="bot-message-form">
                @csrf
                
                <!-- Message Type Toggle -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="mb-1">Message Type</h6>
                                        <small class="text-muted" id="mode-description">
                                            Regular communication mode
                                        </small>
                                    </div>
                                    <div class="custom-control custom-switch" style="transform: scale(1.5);">
                                        <input type="checkbox" class="custom-control-input" id="rating-mode-toggle">
                                        <label class="custom-control-label" for="rating-mode-toggle">
                                            <span class="badge badge-primary" id="mode-badge">Regular Message</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rating Configuration (Hidden by default) -->
                <div id="rating-config" style="display: none;">
                    <div class="card border-primary mb-4">
                        <div class="card-header bg-primary text-white">
                            <i class="fas fa-star"></i> Rating Configuration
                        </div>
                        <div class="card-body">
                            <!-- All options in one row -->
                            <div class="row align-items-center">
                                <!-- Rating Type -->
                                <div class="col-md-5">
                                    <label class="mb-1 d-block">Rating Type <span class="text-danger">*</span></label>
                                    <div class="d-flex align-items-center">
                                        <span class="text-muted small mr-2" id="rating-type-label">Thumbs</span>
                                        <div class="custom-control custom-switch mx-2">
                                            <input type="checkbox" class="custom-control-input" id="rating-type-toggle">
                                            <label class="custom-control-label" for="rating-type-toggle"></label>
                                        </div>
                                        <span class="text-muted small ml-2">Scale (1-5)</span>
                                    </div>
                                    <input type="hidden" name="rating_type" id="rating-type" value="thumbs">
                                </div>

                                <!-- Vertical Divider -->
                                <div class="col-md-auto px-2">
                                    <div class="vr" style="width: 1px; height: 50px; background-color: #dee2e6;"></div>
                                </div>

                                <!-- Allow Comments -->
                                <div class="col-md-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <label class="mb-0 mr-2">Allow Comments</label>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="allow-comments" checked>
                                            <label class="custom-control-label" for="allow-comments"></label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Vertical Divider -->
                                <div class="col-md-auto px-2">
                                    <div class="vr" style="width: 1px; height: 50px; background-color: #dee2e6;"></div>
                                </div>

                                <!-- Allow Skip -->
                                <div class="col-md-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <label class="mb-0 mr-2">Allow Skip</label>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="allow-skip" checked>
                                            <label class="custom-control-label" for="allow-skip"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

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
                <div class="form-group" id="template-group">
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
               
                <div class="form-group" id="attachment-group">
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
                <input type="hidden" name="message_type" id="message-type" value="regular">
                <input type="hidden" name="rating_config" id="rating-config-data">

                <div class="form-group">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('bot.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-success" id="send-btn" disabled>
                            <i class="fas fa-paper-plane"></i> <span id="send-btn-text">Send Messages</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
/* Compact horizontal rating configuration */
#rating-config .card-body {
    padding: 1.5rem;
}

#rating-config label {
    font-size: 0.9rem;
    font-weight: 500;
    color: #333;
}

#rating-config .custom-switch {
    padding-left: 2.25rem;
}

#rating-config .custom-switch .custom-control-label::before {
    width: 2rem;
    height: 1rem;
}

#rating-config .custom-switch .custom-control-label::after {
    width: 0.875rem;
    height: 0.875rem;
}

#rating-config .custom-control-input:checked ~ .custom-control-label::before {
    background-color: #28a745;
    border-color: #28a745;
}

#rating-type-label {
    font-size: 0.85rem;
}

/* Vertical divider */
.vr {
    display: inline-block;
    align-self: stretch;
    width: 1px;
    min-height: 1em;
    background-color: currentColor;
    opacity: 0.25;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    let recipients = [];
    let attachmentFiles = [];
    const maxCharsPerSms = 160;
    let isRatingMode = false;

    // Rating Mode Toggle
    $('#rating-mode-toggle').on('change', function() {
        isRatingMode = $(this).is(':checked');
        
        if (isRatingMode) {
            // Show rating config
            $('#rating-config').slideDown();
            
            // Hide template and attachments in rating mode
            $('#template-group').hide();
            $('#attachment-group').hide();
            
            // Update UI
            $('#mode-badge').removeClass('badge-primary').addClass('badge-warning').text('Rating Mode');
            $('#mode-description').text('Collect ratings and feedback from recipients');
            $('#send-btn-text').text('Send Rating Request');
            $('#message-type').val('rating');
        } else {
            // Hide rating config
            $('#rating-config').slideUp();
            
            // Show template and attachments in regular mode
            $('#template-group').show();
            $('#attachment-group').show();
            
            // Update UI
            $('#mode-badge').removeClass('badge-warning').addClass('badge-primary').text('Regular Message');
            $('#mode-description').text('Regular communication mode');
            $('#send-btn-text').text('Send Messages');
            $('#message-type').val('regular');
        }
    });

    // Rating type toggle
    $('#rating-type-toggle').on('change', function() {
        const isScale = $(this).is(':checked');
        if (isScale) {
            $('#rating-type').val('scale');
            $('#rating-type-label').text('Scale (1-5)');
        } else {
            $('#rating-type').val('thumbs');
            $('#rating-type-label').text('Thumbs');
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

        // Only load if we have some filter selected
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

        
        let ratingConfig = null;
        if (isRatingMode) {
            ratingConfig = {
                rating_type: $('#rating-type').val(),
                scale_min: 1,
                scale_max: 5,
                allow_comment: $('#allow-comments').is(':checked'),
                allow_skip: $('#allow-skip').is(':checked'),
            };
            $('#rating-config-data').val(JSON.stringify(ratingConfig));
        }

        console.log('🔍 DEBUG: Submitting form', {
            message_type: isRatingMode ? 'rating' : 'regular',
            has_rating_config: !!ratingConfig,
            rating_config: ratingConfig
        });

        $('#send-btn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Sending...');

        const formData = new FormData();
        formData.append('message', message);
        formData.append('recipients', JSON.stringify(recipients));
        formData.append('message_type', isRatingMode ? 'rating' : 'regular');
        
        if (ratingConfig) {
            formData.append('rating_config', JSON.stringify(ratingConfig));
        }
        
        formData.append('filters', JSON.stringify({
            role_id: $('#role_id').val(),
            county_id: $('#county_id').val(),
            group_id: $('#groupFilter').val(),
        }));
        formData.append('_token', '{{ csrf_token() }}');
        
        // Only add attachments in regular mode
        if (!isRatingMode) {
            attachmentFiles.forEach((file, index) => {
                formData.append(`attachments[${index}]`, file);
            });
        }

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
                    html: `
                        <p>${response.total_count} ${isRatingMode ? 'rating requests' : 'messages'} queued for sending.</p>
                        ${isRatingMode ? '<p class="text-muted">Recipients will be able to respond with ratings.</p>' : ''}
                    `,
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
                $('#send-btn').prop('disabled', false).html(`<i class="fas fa-paper-plane"></i> <span>${isRatingMode ? 'Send Rating Request' : 'Send Messages'}</span>`);
            }
        });
    });
});
</script>
@endpush
@endsection