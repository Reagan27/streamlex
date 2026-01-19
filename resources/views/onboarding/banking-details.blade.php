@extends('layouts.onboarding')

@section('content')
    @include('onboarding.steps', ['currentStep' => 'banking-details'])

    @include('partials.messages')
    <form action="{{ route('onboarding.store', 'banking-details') }}" method="POST">
        @csrf
        <div class="step-content">
            <h3>Bank Details</h3>
            <hr>
            <div class="form-group">
                <label for="bank_id">Bank Name</label>
                <select class="form-control" id="bank_id" name="bank_id">
                    <option value="">Select a bank</option>
                    @foreach($banks as $bank)
                        <option value="{{ $bank->id }}" {{ old('bank_id', $user->bankDetails->bank_id ?? '') == $bank->id ? 'selected' : '' }}>{{ $bank->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="bank_branch_code">Bank Branch</label>
                <select class="form-control" id="bank_branch_code" name="bank_branch_code">
                    <option value="">Select a bank first</option>
                </select>
            </div>
            <div class="form-group">
                <label for="account_name">Account Name</label>
                <input type="text" class="form-control" id="account_name" name="account_name" value="{{ old('account_name', $user->bankDetails->account_name ?? '') }}">
            </div>
            <div class="form-group">
                <label for="account_number">Account Number</label>
                <input type="text" class="form-control" id="account_number" name="account_number" value="{{ old('account_number', $user->bankDetails->account_number ?? '') }}">
            </div>
            <div class="form-group">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="confirm_banking" name="confirm_banking" required>
                    <label class="form-check-label" for="confirm_banking">
                        I confirm that this information can be used for transactions
                    </label>
                </div>
            </div>
            
            <div class="d-flex justify-content-between mt-3">
                <a href="{{ route('onboarding.navigate', 'personal-info') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Previous
                </a>
                <button type="submit" class="btn btn-primary">
                    Next <i class="fa fa-arrow-right"></i>
                </button>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var bankSelect = document.getElementById('bank_id');
    var branchSelect = document.getElementById('bank_branch_code');
    var savedBankId = "{{ old('bank_id', $user->bankDetails->bank_id ?? '') }}";
    var savedBranchCode = "{{ old('bank_branch_code', $user->bankDetails->bank_branch ?? '') }}";
    
    function populateBranches(bankId, callback) {
        fetch('{{ route("get-bank-branches") }}?bank_id=' + bankId)
            .then(response => response.json())
            .then(data => {
                branchSelect.innerHTML = '<option value="">Select a branch</option>';
                data.forEach(function(branch) {
                    var option = document.createElement('option');
                    option.value = branch.code;
                    option.textContent = branch.name;
                    branchSelect.appendChild(option);
                });
                if (callback) callback();
            })
            .catch(error => {
                console.error('Fetch Error:', error);
            });
    }
    
    bankSelect.addEventListener('change', function() {
        var bankId = this.value;
        if (bankId) {
            populateBranches(bankId);
        } else {
            branchSelect.innerHTML = '<option value="">Select a bank first</option>';
        }
    });
    
    // If a bank was previously selected, populate branches and select the saved branch
    if (savedBankId) {
        populateBranches(savedBankId, function() {
            if (savedBranchCode) {
                branchSelect.value = savedBranchCode;
            }
        });
    }
});
</script>
@endsection