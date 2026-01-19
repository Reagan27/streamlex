@extends('layouts.app')

@section('page-title', __('Edit Email'))
@section('page-heading', __('Edit Email'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('groups.index') }}">@lang('Groups')</a></li>
    <li class="breadcrumb-item active">@lang('Edit Email')</li>
@stop

@section('content')
<div class="card mx-auto" style="max-width: 90%;">
    <div class="card-body">
        <div class="text-right mb-3">
            <a href="javascript:history.back()" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> @lang('Back')
            </a>
        </div>

        <h1>@lang('Edit Email')</h1>

        <form action="{{ route('emails.update', $email->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">@lang('Name')</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $email->name) }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="phone">@lang('Phone')</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $email->phone) }}">
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">@lang('Email')</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $email->email) }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="group_id">@lang('Group')</label>
                        <select name="group_id" id="group_id" class="form-control" required>
                            @foreach ($groups as $group)
                                <option value="{{ $group->id }}" {{ $group->id == $email->group_id ? 'selected' : '' }}>
                                    {{ $group->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> @lang('Update')
            </button>
        </form>
    </div>
</div>
@stop
