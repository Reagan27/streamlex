@extends('layouts.onboarding')

@section('content')
    @include('onboarding.steps', ['currentStep' => 'welcome'])

    <div class="step-content">
                @if(isset($contract) && isset($skipCountyChecks) && $skipCountyChecks)
                    <div class="alert alert-info">
                        <strong>Note:</strong> You have an <b>individual contract</b> assigned. County-based checks are not required for your onboarding.
                    </div>
                @elseif(isset($contract) && isset($contract->contract_category) && $contract->contract_category === 'group')
                    <div class="alert alert-info">
                        <strong>Note:</strong> You have a <b>group contract</b>. County/project checks will apply during onboarding.
                    </div>
                @endif
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
            <div class="alert alert-danger">
                <h5 class="alert-heading">Cannot Start Onboarding</h5>
                <p><strong>We're sorry, but there is no contract available for your role at this time.</strong></p>
                <hr>
                <p>This could be because:</p>
                <ul>
                    <li>You haven't been assigned to an active project yet</li>
                    <li>Your role doesn't have a published contract in your project</li>
                    <li>Your county is not included in any available contract</li>
                </ul>
                <p class="mb-0"><strong>Please contact the administrator for more information.</strong></p>
            </div>
        @else
            @if($prevContractStatus && in_array($prevContractStatus, ['terminated', 'expired', 'inactive', 'declined']))
                @if(!$user->onboarding_status)
                    <div class="mt-4 d-flex justify-content-end">
                        <a href="{{ route('onboarding.navigate', 'personal-info') }}" class="btn btn-success">
                            Continue Onboarding <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                @endif
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
            @if(!$user->onboarding_status)
                <div class="mt-4 d-flex justify-content-end">
                    <a href="{{ route('onboarding.navigate', 'personal-info') }}" class="btn btn-success">
                        Continue Onboarding <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            @endif
        @endif
        @if(!$prevContractStatus || !in_array($prevContractStatus, ['terminated', 'expired', 'inactive', 'declined']))
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