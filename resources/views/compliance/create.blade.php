@extends('layouts.app')

@section('page-title', __('Create Compliance Document'))
@section('page-heading', __('Create Compliance Document'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('compliance.index') }}">@lang('Compliance')</a></li>
    <li class="breadcrumb-item active">@lang('Create')</li>
@stop

@section('content')
    @include('partials.messages')

    <div class="card shadow">
        <div class="card-body">
            <form method="POST" action="{{ route('compliance.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name">@lang('Document name')</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="category_id">@lang('Category')</label>
                    <select name="category_id" id="category_id" class="form-control" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="reference_number">@lang('Reference number')</label>
                        <input type="text" name="reference_number" id="reference_number" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="regulatory_authority">@lang('Regulatory authority')</label>
                        <input type="text" name="regulatory_authority" id="regulatory_authority" class="form-control">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="department">@lang('Department')</label>
                        <input type="text" name="department" id="department" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="responsible_officer">@lang('Responsible officer')</label>
                        <input type="text" name="responsible_officer" id="responsible_officer" class="form-control">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="issue_date">@lang('Issue date')</label>
                        <input type="date" name="issue_date" id="issue_date" class="form-control">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="expiry_date">@lang('Expiry date')</label>
                        <input type="date" name="expiry_date" id="expiry_date" class="form-control">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="renewal_frequency">@lang('Renewal frequency')</label>
                        <input type="text" name="renewal_frequency" id="renewal_frequency" class="form-control" placeholder="annual">
                    </div>
                </div>
                <div class="form-group">
                    <label for="description">@lang('Description')</label>
                    <textarea name="description" id="description" class="form-control" rows="4"></textarea>
                </div>
                <div class="form-group">
                    <label for="document">@lang('Upload document')</label>
                    <input type="file" name="document" id="document" class="form-control-file">
                </div>
                <button type="submit" class="btn btn-primary">@lang('Save')</button>
                <a href="{{ route('compliance.index') }}" class="btn btn-secondary">@lang('Cancel')</a>
            </form>
        </div>
    </div>
@stop
