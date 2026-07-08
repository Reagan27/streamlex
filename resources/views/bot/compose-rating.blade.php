@extends('layouts.app')

@section('page-title', __('Send Rating Request'))

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="card-title mb-0">
                    <i class="fas fa-star"></i> Compose Rating Request
                </h5>
                <a href="{{ route('bot.compose') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-envelope"></i> Switch to Regular Message
                </a>
            </div>
            
            <form id="bot-rating-form">
                @csrf
                
                <!-- Rating Configuration -->
                <div class="card border-primary mb-4">
                    <div class="card-header bg-primary text-white">
                        <i class="fas fa-cog"></i> Rating Configuration
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <!-- Rating Type -->
                            <div class="col-md-4">
                                <label class="mb-2 d-block font-weight-bold">Rating Type <span class="text-danger">*</span></label>
                                <div class="d-flex align-items-center">
                                    <span class="text-muted small mr-3" id="rating-type-label">👍 Thumbs Up/Down</span>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="rating-type-toggle">
                                        <label class="custom-control-label" for="rating-type-toggle"></label>
                                    </div>
                                    <span class="text-muted small ml-3">⭐ Scale (1-5)</span>
                                </div>
                                <input type="hidden" name="rating_type" id="rating-type" value="thumbs">
                            </div>

                            <!-- Scale Min/Max (shown only when scale is selected) -->
                            <div class="col-md-4" id="scale-config" style="display: none;">
                                <div class="row">
                                    <div class="col-6">
                                        <label class="mb-1">Min Value</label>
                                        <input type="number" class="form-control form-control-sm" id="scale-min" value="1" min="1" max="5">
                                    </div>
                                    <div class="col-6">
                                        <label class="mb-1">Max Value</label>
                                        <input type="number" class="form-control form-control-sm" id="scale-max" value="5" min="2" max="10">
                                    </div>
                                </div>
                            </div>

                            <!-- Allow Comments -->
                            <div class="col-md-2">
                                <div class="d-flex align-items-center justify-content-between">
                                    <label class="mb-0 mr-2 small">Allow Comments</label>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="allow-comments" checked>
                                        <label class="custom-control-label" for="allow-comments"></label>
                                    </div>
                                </div>
                            </div>

                            <!-- Allow Skip -->
                            <div class="col-md-2">
                                <div class="d-flex align-items-center justify-content-between">
                                    <label class="mb-0 mr-2 small">Allow Skip</label>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="allow-skip" checked>
                                        <label class="custom-control-label" for="allow-skip"></label>
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

                <!-- Rating Question -->
                <div class="form-group">
                    <label>Rating Question <span class="text-danger">*</span></label>
                    <textarea name="message" id="message-content" class="form-control" rows="4" maxlength="500" placeholder="e.g., How satisfied are you with our service?" required></textarea>
                    <div class="d-flex justify-content-between mt-2">
                        <small class="form-text text-muted">
                            <span id="char-count">0</span> / 500 characters
                        </small>
                        <small class="form-text text-info">
                            <i class="fas fa-info-circle"></i> Keep questions clear and concise
                        </small>
                    </div>
                </div>

                <input type="hidden" name="recipients" id="recipients-data">
                <input type="hidden" name="message_type" value="rating">
                <input type="hidden" name="rating_config" id="rating-config-data">

                <div class="form-group">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('bot.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-warning" id="send-btn" disabled>
                            <i class="fas fa-star"></i> Send Rating Request
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
    const maxChars = 500;

    // Rating type toggle
    $('#rating-type-toggle').on('change', function() {
        const isScale = $(this).is(':checked');
        if (isScale) {
            $('#rating-type').val('scale');
            $('#rating-type-label').text('⭐ Scale (1-5)');
            $('#scale-config').show();
        } else {
            $('#rating-type').val('thumbs');
            $('#rating-type-label').text('👍 Thumbs Up/Down');
            $('#scale-config').hide();
        }
    });

    // Character count
    function updateCharCount() {
        const message = $('#message-content').val();
        const charCount = message.length;
        $('#char-count').text(charCount);
    }

    $('#message-content').on('input', updateCharCount);

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

    // Form submission
    $('#bot-rating-form').on('submit', function(e) {
        e.preventDefault();

        if (recipients.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Recipients',
                text: 'Please select filters to load recipients'
            });
            return;
        }

        const message = $('#message-content').val().trim();
        if (!message) {
            Swal.fire({
                icon: 'warning',
                title: 'No Question',
                text: 'Please enter a rating question'
            });
            return;
        }

       
        const ratingType = $('#rating-type').val();
        const ratingConfig = {
            rating_type: ratingType, 
            allow_comment: $('#allow-comments').is(':checked'),
            allow_skip: $('#allow-skip').is(':checked'),
        };

       
        if (ratingType === 'scale') {
            ratingConfig.scale_min = parseInt($('#scale-min').val());
            ratingConfig.scale_max = parseInt($('#scale-max').val());
        }

        $('#rating-config-data').val(JSON.stringify(ratingConfig));

        console.log('Submitting rating config:', ratingConfig);

        $('#send-btn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Sending...');

        const formData = new FormData();
        formData.append('message', message);
        formData.append('recipients', JSON.stringify(recipients));
        formData.append('message_type', 'rating');
        formData.append('rating_config', JSON.stringify(ratingConfig));
        formData.append('filters', JSON.stringify({
            role_id: $('#role_id').val(),
            county_id: $('#county_id').val(),
            group_id: $('#groupFilter').val(),
        }));
        formData.append('_token', '{{ csrf_token() }}');

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
                        <p>${response.total_count} rating requests queued for sending.</p>
                        <p class="text-muted">Recipients will be able to respond with ratings.</p>
                    `,
                    timer: 3000
                }).then(() => {
                    window.location.href = '{{ route("bot.index") }}';
                });
            },
            error: function(xhr) {
                console.error('Error:', xhr.responseJSON);
                const error = xhr.responseJSON?.message || 'Error sending rating requests';
                const errors = xhr.responseJSON?.errors;
                
                let errorHtml = error;
                if (errors) {
                    errorHtml += '<ul class="text-left mt-2">';
                    Object.values(errors).forEach(err => {
                        errorHtml += `<li>${err[0]}</li>`;
                    });
                    errorHtml += '</ul>';
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: errorHtml
                });
                $('#send-btn').prop('disabled', false).html('<i class="fas fa-star"></i> Send Rating Request');
            }
        });
    });
});
</script>
@endpush
@endsection