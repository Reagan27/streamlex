@include('partials.messages')

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Send Single Email</h5>
        <form method="POST" action="{{ route('emails.send_single') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="recipient">Recipient Email</label>
                <input type="email" name="recipient" id="recipient" class="form-control" required>
            </div>

            <div class="form-group mt-3">
                <label for="subject">Subject</label>
                <input type="text" name="subject" id="subject" class="form-control" required>
            </div>

            <div class="form-group mt-3">
                <label for="message">Message</label>
                <textarea name="message" id="message" class="form-control" rows="6" required></textarea>
            </div>

            <div class="form-group mt-3">
                <label for="attachments">Attachments (Optional)</label>
                <input type="file" name="attachments[]" id="attachments" class="form-control-file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.zip,.rar,.txt" multiple>
                <small class="form-text text-muted">You may attach multiple files (each up to 10MB).</small>
                <div id="attachments-list" class="mt-2 text-muted" style="font-size:.95rem">No files selected.</div>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Send Email</button>
        </form>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script>
(function() {
    const input = document.getElementById('attachments');
    const list = document.getElementById('attachments-list');
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
})();
</script>
