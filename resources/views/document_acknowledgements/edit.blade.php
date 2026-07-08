@extends('layouts.app')

@section('page-title', __('Edit Document Acknowledgement'))
@section('page-heading', __('Edit Document Acknowledgement'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('compliance.document_acknowledgements.index') }}">@lang('Document Acknowledgements')</a></li>
    <li class="breadcrumb-item active">@lang('Edit')</li>
@stop

@section('content')
    @include('partials.messages')

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('compliance.document_acknowledgements.update', $document) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="title">@lang('Title')</label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $document->title) }}" required>
                </div>

                <div class="form-group">
                    <label for="description">@lang('Description')</label>
                    <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $document->description) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="content">@lang('Document content')</label>
                    <textarea name="content" id="content" class="form-control summernote">{{ old('content', $document->content) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="original_file">@lang('Replace PDF (optional)')</label>
                    <input type="file" name="original_file" id="original_file" class="form-control-file">
                    @if($document->original_file_name)
                        <p class="mt-2">@lang('Current file'): {{ $document->original_file_name }}</p>
                    @endif
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="signature_page">@lang('Signature Page')</label>
                            <input type="number" name="signature_page" id="signature_page" class="form-control" step="1" min="1" value="{{ old('signature_page', $document->signature_page) }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="signature_x">@lang('Signature X')</label>
                            <input type="number" name="signature_x" id="signature_x" class="form-control" step="0.1" value="{{ old('signature_x', $document->signature_x) }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="signature_y">@lang('Signature Y')</label>
                            <input type="number" name="signature_y" id="signature_y" class="form-control" step="0.1" value="{{ old('signature_y', $document->signature_y) }}" required>
                        </div>
                    </div>
                </div>

                <button class="btn btn-primary" type="submit">@lang('Update document')</button>
            </form>
        </div>
    </div>
@stop

@section('scripts')
    <script>
        $(function () {
            $('.summernote').summernote({
                height: 250,
            });
        });
    </script>
@stop