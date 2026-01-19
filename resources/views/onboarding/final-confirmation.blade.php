@extends('layouts.onboarding')

@section('content')
    @include('onboarding.steps', ['currentStep' => 'final-confirmation'])
    
    @include('partials.messages')
    
    <div class="step-content">
        <h3>Final Confirmation</h3>
        <hr>
        
        <div class="alert alert-info">
            <i class="fas fa-info-circle mr-2"></i>
            Please review your information carefully. After confirmation, your onboarding will be complete.
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h4 class="mb-0">Personal Information</h4>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> {{ $user->first_name }} {{ $user->last_name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Phone:</strong> {{ $user->phone }}</p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h4 class="mb-0">Banking Details</h4>
            </div>
            <div class="card-body">
                <p><strong>Bank:</strong> {{ $user->bankDetails->bank->name }}</p>
                <p><strong>Account Name:</strong> {{ $user->bankDetails->account_name }}</p>
                <p><strong>Account Number:</strong> {{ $user->bankDetails->account_number }}</p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h4 class="mb-0">Documents</h4>
            </div>
            <div class="card-body">
                <p><strong>ID Number:</strong> {{ $user->documents->id_number }}</p>
                <p><strong>KRA PIN:</strong> {{ $user->documents->kra_pin }}</p>
            </div>
        </div>

        <div class="mt-5">

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('onboarding.navigate', 'contract') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i> Review Contract
                </a>
                <form action="{{ route('onboarding.complete') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        Complete Onboarding <i class="fas fa-check ml-2"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('styles')
<style>
    .step-content {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }
    .card {
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0,0,0,0.125);
    }
    .alert {
        border-radius: 8px;
    }
    .alert ul {
        padding-left: 20px;
    }
    .btn {
        padding: 10px 20px;
        font-weight: 500;
    }
</style>
@endsection
