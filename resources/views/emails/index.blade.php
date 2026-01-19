@extends('layouts.app')

@section('page-title', __('Emails'))
@section('page-heading', __('Emails'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Emails')
    </li>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <div class="btn-group" role="group" aria-label="Email Actions">
            <button class="btn btn-primary" id="sendBulkEmailBtn">@lang('Send Bulk Email')</button>
            <button class="btn btn-dark" id="sendSelectEmailBtn">@lang('Send Select Email')</button>
            <button class="btn btn-success" data-toggle="modal" data-target="#importModal">@lang('Import Emails')</button>
            <a href="{{ route('groups.index') }}" class="btn btn-warning">@lang('Groups')</a>
        </div>

        <div id="emailContent" class="mt-4"></div>

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
                        <th>@lang('Created Date')</th>
                        <th>@lang('Actions')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($emailBatches as $batch)
                        <tr>
                            <td>{{ $loop->iteration + $emailBatches->firstItem() - 1 }}</td>
                            <td>{{ $batch->batchId }}</td>
                            <td>{{ $batch->user ? $batch->user->name : 'Unknown' }}</td>
                            <td>{{ ucfirst($batch->category) }}</td>
                            <td>{{ $batch->sent }}</td>
                            <td>{{ $batch->email_count }}</td>
                            <td>{{ $batch->created_at->format('Y-m-d H:i:s') }}</td>
                            <td>
                                <a href="{{ route('emails.view_batch', $batch->batchId) }}" class="btn btn-sm btn-info">@lang('View')</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">
                {{ $emailBatches->links() }}
            </div>
        </div>        
    </div>
</div>

<!-- Import Emails Modal -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center w-100">
                <h5 class="modal-title" id="importModalLabel">@lang('Import Emails')</h5>
                <a href="{{ route('emails.download_template') }}" class="btn btn-sm btn-info ml-auto mr-2">
                    @lang('Download Excel Template')
                </a>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('emails.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="file">@lang('Upload Excel or CSV File')</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="group">@lang('Select Group')</label>
                        <select name="group" id="group" class="form-control">
                            <option value="">@lang('Select Group')</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="new_group">@lang('Or Create New Group')</label>
                        <input type="text" name="new_group" id="new_group" class="form-control" placeholder="@lang('Enter Group name')">
                    </div>
                    <button type="submit" class="btn btn-primary">@lang('Import')</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#sendBulkEmailBtn').click(function() {
            loadEmailContent('{{ route("emails.send_bulk") }}');
        });

        $('#sendSelectEmailBtn').click(function() {
            loadEmailContent('{{ route("emails.send_select") }}');
        });

        function loadEmailContent(url) {
            $('#emailContent').html('<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>');
            $.get(url, function(data) {
                $('#emailContent').html(data);
            }).fail(function() {
                $('#emailContent').html('<div class="alert alert-danger">@lang('Failed to load content. Please try again.')</div>');
            });
        }
    });
</script>
@endsection
