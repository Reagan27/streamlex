@extends('layouts.onboarding')

@section('content')
    @include('onboarding.steps', ['currentStep' => 'welcome'])

    <div class="step-content">
        <h3>Welcome</h3>
        <hr>

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($contract && !$contract->active_for_onboarding)
            <div class="alert alert-warning">
                <h5 class="alert-heading">Contract Currently Inactive</h5>
                <p>The contract for your role is currently not active. Please contact your administrator for more information.</p>
            </div>
        @elseif(!$hasContract)
            <div class="alert alert-warning">
                <p>We're sorry, but there is no contract available for your role at this time. Please contact the administrator for more information.</p>
            </div>
        @else
            @if($prevContractStatus && in_array($prevContractStatus, ['terminated', 'expired', 'inactive', 'declined']))
                <div class="alert {{ $prevContractStatus === 'terminated' ? 'alert-danger' : 'alert-info' }}">
                    <h5 class="alert-heading">Previous Contract Status</h5>
                    @if($prevContractStatus === 'terminated')
                        <p>Your previous contract was terminated. Please contact an administrator to restart the process.</p>
                        <p><strong>Note:</strong> Contract termination requires administrative review before starting a new contract.</p>
                    @elseif($prevContractStatus === 'expired')
                        <p>Your previous contract has expired.</p>
                        <form action="{{ route('onboarding.restart') }}" method="POST" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-primary">Start New Contract Process</button>
                        </form>
                    @elseif($prevContractStatus === 'inactive')
                        <p>Your previous contract is no longer active.</p>
                        <form action="{{ route('onboarding.restart') }}" method="POST" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-primary">Start New Contract Process</button>
                        </form>
                    @elseif($prevContractStatus === 'declined')
                        <p>Your previous contract was declined.</p>
                        @if($contractSignature?->decline_reason)
                            <p><strong>Reason:</strong> {{ $contractSignature->decline_reason }}</p>
                        @endif
                        <form action="{{ route('onboarding.restart') }}" method="POST" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-primary">Start New Contract Process</button>
                        </form>
                    @endif
                </div>
            @else
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Welcome to the Onboarding Process</h5>
                        <p>This process will guide you through the following steps:</p>
                        <ul>
                            <li>Personal Information</li>
                            <li>Bank Details</li>
                            <li>Required Documents</li>
                            <li>Policy Agreement</li>
                            <li>Contract Signing</li>
                        </ul>
                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('onboarding.navigate', 'personal-info') }}" class="btn btn-primary">
                                Get Started <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        @endif
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
    .alert {
        border-radius: 8px;
    }
    ul {
        padding-left: 20px;
    }
    ul li {
        margin-bottom: 10px;
    }
    .btn-primary {
        padding: 10px 20px;
        font-weight: 500;
    }
</style>
@endsection