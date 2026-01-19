@extends('layouts.onboarding')

@section('content')
    @include('onboarding.steps', ['currentStep' => 'policy-agreement'])
    @include('partials.messages')
    <div class="step-content">
        <h3>PSEA Policy Agreement</h3>
        <hr>
        <form action="{{ route('onboarding.store', 'policy-agreement') }}" method="POST">
            @csrf
            <div class="policy-container" style="height: 600px; overflow-y: scroll; border: 1px solid #ccc; padding: 15px; margin-bottom: 20px;">
                <embed src="{{ asset('documents/policy.pdf') }}" type="application/pdf" width="100%" height="550px" />
                <div class="form-group mt-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="policy_agreed" name="policy_agreed" required {{ $user->policy_agreed ? 'checked' : '' }}>
                        <label class="form-check-label" for="policy_agreed">
                            I have read and agree to the policy
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-between mt-3">
                <a href="{{ route('onboarding.navigate', 'documents') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Previous
                </a>
                <button type="submit" class="btn btn-primary" id="submit-btn" {{ $user->policy_agreed ? '' : 'disabled' }}>
                    Next <i class="fa fa-arrow-right"></i>
                </button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkbox = document.getElementById('policy_agreed');
        const submitBtn = document.getElementById('submit-btn');

        checkbox.addEventListener('change', function() {
            submitBtn.disabled = !this.checked;
        });

        // Initially set the button state based on the checkbox
        submitBtn.disabled = !checkbox.checked;
    });
</script>
@endsection