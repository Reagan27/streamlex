@extends('layouts.onboarding')

@section('content')
    @include('onboarding.steps', ['currentStep' => 'personal-info'])
    @include('partials.messages')
    <form action="{{ route('onboarding.store', 'personal-info') }}" method="POST">
        @csrf
        <div class="step-content">
            <h3>Personal Information</h3>
            <hr>
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" readonly>
            </div>
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" readonly>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
            </div>
            
            <div class="d-flex justify-content-between mt-3">
                <a href="{{ route('onboarding.navigate', 'welcome') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Previous
                </a>
                <button type="submit" class="btn btn-primary">
                    Next <i class="fa fa-arrow-right"></i>
                </button>
            </div>
        </div>
    </form>
@endsection