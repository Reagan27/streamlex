@include('partials.messages')

<div class="card">
    <div class="card-body">
        <h5 class="card-title">@lang('Filters')</h5>
        <form method="POST" action="{{ route('emails.send_bulk') }}" id="bulkEmailForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="MAX_FILE_SIZE" value="10485760"><!-- 10MB max -->

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
                        @foreach($roles as $roleId => $roleName)
                            <option value="{{ $roleId }}">{{ $roleName }}</option>
                        @endforeach
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

                <div class="col-md-3">
                    <label for="projectFilter">@lang('Project')</label>
                    <select name="project_id" id="projectFilter" class="form-control">
                        <option value="">@lang('Select a Project')</option>
                        @foreach($projects as $projectId => $projectName)
                            <option value="{{ $projectId }}">{{ $projectName }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group mt-4">
                <label for="subject">@lang('Subject')</label>
                <input type="text" name="subject" class="form-control" id="subject" required>
            </div>

            <div class="form-group mt-4">
                <label for="message">@lang('Message')</label>
                <textarea name="message" id="message" class="form-control" rows="5" required></textarea>
            </div>

            <div class="form-group mt-4">
                <label for="attachments">@lang('Attachments')</label>
                <input type="file" name="attachments[]" class="form-control-file" id="attachments" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.zip,.rar,.txt" multiple>
                <small class="form-text text-muted">You may attach multiple files (each up to 10MB).</small>
                <div id="attachments-list" class="mt-2 text-muted" style="font-size:.95rem">No files selected.</div>
            </div>

            <button type="submit" class="btn btn-primary" disabled>@lang('Send Bulk Email')</button>
        </form>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
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
    }
});
</script>
