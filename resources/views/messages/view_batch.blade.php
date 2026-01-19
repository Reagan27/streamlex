@extends('layouts.app')

@section('page-title', __('Batch Messages'))
@section('page-heading', __('Batch Messages'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('messages.index') }}">@lang('Messages')</a>
    </li>
    <li class="breadcrumb-item active">
        @lang('Batch Messages')
    </li>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <a href="{{ route('messages.index') }}" class="btn btn-secondary mb-4">
            @lang('Back')
        </a>

        <div class="table-responsive mt-4">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>@lang('No')</th>
                        <th>@lang('Recipient')</th>
                        <th>@lang('Phone Number')</th>
                        <th>@lang('Message')</th>
                        <th>@lang('Status')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($messages as $message)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $message->recipient }}</td>
                            <td>{{ $message->phone }}</td> 
                            <td>{{ $message->message }}</td>
                            <td>{{ $message->status_message }}</td> 
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">
                {{ $messages->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
