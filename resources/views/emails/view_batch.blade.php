@extends('layouts.app')

@section('page-title', __('Batch Emails'))
@section('page-heading', __('Batch Emails'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('emails.index') }}">@lang('Emails')</a>
    </li>
    <li class="breadcrumb-item active">
        @lang('Batch Emails')
    </li>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <a href="{{ route('emails.index') }}" class="btn btn-secondary mb-4">
            @lang('Back')
        </a>

        <div class="form-group mt-4">
            <input type="text" id="emailSearch" class="form-control" placeholder="Search recipient, subject, or message">
        </div>

        <div class="table-responsive mt-4">
            <table class="table table-bordered table-striped" id="emailBatchTable">
                <thead>
                    <tr>
                        <th>@lang('No')</th>
                        <th>@lang('Recipient')</th>
                        <th>@lang('Email Address')</th>
                        <th>@lang('Subject')</th>
                        <th>@lang('Message Preview')</th>
                        <th>@lang('Attachments')</th>
                        <th>@lang('Actions')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($emails as $email)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $email->recipient }}</td>
                            <td>{{ $email->recipient }}</td>
                            <td>{{ $email->subject }}</td>
                            <td>
                                <small class="text-muted">
                                    {!! \Illuminate\Support\Str::limit(strip_tags($email->message), 100) !!}
                                </small>
                            </td>
                            <td>
                                @php
                                   $paths = is_array($email->attachment) ? $email->attachment : (json_decode($email->attachment, true) ?: []);
                                @endphp
                                @if(count($paths))
                                    @foreach($paths as $p)
                                        <a href="{{ Storage::url($p) }}" target="_blank" class="d-block small">{{ basename($p) }}</a>
                                    @endforeach
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('emails.view_single', $email->id) }}" class="btn btn-sm btn-primary" title="@lang('View Full Email')">
                                    <i class="fas fa-eye"></i> @lang('View')
                                </a>
                                <a href="{{ route('emails.download_email', $email->id) }}" class="btn btn-sm btn-secondary ml-2" title="@lang('Download Email')">
                                    <i class="fas fa-download"></i> @lang('Download')
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">
                {{ $emails->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    $('#emailSearch').on('keyup', function () {
        const search = $(this).val().toLowerCase();
        $('#emailBatchTable tbody tr').each(function () {
            const rowText = $(this).text().toLowerCase();
            $(this).toggle(rowText.includes(search));
        });
    });
});
</script>
@endsection
