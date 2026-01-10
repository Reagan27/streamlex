@extends('layouts.app')

@section('page-title', 'Change Your Password')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Security Check – Change Your Password</h4>
                </div>

                <div class="card-body">
                    <div class="alert alert-warning">
                        <strong>Welcome, {{ auth()->user()->first_name }}!</strong><br>
                        For your security, you must change the initial password set by your administrator before continuing.
                    </div>

                    <form method="POST" action="{{ route('password.force.update') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autofocus>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                Change Password & Continue
                            </button>
                        </div>
                    </form>
                </div>

                <div class="card-footer text-center">
                    <small class="text-muted">
                        After changing your password, you will have full access to FOS.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
