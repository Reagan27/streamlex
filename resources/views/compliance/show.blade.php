@extends('layouts.app')

@section('page-title', __('Compliance Document'))
@section('page-heading', __('Compliance Document'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('compliance.index') }}">@lang('Compliance')</a></li>
    <li class="breadcrumb-item active">{{ $compliance->name }}</li>
@stop

@section('content')
    @include('partials.messages')

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h4>{{ $compliance->name }}</h4>
                    <p><strong>@lang('Category'):</strong> {{ $compliance->category?->name }}</p>
                    <p><strong>@lang('Reference'):</strong> {{ $compliance->reference_number }}</p>
                    <p><strong>@lang('Expiry'):</strong> {{ $compliance->expiry_date?->format('d M Y') }}</p>
                    <p><strong>@lang('Status'):</strong> <span class="badge badge-{{ $compliance->badge_class }}">{{ ucfirst(str_replace('_', ' ', $compliance->status_for_display)) }}</span></p>
                    <p><strong>@lang('Description'):</strong> {{ $compliance->description }}</p>
                    @if($compliance->document_path)
                        <a href="{{ route('compliance.download', $compliance) }}" class="btn btn-primary">@lang('Download document')</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <h5>@lang('Renewal history')</h5>
            <form method="POST" action="{{ route('compliance.renew', $compliance) }}" enctype="multipart/form-data" class="mb-4">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="renewed_on">@lang('Renewed on')</label>
                        <input type="date" name="renewed_on" id="renewed_on" class="form-control" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="document">@lang('Upload latest document')</label>
                        <input type="file" name="document" id="document" class="form-control-file">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="notes">@lang('Notes')</label>
                        <textarea name="notes" id="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">@lang('Record renewal')</button>
            </form>

            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>@lang('Renewed on')</th>
                    <th>@lang('Notes')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($compliance->renewals as $renewal)
                    <tr>
                        <td>{{ $renewal->renewed_on->format('d M Y') }}</td>
                        <td>{{ $renewal->notes }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">@lang('No renewal history yet.')</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop
