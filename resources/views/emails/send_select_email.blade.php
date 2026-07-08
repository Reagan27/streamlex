@include('partials.messages')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">

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

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="project_id">@lang('Project')</label>
                        <select name="project_id" id="project_id" class="form-control">
                            <option value="">@lang('Select Project')</option>
                            @foreach($projects as $projectId => $projectName)
                                <option value="{{ $projectId }}">{{ $projectName }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Send Select Email form -->
<form method="POST" action="{{ route('emails.send_select') }}" class="mt-4" enctype="multipart/form-data">
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
        <textarea name="message" id="message" class="form-control" rows="5" required></textarea>
    </div>

    <div class="form-group">
        <label for="attachments">@lang('Attachments (Optional)')</label>
        <input type="file" class="form-control-file" id="attachments" name="attachments[]" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" multiple>
        <small class="form-text text-muted">
            Supported formats: PDF, JPG, PNG, DOC, DOCX (Max 10MB each). You may attach multiple files.
        </small>
        <div id="attachments-list" class="mt-2 text-muted" style="font-size:.95rem">No files selected.</div>
    </div>
    <button type="submit" class="btn btn-dark">@lang('Send Select Email')</button>
</form>

{{--
    NOTE: jQuery, Select2 JS, and Summernote JS are intentionally NOT re-included here.
    They are already loaded once by the main layout. Re-loading jQuery/Select2 on this
    page was clobbering the existing $ object and breaking the script below before it
    ever reached the Summernote initialization, which is why the editor never appeared.
--}}
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

<script>
$(document).ready(function() {

    // Initialize Summernote FIRST so it always runs even if something
    // further down (Select2 / AJAX wiring) throws an error.
    if (typeof $.fn.summernote !== 'undefined') {
        $('#message').summernote({
            height: 220,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview']]
            ]
        });
    } else {
        console.error('Summernote plugin not loaded.');
    }

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

    $('#role_id, #county_id, #project_id').change(function() {
        if (!$('#group_id').val()) {
            var role_id = $('#role_id').val();
            var county_id = $('#county_id').val();
            var project_id = $('#project_id').val();
            loadUsersByFilters(role_id, county_id, project_id);
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
                        let fullName = (user.first_name ? user.first_name : '') + (user.last_name ? ' ' + user.last_name : '');
                        $('#selectedRecipients').append(
                            `<option value="${user.id}">${fullName.trim() || user.email} (${user.email})</option>`
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

    function loadUsersByFilters(role_id, county_id, project_id) {
        $.ajax({
            url: "{{ route('emails.filter_users') }}",
            method: "GET",
            data: {
                role_id: role_id,
                county_id: county_id,
                project_id: project_id
            },
            success: function(users) {
                $('#selectedRecipients').empty();
                if (users.length > 0) {
                    users.forEach(function(user) {
                        let fullName = (user.first_name ? user.first_name : '') + (user.last_name ? ' ' + user.last_name : '');
                        $('#selectedRecipients').append(
                            `<option value="${user.id}">${fullName.trim() || user.email} (${user.email})</option>`
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

    function setupAttachmentManager(inputId, listId) {
        const input = document.getElementById(inputId);
        const list = document.getElementById(listId);
        if (!input || !list || typeof DataTransfer === 'undefined') {
            return;
        }

        let selectedFiles = [];

        function fileKey(file) {
            return [file.name, file.size, file.lastModified].join('|');
        }

        function renderFiles() {
            if (!selectedFiles.length) {
                list.innerHTML = 'No files selected.';
                return;
            }
            list.innerHTML = selectedFiles.map(file => {
                return `<div class="d-flex justify-content-between align-items-center py-1">
                    <span>${file.name}</span>
                    <button type="button" class="btn btn-sm btn-link text-danger remove-file" data-key="${fileKey(file)}">Remove</button>
                </div>`;
            }).join('');
            list.querySelectorAll('.remove-file').forEach(btn => {
                btn.addEventListener('click', function() {
                    const key = this.dataset.key;
                    selectedFiles = selectedFiles.filter(file => fileKey(file) !== key);
                    updateInputFiles();
                    renderFiles();
                });
            });
        }

        function updateInputFiles() {
            const dt = new DataTransfer();
            selectedFiles.forEach(file => dt.items.add(file));
            input.files = dt.files;
        }

        input.addEventListener('change', function() {
            for (const file of Array.from(input.files)) {
                const key = fileKey(file);
                if (!selectedFiles.some(existing => fileKey(existing) === key)) {
                    selectedFiles.push(file);
                }
            }
            updateInputFiles();
            renderFiles();
        });

        renderFiles();
    }

    setupAttachmentManager('attachments', 'attachments-list');
});
</script>