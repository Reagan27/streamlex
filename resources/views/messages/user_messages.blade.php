@extends('layouts.app')

@section('page-title', __('My Messages'))
@section('page-heading', __('My Messages'))

@section('content')
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th  class="min-width-100">@lang('Date')</th>
                        <th  class="min-width-150">@lang('Message')</th>
                        <!-- <th>@lang('Category')</th>
                        <th>@lang('Type')</th> -->
                        <th>@lang('Actions')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($messages as $message)
                        <tr>
                            <td>{{ $message->created_at->format('Y-m-d H:i') }}</td>
                            <td>{{ Str::limit($message->message, 50) }}</td>
                            <!-- <td>{{ $message->category }}</td>
                            <td>
                                @if($message->user_id == auth()->id())
                                    @lang('Sent')
                                @else
                                    @lang('Received')
                                @endif
                            </td> -->
                            <td>
                                <button type="button" class="btn btn-icon"
                                title="@lang('View Details')" data-bs-toggle="modal" data-bs-target="#messageModal{{ $message->id }}">
                                <i class="fas fa-eye"></i>
                                </button>

                                
                                
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $messages->links() }}
    </div>
</div>

<!-- Message Modals -->
@foreach ($messages as $message)
    <div class="modal fade" id="messageModal{{ $message->id }}" tabindex="-1" aria-labelledby="messageModalLabel{{ $message->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="messageModalLabel{{ $message->id }}">@lang('Message Details')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> &times;</button>
                </div>
                <div class="modal-body">
                    @if($message->user_id == auth()->id())
                        <p><strong>@lang('To'):</strong> {{ $message->recipient }}</p>
                    @else
                        <p><strong>@lang('From'):</strong> {{ $message->user->first_name }} {{ $message->user->last_name }}</p>
                    @endif
                    <p><strong>@lang('Date'):</strong> {{ $message->created_at->format('Y-m-d H:i:s') }}</p>
                    <p><strong>@lang('Category'):</strong> {{ $message->category }}</p>
                    <p><strong>@lang('Message'):</strong></p>
                    <p>{{ $message->message }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Close')</button>
               
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize Bootstrap modals
        var myModals = document.querySelectorAll('.modal');
        myModals.forEach(function(modal) {
            new bootstrap.Modal(modal);
        });
    });
</script>
@endpush