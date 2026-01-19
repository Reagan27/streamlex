@extends('layouts.app')

@section('page-title', __('Message Details'))
@section('page-heading', __('Message Details'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('messages.index') }}">@lang('Messages')</a>
    </li>
    <li class="breadcrumb-item active">
        @lang('Message Details')
    </li>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <h3>Message Details</h3>

        <table class="table table-bordered">
            <tr>
                <th>@lang('Recipient')</th>
                <td>{{ $message->recipient }}</td>
            </tr>
            <tr>
                <th>@lang('Message')</th>
                <td>{{ $message->message }}</td>
            </tr>
            <tr>
                <th>@lang('Status')</th>
                <td>{{ $message->status_description }}</td>
            </tr>
            <tr>
                <th>@lang('Created At')</th>
                <td>{{ $message->formatted_created_at }}</td>
            </tr>
        </table>
    </div>
</div>
@endsection
