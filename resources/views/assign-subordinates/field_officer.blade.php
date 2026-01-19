@extends('layouts.app')

@section('page-title', __('Assigned Supervisor'))
@section('page-heading', __('Assign Supervisor'))

@section('content')
<div class="container">
    <!-- <h2>Assigned Supervisor - Field Officer View</h2> -->

    @if($supervisor)
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Your Supervisor</h5>
                <p><strong>Name:</strong> {{ $supervisor->first_name }} {{ $supervisor->last_name }}</p>
                <p><strong>Email:</strong> {{ $supervisor->email }}</p>
                <p><strong>Phone:</strong> {{ $supervisor->phone }}</p>
            </div>
        </div>
    @else
        <p>You have not been assigned to a supervisor yet.</p>
    @endif
</div>
@endsection