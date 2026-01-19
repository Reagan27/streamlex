@extends('layouts.app')

@section('page-title', __('My Issues'))
@section('page-heading', __('My Issues'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('My Issues')
    </li>
@stop

@section('content')

@include('partials.messages')

<div class="card">
    <div class="card-body">

        <div class="row my-3 flex-md-row flex-column-reverse">
            <div class="col-md-4 mt-md-0 mt-2">
                <div class="input-group custom-search-form">
                    <input type="text"
                           class="form-control input-solid"
                           name="search"
                           value="{{ Request::get('search') }}"
                           placeholder="@lang('Search for issues...')">

                    <span class="input-group-append">
                        @if (Request::has('search') && Request::get('search') != '')
                            <a href="{{ route('support.index') }}"
                               class="btn btn-light d-flex align-items-center text-muted"
                               role="button">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                        <button class="btn btn-light" type="submit" id="search-issues-btn">
                            <i class="fas fa-search text-muted"></i>
                        </button>
                    </span>
                </div>
            </div>

            <div class="col-md-6">
                <a href="{{ route('support.create') }}" class="btn btn-primary btn-rounded float-right">
                    <i class="fas fa-plus mr-2"></i>
                    @lang('Raise Issue')
                </a>
            </div>
        </div>

        <div class="table-responsive" id="issues-table-wrapper">
            <table class="table table-borderless table-striped">
                <thead>
                    <tr>
                        <th class="min-width-80">@lang('Category')</th>
                        <th class="min-width-150">@lang('Subject')</th>
                        <th class="min-width-100">@lang('Priority')</th>
                        <th class="min-width-100">@lang('Status')</th>
                        <th class="text-center min-width-150">@lang('Action')</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($issues))
                        @foreach ($issues as $issue)
                            <tr>
                                <td>{{ $issue->category->name }}</td>
                                <td>{{ $issue->subject }}</td>
                                <td>{{ ucfirst($issue->priority) }}</td>
                                <td>{{ ucfirst($issue->status) }}</td>
                                <td class="text-center">
                                    <a href="{{ route('support.user_show', $issue->id) }}" class="btn btn-icon" title="View Issue">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5"><em>@lang('No issues found.')</em></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>        
    </div>
</div>

<!-- Pagination Links -->
{!! $issues->links() !!}

@stop

@section('scripts')
    <script>
        $("#status").change(function () {
            $("#issues-form").submit();
        });
    </script>
@stop
