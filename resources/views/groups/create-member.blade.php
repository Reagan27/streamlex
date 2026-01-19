@extends('layouts.app')

@section('page-title', __('Add Member'))
@section('page-heading', __('Add Member'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('groups.index') }}">@lang('Groups')</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('groups.show', $group->id) }}">{{ $group->name }}</a>
    </li>
    <li class="breadcrumb-item active">
        @lang('Add Member')
    </li>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('groups.storeMember', $group->id) }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">@lang('Name')</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label for="phone">@lang('Phone')</label>
                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}">
            </div>

            <div class="form-group">
                <label for="recipient">@lang('Email')</label>
                <input type="email" name="recipient" id="recipient" class="form-control" value="{{ old('recipient') }}">
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">@lang('Add Member')</button>
                <a href="{{ route('groups.show', $group->id) }}" class="btn btn-secondary">@lang('Cancel')</a>
            </div>
        </form>
    </div>
</div>
@endsection
