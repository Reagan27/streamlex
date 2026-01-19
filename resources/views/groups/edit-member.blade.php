@extends('layouts.app')

@section('page-title', __('Edit Member'))
@section('page-heading', __('Edit Member'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('groups.index') }}">@lang('Groups')</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('groups.show', $group->id) }}">{{ $group->name }}</a>
    </li>
    <li class="breadcrumb-item active">
        @lang('Edit Member')
    </li>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('groups.updateMember', ['group' => $group->id, 'member' => $member->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">@lang('Name')</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $member->name) }}" required>
            </div>

            <div class="form-group">
                <label for="phone">@lang('Phone')</label>
                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $member->phone) }}" required>
            </div>

            <div class="form-group">
                <label for="recipient">@lang('Email')</label>
                <input type="email" name="recipient" id="recipient" class="form-control" value="{{ old('recipient', $member->email) }}" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">@lang('Update Member')</button>
                <a href="{{ route('groups.show', $group->id) }}" class="btn btn-secondary">@lang('Cancel')</a>
            </div>
        </form>
    </div>
</div>
@endsection
