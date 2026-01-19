@extends('layouts.app')

@section('page-title', $group->name)
@section('page-heading', $group->name)

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('groups.index') }}">@lang('Groups')</a></li>
    <li class="breadcrumb-item active">{{ $group->name }}</li>
@stop

@section('content')
<div class="card mx-auto" style="max-width: 90%;">
    <div class="card-body">
        <a href="{{ route('groups.index') }}" class="btn btn-primary float-right mb-3">
            <i class="fas fa-arrow-left"></i> @lang('Back to Groups')
        </a>

        <h2 class="mb-4">@lang('Group Members')</h2>

        <a href="{{ route('groups.addMember', $group->id) }}" class="btn btn-success mb-3">
            <i class="fas fa-user-plus"></i> @lang('Add Member')
        </a>        

        <form action="{{ route('groups.show', $group->id) }}" method="GET" id="group-filter-form" class="pb-2 mb-3 border-bottom-light">
            <div class="row my-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text"
                               class="form-control"
                               name="search"
                               value="{{ Request::get('search') }}"
                               placeholder="@lang('Search members...')">
                        <span class="input-group-append">
                            @if (Request::has('search') && Request::get('search') != '')
                                <a href="{{ route('groups.show', $group->id) }}"
                                   class="btn btn-light d-flex align-items-center text-muted"
                                   role="button">
                                    <i class="fas fa-times"></i>
                                </a>
                            @endif
                            <button class="btn btn-light" type="submit">
                                <i class="fas fa-search text-muted"></i>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        </form>

        <table class="table table-borderless table-striped">
            <thead>
                <tr>
                    <th>@lang('Name')</th>
                    <th>@lang('Phone')</th>
                    <th>@lang('Email')</th>
                    <th class="text-center">@lang('Actions')</th>
                </tr>
            </thead>
            <tbody>
                @if($members->count())
                    @foreach($members as $member)
                    <tr>
                        <td>{{ $member->name }}</td>
                        <td>{{ $member->phone ?? '-' }}</td>
                        <td>{{ $member->email ?? '-' }}</td>
                        <td class="text-center align-middle">
                            <a href="{{ route('groups.editMember', ['group' => $group->id, 'member' => $member->id]) }}"
                               class="btn btn-icon" title="@lang('Edit Member')" data-toggle="tooltip" data-placement="top">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('groups.destroyMember', ['group' => $group->id, 'member' => $member->id]) }}" method="POST" class="d-inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-icon text-danger" title="@lang('Delete Member')"
                                        onclick="return confirm('@lang('Are you sure you want to delete this member?')')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4" class="text-center">
                            <em>@lang('No members found.')</em>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
        {!! $members->links() !!}
    </div>
</div>
@endsection
