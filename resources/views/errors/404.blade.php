@extends('layouts.app')

@section('title', 'Not Found')
@section('content')
<div class="container mt-5">
    <div class="alert alert-danger text-center">
        <h1>404</h1>
        <p>{{ $message ?? 'The resource you are looking for could not be found.' }}</p>
        <a href="{{ url()->previous() }}" class="btn btn-primary mt-3">Go Back</a>
    </div>
</div>
@endsection
