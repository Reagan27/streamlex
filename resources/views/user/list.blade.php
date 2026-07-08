@extends('layouts.app')

@section('page-title', __('Users'))
@section('page-heading', __('Users'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Users')
    </li>
@stop

@section('content')

@include('partials.messages')

<div class="card">
    <div class="card-body">
        <form action="" method="GET" id="users-form" class="pb-2 mb-3 border-bottom-light">
            <div class="row my-3 flex-md-row flex-column-reverse">
                <div class="col-md-3 mt-md-0 mt-2">
                    <div class="input-group custom-search-form">
                        <input type="text"
                               class="form-control input-solid"
                               name="search"
                               id="search-input"
                               value="{{ Request::get('search') }}"
                               placeholder="@lang('Search name, email or phone...')">

                        <span class="input-group-append">
                            @if (Request::has('search') && Request::get('search') != '')
                                <a href="{{ route('users.index') }}"
                                   class="btn btn-light d-flex align-items-center text-muted"
                                   role="button">
                                    <i class="fas fa-times"></i>
                                </a>
                            @endif
                            <button class="btn btn-light" type="submit" id="search-users-btn">
                                <i class="fas fa-search text-muted"></i>
                            </button>
                        </span>
                    </div>
                </div>

                <div class="col-md-2 mt-2 mt-md-0">
                    <select name="county_id" id="county" class="form-control input-solid">
                        <option value="">@lang('All Counties')</option>
                        @foreach($counties as $county)
                            <option value="{{ $county->id }}" {{ Request::get('county_id') == $county->id ? 'selected' : '' }}>
                                {{ $county->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
     <div class="col-md-2 mt-2 mt-md-0">
    <select name="project_id" id="project" class="form-control input-solid">
        <option value="">@lang('Select Projects')</option>
        @foreach($projects as $project)
            <option value="{{ $project->id }}" {{ Request::get('project_id') == $project->id ? 'selected' : '' }}>
                {{ $project->name }}
            </option>
        @endforeach
    </select>
</div>
                @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Manager'))
                    <div class="col-md-5">
                        <a href="{{ route('users.create') }}" class="btn btn-primary btn-rounded float-right ml-2">
                            <i class="fas fa-plus mr-2"></i>
                            @lang('Add User')
                        </a>
                        <a href="#" class="btn btn-secondary btn-rounded float-right ml-2" data-toggle="modal" data-target="#importModal">
                            <i class="fas fa-file-import mr-2"></i>
                            @lang('Import User')
                        </a>
                    </div>
                @endif
            </div>
        </form>

        <div class="table-responsive" id="users-table-wrapper">
            <table class="table table-borderless table-striped">
            <thead>
            <tr>
                    <th class="min-width-150">@lang('Full Name')</th>
                    <th class="min-width-100">@lang('Email')</th>
                    <th class="min-width-100">@lang('County')</th>
                    <th class="min-width-100">@lang('Roles')</th>
                    <th class="min-width-80">@lang('Status')</th>
                    <th class="text-center min-width-150">@lang('Action')</th>
                </tr>
</thead>
                <tbody>
                    @if (count($users))
                        @foreach ($users as $user)
                            @include('user.partials.row')
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7"><em>@lang('No records found.')</em></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

{!! $users->render() !!}

<!-- Deep Search Modal -->
<div class="modal fade" id="deepSearchModal" tabindex="-1" role="dialog" aria-labelledby="deepSearchModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deepSearchModalLabel">Deep Search</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>The initial search did not find any results. Would you like to perform a deeper search across all records?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirm-deep-search">Perform Deep Search</button>
            </div>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">@lang('Import Users')</h5>
                    <div class="d-flex align-items-center">
                        <a href="{{ route('users.downloadTemplate') }}" class="btn btn-info btn-sm mr-2">
                            @lang('Download Template')
                        </a>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="import_file">@lang('Choose Excel File')</label>
                        <input type="file" class="form-control-file" id="import_file" name="import_file" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-primary">@lang('Import')</button>
                </div>
            </form>
        </div>
    </div>
</div>

@stop

@section('scripts')
<script>
$(document).ready(function() {
    $("#status, #county, #project").change(function () {
        $("#users-form").submit();
    });

    let timer;
    const delay = 500;

    $("#search-input").on('input', function() {
        clearTimeout(timer);
        timer = setTimeout(performSearch, delay);
    });

    function performSearch() {
        const searchTerm = $("#search-input").val();
        const countyId = $("#county").val();
        const status = $("#status").val();

        if (searchTerm.length > 2) {
            $.ajax({
                url: '{{ route("users.search") }}',
                method: 'GET',
                data: { 
                    search: searchTerm,
                    county_id: countyId,
                    status: status
                },
                success: function(response) {
                    if (response.count === 0 && response.total > response.searchedCount) {
                        $('#deepSearchModal').modal('show');
                    } else {
                        // Update the users table with the search results
                        $('#users-table-wrapper').html(response.html);
                    }
                }
            });
        }
    }

    $("#confirm-deep-search").click(function() {
        const searchTerm = $("#search-input").val();
        const countyId = $("#county").val();
        const status = $("#status").val();

        $.ajax({
            url: '{{ route("users.deepSearch") }}',
            method: 'GET',
            data: { 
                search: searchTerm,
                county_id: countyId,
                status: status
            },
            success: function(response) {
                $('#deepSearchModal').modal('hide');
                $('#users-table-wrapper').html(response.html);
            }
        });
    });
});
</script>
@stop
