@extends('layouts.app')

@section('page-title', __('Assigned Field Officers'))
@section('page-heading', __('Assign Field Officers'))

@section('content')
<div class="">

    @if($fieldOfficers->count() > 0)
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
         <table class="table table-striped table-borderless">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fieldOfficers as $fieldOfficer)
                <tr>
                    <td>{{ $fieldOfficer->first_name }} {{ $fieldOfficer->last_name }}</td>
                    <td>{{ $fieldOfficer->email }}</td>
                    <td>{{ $fieldOfficer->phone }}</td>
                    <td>
                        <a href="{{ route('users.edit', $fieldOfficer->id) }}" class="btn btn-sm btn-primary">View Details</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
            </div>
        </div>
    </div>
    @else
        <p>No field officers are currently assigned to you.</p>
    @endif
</div>
@endsection