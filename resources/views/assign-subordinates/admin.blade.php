@extends('layouts.app')

@section('page-title', __('Assign Subordinates'))
@section('page-heading', __('Assign Subordinates'))

@section('content')
<div class="">
    <!-- <h2>Assign Subordinates - Admin View</h2> -->

    @if(isset($counties) && is_array($counties) && count($counties) > 0)
        <form action="{{ route('assign-subordinates.index') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <select name="county" class="form-control">
                        <option value="">All Counties</option>
                        @foreach($counties as $countyId => $countyName)
                            <option value="{{ $countyId }}" {{ request('county') == $countyId ? 'selected' : '' }}>
                                {{ $countyName }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="role" class="form-control">
                        <option value="">All Roles</option>
                        @foreach($roles as $roleId => $roleName)
                            <option value="{{ $roleId }}" {{ request('role') == $roleId ? 'selected' : '' }}>
                                {{ $roleName }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>

        @if(isset($users) && $users->count() > 0)
        <div class="card">
        <div class="card-body">
            <div class="table-responsive">
         <table class="table table-striped table-borderless">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Role</th>
                        <th>County/Counties</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                        <td>{{ $user->role->name ?? 'N/A' }}</td>
                        <td>
                            <x-user-counties :user="$user" />
                        </td>
                        <td>
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary">Assign</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
        </div>

            {{ $users->links() }}
        @else
            <p>No users found.</p>
        @endif
    @else
        <p>No counties found or $counties variable is not set correctly.</p>
    @endif


</div>
@endsection