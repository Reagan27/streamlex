@extends('layouts.app')

@section('page-title', __('Edit Template'))
@section('page-heading', __('Edit Template'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Edit Template')
    </li>
@stop

@section('styles')
    @parent
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endsection

@section('content')
@include('partials.messages')
<div class="card">
    <div class="card-body">
        <form action="{{ route('recommendation_certificates.update', $template->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="threshold_status" name="threshold_status" value="1" {{ old('threshold_status', $template->threshold_status) ? 'checked' : '' }}>
                    <label class="custom-control-label" for="threshold_status">Threshold Active</label>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $template->name) }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="type">Type</label>
                        <select class="form-control" id="type" name="type" required>
                            <option value="recommendation" {{ old('type', $template->type) == 'recommendation' ? 'selected' : '' }}>Recommendation</option>
                            <option value="certificate" {{ old('type', $template->type) == 'certificate' ? 'selected' : '' }}>Certificate of Service</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="threshold">Threshold (%)</label>
                        <input type="number" class="form-control" id="threshold" name="threshold" min="0" max="100" value="{{ old('threshold', $template->threshold) }}" {{ old('threshold_status', $template->threshold_status) ? '' : 'disabled' }}>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="content">Template Content</label>
                <textarea class="form-control" id="content" name="content" rows="10" required>{{ old('content', $template->content) }}</textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Update Template</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
    @parent
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#content').summernote({
                height: 300,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });

            $('#threshold_status').change(function() {
                var isChecked = this.checked;
                $(this).next('.custom-control-label').text(isChecked ? 'Threshold Active' : 'Threshold Inactive');
                $('#threshold').prop('disabled', !isChecked);
            });

            // Initialize the threshold input state
            $('#threshold').prop('disabled', !$('#threshold_status').is(':checked'));
        });
    </script>
@endsection
