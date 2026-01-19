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

        <div class="table-responsive mt-4">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>@lang('No')</th>
                        <th>@lang('Recipient')</th>
                        <th>@lang('Email Address')</th>
                        <th>@lang('Message')</th>
                        <!-- <th>@lang('Status')</th> -->
                    </tr>
                </thead>
                <tbody>
                    @foreach($emails as $email)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $email->recipient }}</td>
                            <td>{{ $email->recipient }}</td>
                            <td>{{ $email->message }}</td>
                            <!-- <td>{{ $email->status_description }}</td> -->
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
