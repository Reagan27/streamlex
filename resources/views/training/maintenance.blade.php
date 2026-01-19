@extends('layouts.public')

@section('content')
<div class="container">
    <div class="alert alert-warning">
        <h4 class="alert-heading">System Maintenance</h4>
        <p>{{ $message }}</p>
    </div>
</div>
@endsection