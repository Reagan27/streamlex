@extends('layouts.app')

@section('page-title', __('Issues Management'))
@section('page-heading', __('Issues Management'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('All Issues')
    </li>
@stop

@section('content')

@include('partials.messages')

<div class="card">
    <div class="card-body">
        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs" id="issuesTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="all-issues-tab" data-toggle="tab" href="#all-issues" role="tab" aria-controls="all-issues" aria-selected="true">@lang('All Issues')</a>
            </li>
            @if(Auth::user()->hasRole('Admin'))
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="escalated-issues-tab" data-toggle="tab" href="#escalated-issues" role="tab" aria-controls="escalated-issues" aria-selected="false">@lang('Escalated Issues')</a>
                </li>
            @endif
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="issuesTabContent">
            <!-- All Issues Tab -->
            <div class="tab-pane fade show active" id="all-issues" role="tabpanel" aria-labelledby="all-issues-tab">
                <form action="{{ route('support.index') }}" method="GET" id="issues-form" class="pb-2 mb-3 border-bottom-light">
                    <div class="row my-3">
                        <div class="col-md-3">
                            <div class="input-group custom-search-form">
                                <input type="text"
                                       name="search"
                                       class="form-control input-solid"
                                       placeholder="@lang('Search for issues...')"
                                       value="{{ request('search') }}">
                                <span class="input-group-append">
                                    <button class="btn btn-light" type="submit">
                                        <i class="fas fa-search text-muted"></i>
                                    </button>
                                </span>
                            </div>
                        </div>

                        @if ($user->hasRole('Admin') || $user->hasRole('Regional_Coordinator'))
                            <div class="col-md-3">
                                <select name="county" class="form-control input-solid" onchange="$('#issues-form').submit()">
                                    <option value="">@lang('Select County')</option>
                                    @foreach($counties as $id => $name)
                                        <option value="{{ $id }}" {{ request('county') == $id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="col-md-3">
                            <select name="status" class="form-control input-solid" onchange="$('#issues-form').submit()">
                                <option value="">@lang('Select Status')</option>
                                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>@lang('Pending')</option>
                                <option value="Open" {{ request('status') == 'Open' ? 'selected' : '' }}>@lang('Open')</option>
                                <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>@lang('Closed')</option>
                            </select>
                        </div>

                        @if ($user->hasRole('Admin'))
                            <div class="col-md-3 text-right">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCategoryModal">
                                    @lang('Add Category')
                                </button>
                            </div>
                        @endif
                    </div>
                </form>

                <div class="row mb-4">
                    @foreach ([
                        'Total Issues' => $totalIssues,
                        'Pending' => $pendingIssues,
                        'Open' => $openIssues,
                        'Closed' => $resolvedIssues,
                    ] as $label => $count)
                        <div class="col-md-3">
                            <div class="card text-center" style="background-color: #d6d6d6;">
                                <div class="card-body">
                                    <h4 class="card-title">@lang($label)</h4>
                                    <p class="card-text"><strong>{{ $count }}</strong></p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="table-responsive" id="issues-table-wrapper">
                    <table class="table table-borderless table-striped">
                        <thead>
                            <tr>
                                <th class="min-width-80">@lang('Category')</th>
                                <th class="min-width-150">@lang('Subject')</th>
                                <th class="min-width-100">@lang('Priority')</th>
                                <th class="min-width-100">@lang('Status')</th>
                                <th class="min-width-100">@lang('Opened By')</th>
                                <th class="min-width-100">@lang('Role')</th>
                                <th class="min-width-100">@lang('County')</th>
                                <th class="text-center min-width-150">@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($issues))
                                @foreach ($issues as $issue)
                                    <tr>
                                        <td>{{ $issue->category ? ucfirst($issue->category->name) : 'N/A' }}</td>
                                        <td>{{ $issue->subject }}</td>
                                        <td>
                                            @if ($issue->priority === 'high')
                                                <span class="badge badge-danger badge-fixed">@lang('High')</span>
                                            @elseif ($issue->priority === 'medium')
                                                <span class="badge badge-warning badge-fixed">@lang('Medium')</span>
                                            @elseif ($issue->priority === 'low')
                                                <span class="badge badge-success badge-fixed">@lang('Low')</span>
                                            @else
                                                <span class="badge badge-secondary badge-fixed">@lang('N/A')</span>
                                            @endif
                                        </td>
                                        <td>{{ ucfirst($issue->status) }}</td>
                                        <td>{{ $issue->user->name ?? 'N/A' }}</td>
                                        <td>{{ $issue->user->role->name ?? 'N/A' }}</td>
                                        <td>{{ $issue->user->county->name ?? 'N/A' }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('support.manage_show', $issue->id) }}" class="btn btn-icon" title="View Issue">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8"><em>@lang('No issues found.')</em></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                {!! $issues->links() !!}
            </div>

            <!-- Escalated Issues Tab -->
            <div class="tab-pane fade" id="escalated-issues" role="tabpanel" aria-labelledby="escalated-issues-tab">
                <div class="table-responsive" id="escalated-issues-table-wrapper">
                    <table class="table table-borderless table-striped">
                        <thead>
                            <tr>
                                <th class="min-width-80">@lang('Category')</th>
                                <th class="min-width-150">@lang('Subject')</th>
                                <th class="min-width-100">@lang('Priority')</th>
                                <th class="min-width-100">@lang('Status')</th>
                                <th class="min-width-100">@lang('Opened By')</th>
                                <th class="min-width-100">@lang('Role')</th>
                                <th class="min-width-100">@lang('Escalated By')</th>
                                <th class="min-width-100">@lang('Escalated Role')</th>
                                <th class="min-width-100">@lang('County')</th>
                                <th class="text-center min-width-150">@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($escalatedIssues))
                                @foreach ($escalatedIssues as $issue)
                                    <tr>
                                        <td>{{ $issue->category ? ucfirst($issue->category->name) : 'N/A' }}</td>
                                        <td>{{ $issue->subject }}</td>
                                        <td>
                                            @if ($issue->priority === 'high')
                                                <span class="badge badge-danger badge-fixed">@lang('High')</span>
                                            @elseif ($issue->priority === 'medium')
                                                <span class="badge badge-warning badge-fixed">@lang('Medium')</span>
                                            @elseif ($issue->priority === 'low')
                                                <span class="badge badge-success badge-fixed">@lang('Low')</span>
                                            @else
                                                <span class="badge badge-secondary badge-fixed">@lang('N/A')</span>
                                            @endif
                                        </td>
                                        <td>{{ ucfirst($issue->status) }}</td>
                                        <td>{{ $issue->user->name ?? 'N/A' }}</td>
                                        <td>{{ $issue->user->role->name ?? 'N/A' }}</td>
                                        <td>{{ $issue->escalatedByUser->name ?? 'N/A' }}</td>
                                        <td>{{ $issue->escalatedByUser->role->name ?? 'N/A' }}</td>                                        
                                        <td>{{ $issue->user->county->name ?? 'N/A' }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('support.manage_show', $issue->id) }}" class="btn btn-icon" title="View Issue">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="10"><em>@lang('No escalated issues found.')</em></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                {!! $escalatedIssues->links() !!}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryModalLabel">@lang('Add/Edit Categories')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form action="{{ route('support.categories.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="category_name">@lang('New Category Name')</label>
                        <input type="text" name="name" class="form-control" id="category_name" required>
                    </div>
                    <button type="submit" class="btn btn-primary">@lang('Add Category')</button>
                </form>

                <hr>
                <h5>@lang('Existing Categories')</h5>
                <ul class="list-group">
                    @foreach($categories as $category)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <form action="{{ route('support.categories.update', $category->id) }}" method="POST" class="d-inline-flex w-100">
                                @csrf
                                @method('PUT')
                                <input type="text" name="name" class="form-control mr-2" value="{{ $category->name }}" required>
                                <button type="submit" class="btn btn-success btn-sm mr-1">@lang('Save')</button>
                            </form>
                            <form action="{{ route('support.categories.destroy', $category->id) }}" method="POST" class="ml-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">@lang('Delete')</button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

@stop

@section('scripts')
<script>
    $(document).ready(function() {
        const activeTab = localStorage.getItem('activeIssuesTab');
        if (activeTab) {
            const tab = document.querySelector(activeTab);
            if (tab) {
                tab.click();
            }
        }

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            localStorage.setItem('activeIssuesTab', '#' + e.target.id);
        });
    });
</script>
@stop
