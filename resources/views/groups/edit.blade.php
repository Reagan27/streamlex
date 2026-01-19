@extends('layouts.app')

@section('page-title', __('Edit Group'))
@section('page-heading', __('Edit Group'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('groups.index') }}">@lang('Groups')</a></li>
    <li class="breadcrumb-item active">@lang('Edit Group')</li>
@stop

@section('content')

    @include('partials.messages')

    <div class="card mx-auto" style="max-width: 90%;">
        <div class="card-body">
            <a href="{{ route('groups.index') }}" class="btn btn-primary float-right">
                <i class="fas fa-arrow-left"></i> @lang('Back to Groups')
            </a>

            <h2 class="mb-4">@lang('Edit Group:') {{ $group->name }}</h2>

            <form action="{{ route('groups.update', $group->id) }}" method="POST" class="form-horizontal">
                @csrf
                @method('PUT')

                <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">@lang('Group Name')</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name', $group->name) }}" placeholder="@lang('Enter Group Name')">
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="type" class="col-sm-3 col-form-label">@lang('Group Type')</label>
                    <div class="col-sm-9">
                        <select name="type" id="type" class="form-control @error('type') is-invalid @enderror">
                            <option value="{{ \Vanguard\Group::TYPE_CONTACT }}"
                                {{ old('type', $group->type) == \Vanguard\Group::TYPE_CONTACT ? 'selected' : '' }}>
                                @lang('Contact')
                            </option>
                            <option value="{{ \Vanguard\Group::TYPE_EMAIL }}"
                                {{ old('type', $group->type) == \Vanguard\Group::TYPE_EMAIL ? 'selected' : '' }}>
                                @lang('Email')
                            </option>
                        </select>
                        @error('type')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-9 offset-sm-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> @lang('Save Changes')
                        </button>
                        <a href="{{ route('groups.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> @lang('Cancel')
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

@stop
