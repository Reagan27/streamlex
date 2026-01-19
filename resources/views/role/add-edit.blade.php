@extends('layouts.app')

@section('page-title', __('Roles'))
@section('page-heading', $edit ? $role->name : __('Create New Role'))

@section('styles')
    @parent
    <style>
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 54px;
            height: 26px;
        }
        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius:24px;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        input:checked + .slider {
            background-color: #28a745;
        }
        input:checked + .slider:before {
            transform: translateX(26px);
        }
    </style>
@endsection

@section('content')

@include('partials.messages')

@if ($edit)
    <form action="{{ route('roles.update', $role) }}" method="POST" id="role-form">
    @method('PUT')
@else
    <form action="{{ route('roles.store') }}" method="POST" id="role-form">
@endif
    @csrf

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <h5 class="card-title">
                        @lang('Role Details')
                    </h5>
                    <p class="text-muted">
                        @lang('A general role information.')
                    </p>
                </div>
                <div class="col-md-9">
                    <div class="form-group">
                        <label for="name">@lang('Name')</label>
                        <input type="text"
                               class="form-control input-solid"
                               id="name"
                               name="name"
                               placeholder="@lang('Role Name')"
                               value="{{ $edit ? $role->name : old('name') }}">
                    </div>
                    <div class="form-group">
                        <label for="display_name">@lang('Display Name')</label>
                        <input type="text"
                               class="form-control input-solid"
                               id="display_name"
                               name="display_name"
                               placeholder="@lang('Display Name')"
                               value="{{ $edit ? $role->display_name : old('display_name') }}">
                    </div>
                    <div class="form-group">
                        <label for="description">@lang('Description')</label>
                        <textarea name="description"
                                  id="description"
                                  class="form-control input-solid">{{ $edit ? $role->description : old('description') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="has_contract">@lang('Has Contract')</label>
                        <div>
                            <label class="toggle-switch">
                                <input type="checkbox"
                                       id="has_contract"
                                       name="has_contract"
                                       value="1"
                                       {{ $edit && $role->has_contract ? 'checked' : '' }}
                                       {{ old('has_contract') ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">
        {{ __($edit ? 'Update Role' : 'Create Role') }}
    </button>
</form>

@stop

@section('scripts')
    @if ($edit)
        {!! JsValidator::formRequest('Vanguard\Http\Requests\Role\UpdateRoleRequest', '#role-form') !!}
    @else
        {!! JsValidator::formRequest('Vanguard\Http\Requests\Role\CreateRoleRequest', '#role-form') !!}
    @endif
@stop