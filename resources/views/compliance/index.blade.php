@extends('layouts.app')

@section('page-title', __('Compliance Management'))
@section('page-heading', __('Compliance Management'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">@lang('Compliance')</li>
@stop

@section('content')
    @include('partials.messages')

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">@lang('Total Documents')</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $documents->total() }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">@lang('Expiring Soon')</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $expiringSoon }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">@lang('Expired')</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $expired }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-uppercase mb-1">@lang('Upcoming renewals')</div>
                    <div class="small text-gray-700">
                        @forelse($upcoming as $item)
                            <div>{{ $item->name }} - {{ $item->expiry_date?->format('d M Y') }}</div>
                        @empty
                            <div>@lang('No renewals scheduled')</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row align-items-center mb-3">
                <div class="col-md-6">
                    <form method="GET" action="{{ route('compliance.index') }}" class="form-inline">
                        <input type="text" name="search" class="form-control mr-2" value="{{ request('search') }}" placeholder="Search documents">
                        <select name="category_id" class="form-control mr-2">
                            <option value="">@lang('All categories')</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <select name="status" class="form-control mr-2">
                            <option value="">@lang('All statuses')</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>@lang('Active')</option>
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>@lang('Expired')</option>
                        </select>
                        <button class="btn btn-primary" type="submit">@lang('Filter')</button>
                    </form>
                </div>
                <div class="col-md-6 text-right">
                    <a href="{{ route('compliance.create') }}" class="btn btn-primary">@lang('Add document')</a>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <form method="POST" action="{{ route('compliance.categories.store') }}">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="name" class="form-control" placeholder="New category" required>
                            <textarea name="description" class="form-control" placeholder="Description" rows="1"></textarea>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="submit">@lang('Save category')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>@lang('Name')</th>
                        <th>@lang('Category')</th>
                        <th>@lang('Reference')</th>
                        <th>@lang('Expiry')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($documents as $document)
                        <tr>
                            <td>{{ $document->name }}</td>
                            <td>{{ $document->category?->name }}</td>
                            <td>{{ $document->reference_number }}</td>
                            <td>{{ $document->expiry_date?->format('d M Y') }}</td>
                            <td><span class="badge badge-{{ $document->badge_class }}">{{ ucfirst(str_replace('_', ' ', $document->status_for_display)) }}</span></td>
                            <td>
                                <a href="{{ route('compliance.show', $document) }}" class="btn btn-sm btn-info">@lang('View')</a>
                                <a href="{{ route('compliance.edit', $document) }}" class="btn btn-sm btn-warning">@lang('Edit')</a>
                                <a href="{{ route('compliance.download', $document) }}" class="btn btn-sm btn-success">@lang('Download')</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">@lang('No compliance documents found.')</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            {{ $documents->links() }}
        </div>
    </div>
@stop
