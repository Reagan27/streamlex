@extends('layouts.app')

@section('page-title', __('Groups'))
@section('page-heading', __('Groups'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Groups')
    </li>
@stop

@section('content')

@include('partials.messages')

<div class="card mx-auto" style="max-width: 90%;">
    <div class="card-body">

        <form action="" method="GET" id="groups-form" class="pb-2 mb-3 border-bottom-light">
            <div class="row my-3 flex-md-row flex-column-reverse">
                <div class="col-md-4 mt-md-0 mt-2">
                    <div class="input-group custom-search-form">
                        <input type="text"
                               class="form-control input-solid"
                               name="search"
                               id="search-input"
                               value="{{ Request::get('search') }}"
                               placeholder="@lang('Search for groups...')">

                        <span class="input-group-append">
                            @if (Request::has('search') && Request::get('search') != '')
                                <a href="{{ route('groups.index') }}"
                                   class="btn btn-light d-flex align-items-center text-muted"
                                   role="button">
                                    <i class="fas fa-times"></i>
                                </a>
                            @endif
                            <button class="btn btn-light" type="submit" id="search-groups-btn">
                                <i class="fas fa-search text-muted"></i>
                            </button>
                        </span>
                    </div>
                </div>

                <div class="col-md-2 mt-2 mt-md-0">
                    <select name="type" id="type" class="form-control input-solid">
                        <option value="">@lang('All Types')</option>
                        <option value="{{ \Vanguard\Group::TYPE_CONTACT }}" {{ Request::get('type') == \Vanguard\Group::TYPE_CONTACT ? 'selected' : '' }}>
                            @lang('Contact')
                        </option>
                        <option value="{{ \Vanguard\Group::TYPE_EMAIL }}" {{ Request::get('type') == \Vanguard\Group::TYPE_EMAIL ? 'selected' : '' }}>
                            @lang('Email')
                        </option>
                    </select>
                </div>

                @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Manager'))
                    <!-- <div class="col-md-6">
                        <a href="{{ route('groups.create') }}" class="btn btn-primary btn-rounded float-right ml-2">
                            <i class="fas fa-plus mr-2"></i>
                            @lang('Create Group')
                        </a>
                        <a href="#" class="btn btn-secondary btn-rounded float-right ml-2" data-toggle="modal" data-target="#importModal">
                            <i class="fas fa-file-import mr-2"></i>
                            @lang('Import Contacts/Emails')
                        </a>
                    </div> -->
                @endif
            </div>
        </form>

        <div class="table-responsive" id="groups-table-wrapper">
            <table class="table table-borderless table-striped">
                <thead>
                    <tr>
                        <th></th>
                        <th class="min-width-150">@lang('Group Name')</th>
                        <th class="min-width-100">@lang('Type')</th>
                        <th class="text-center min-width-150">@lang('Actions')</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($groups))
                        @foreach ($groups as $group)
                            <tr>
                                <td></td>
                                <td>{{ $group->name }}</td>
                                <td>{{ $group->type == \Vanguard\Group::TYPE_CONTACT ? 'Contact' : 'Email' }}</td>
                                <td class="text-center align-middle">
                                    <a href="{{ route('groups.show', $group->id) }}" class="btn btn-icon" title="@lang('View Group')" data-toggle="tooltip" data-placement="top">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('groups.edit', $group->id) }}" class="btn btn-icon" title="@lang('Edit Group')" data-toggle="tooltip" data-placement="top">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                        @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-icon" title="@lang('Delete Group')" data-toggle="tooltip" data-placement="top" onclick="return confirm('@lang('Are you sure you want to delete this group?')');">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" class="text-center">
                                <em>@lang('No records found.')</em>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>        
    </div>
</div>

