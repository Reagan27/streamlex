@extends('layouts.app')

@section('page-title', __('Messages'))
@section('page-heading', __('Messages'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Messages')
    </li>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <div class="btn-group" role="group">
                <button class="btn btn-primary" id="sendBulkSmsBtn">@lang('Send Bulk SMS')</button>
                <button class="btn btn-dark" id="sendSelectSmsBtn">@lang('Send Select SMS')</button>
                <button class="btn btn-success" data-toggle="modal" data-target="#importModal">@lang('Import Contacts')</button>
                <a href="{{ route('groups.index') }}" class="btn btn-warning">@lang('Groups')</a>
            </div>
            <div class="ml-auto">
                <a href="{{ route('messages.index') }}" class="btn btn-info">List</a>
            </div>
        </div>

        <div id="smsContent" class="mt-4"></div>

        <div class="table-responsive mt-4">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>@lang('No')</th>
                        <th>@lang('Batch Number')</th>
                        <th>@lang('Sender')</th>
                        <th>@lang('Category')</th>
                        <th>@lang('Sent')</th>
                        <th>@lang('Count')</th>
                        <th>@lang('Created Date')
                        <th>@lang('Actions')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($messageBatches as $messageBatch)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $messageBatch->batchID ?: '-' }}</td> 
                            <td>{{ $messageBatch->user ? $messageBatch->user->name : 'Unknown' }}</td>
                            <td>{{ ucfirst($messageBatch->category) }}</td> 
                            <td>{{ $messageBatch->sent }}</td> 
                            <td>{{ $messageBatch->count }}</td>
                            <td>{{ $messageBatch->created_at->format('Y-m-d H:i:s') }}</td>
                            <td>
                                <a href="{{ route('messages.view_batch', $messageBatch->batchID) }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">
                {{ $messageBatches->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Import Contacts Modal -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center w-100">
                <h5 class="modal-title" id="importModalLabel">@lang('Import Contacts')</h5>
                <a href="{{ route('contacts.download_template') }}" class="btn btn-sm btn-info ml-auto mr-2">
                    @lang('Download Excel Template')
                </a>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('contacts.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="file">@lang('Upload Excel or CSV File')</label>
                        <input type="file" name="file" class="form-control border-0 p-0" style="outline: none;" required>
                    </div>
                    <div class="form-group">
                        <label for="group">@lang('Select Group')</label>
                        <select name="group" class="form-control" id="groupDropdown">
                            <option value="">@lang('Select Group')</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="new_group">@lang('Create Group')</label>
                        <input type="text" name="new_group" class="form-control" id="newGroupInput" placeholder="@lang('Enter Group name')">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-success">@lang('Import')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#newGroupInput').prop('disabled', false);

        $('#groupDropdown').change(function() {
            if ($(this).val()) {
                $('#newGroupInput').prop('disabled', true).val('');
            } else {
                $('#newGroupInput').prop('disabled', false);
            }
        });

        $('#sendBulkSmsBtn').click(function() {
            loadSmsContent('{{ route("messages.send_bulk") }}');
        });

        $('#sendSelectSmsBtn').click(function() {
            loadSmsContent('{{ route("messages.send_select") }}');
        });

        function loadSmsContent(url) {
            $('#smsContent').html('<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>');
            $.get(url, function(data) {
                $('#smsContent').html(data);
            });
        }
    });
</script>
@endsection
