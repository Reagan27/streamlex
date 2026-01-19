@extends('layouts.app')

@section('page-title', __('Raise an Issue'))
@section('page-heading', __('Raise an Issue'))

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <h3>@lang('Issues')</h3>
            <a href="{{ route('support.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> @lang('Back to Support')
            </a>
        </div>

        <form action="{{ route('support.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="category">@lang('Category')</label>
                        <select class="form-control" name="category_id" required>
                            <option value="">@lang('Select Category')</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ ucfirst($category->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="priority">@lang('Priority')</label>
                        <select class="form-control" name="priority" required>
                            <option value="low">@lang('Low')</option>
                            <option value="medium">@lang('Medium')</option>
                            <option value="high">@lang('High')</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="subject">@lang('Subject')</label>
                <input type="text" name="subject" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="content">@lang('Content')</label>
                <textarea name="content" class="form-control" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <label for="file">@lang('Upload a File (PDF/Image)')</label>
                <input type="file" name="attachment" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">@lang('Submit')</button>
        </form>
    </div>
</div>
@stop