{!! $groups->render() !!}

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('emails.import.submit') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">@lang('Import Contacts/Emails')</h5>
                    <div class="d-flex align-items-center">
                        <a href="{{ route('downloadTemplate', 'contacts') }}" class="btn btn-info btn-sm mr-2">
                            @lang('Download Contact Template')
                        </a>
                        <a href="{{ route('downloadTemplate', 'emails') }}" class="btn btn-info btn-sm mr-2">
                            @lang('Download Email Template')
                        </a>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="contact_type">@lang('Select Contact Type')</label>
                        <select name="contact_type" id="contact_type" class="form-control" required>
                            <option value="contacts">@lang('Contacts')</option>
                            <option value="emails">@lang('Emails')</option>
                            <option value="both">@lang('Both')</option>
                        </select>
                    </div>

                    <!-- Group Selection -->
                    <div class="form-group">
                        <label for="group">@lang('Select Existing Group')</label>
                        <select name="group" id="group" class="form-control">
                            <option value="">@lang('Select Group')</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }} ({{ $group->type == \Vanguard\Group::TYPE_CONTACT ? __('Contacts') : __('Emails') }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="new_group">@lang('Or Create New Group')</label>
                        <input type="text" class="form-control" id="new_group" name="new_group" placeholder="@lang('Enter New Group Name')">
                    </div>

                    <!-- Contact File -->
                    <div id="contact_file_container" class="form-group">
                        <label for="contact_file">@lang('Choose Contact Excel File')</label>
                        <input type="file" class="form-control-file" id="contact_file" name="contact_file">
                    </div>

                    <!-- Email File -->
                    <div id="email_file_container" class="form-group" style="display: none;">
                        <label for="email_file">@lang('Choose Email Excel File')</label>
                        <input type="file" class="form-control-file" id="email_file" name="email_file">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-primary">@lang('Import')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('scripts')
    <script>
        document.getElementById('contact_type').addEventListener('change', function () {
            const contactFileContainer = document.getElementById('contact_file_container');
            const emailFileContainer = document.getElementById('email_file_container');
            const selectedType = this.value;

            if (selectedType === 'contacts') {
                contactFileContainer.style.display = 'block';
                emailFileContainer.style.display = 'none';
            } else if (selectedType === 'emails') {
                contactFileContainer.style.display = 'none';
                emailFileContainer.style.display = 'block';
            } else if (selectedType === 'both') {
                contactFileContainer.style.display = 'block';
                emailFileContainer.style.display = 'block';
            }
        });

       $(document).ready(function() {
            $("#type").change(function () {
                $("#groups-form").submit();
            });

            let timer;
            const delay = 500;

            $("#search-input").on('input', function() {
                clearTimeout(timer);
                timer = setTimeout(performSearch, delay);
            });

            function performSearch() {
                const searchTerm = $("#search-input").val();
                
                if (searchTerm.length > 2) {
                    $.ajax({
                        url: '{{ route("groups.search") }}',
                        method: 'GET',
                        data: { search: searchTerm },
                        success: function(response) {
                            const tableBody = $('tbody');
                            tableBody.empty();
                            
                            if (response.groups.length > 0) {
                                response.groups.forEach(group => {
                                    const groupType = group.type === "{{ \Vanguard\Group::TYPE_CONTACT }}" ? 'Contact' : 'Email';
                                    
                                    const newRow = `
                                        <tr>
                                            <td></td>
                                            <td>${group.name}</td>
                                            <td>${groupType}</td>
                                            <td class="text-center">
                                                <a href="/groups/${group.id}" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i> @lang('View')
                                                </a>
                                                <form action="/groups/${group.id}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('@lang('Are you sure you want to delete this group?')')">
                                                        <i class="fas fa-trash"></i> @lang('Delete')
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    `;
                                    tableBody.append(newRow);
                                });
                            } else {
                                const noRecordsRow = `
                                    <tr>
                                        <td colspan="4"><em>@lang('No records found.')</em></td>
                                    </tr>
                                `;
                                tableBody.append(noRecordsRow);
                            }
                        }
                    });
                }
            }
        });
    </script>
@stop
