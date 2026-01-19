@if(!$contractSignature || ($contractSignature->status != 'approved' && $contractSignature->status != 'accepted'))
    <div class="card widget text-center custom-card-height mt-5">
        <div class="card-body">
            <h5 class="card-title">Hello, <span>{{ Auth::user()->first_name }}</span></h5>
            
            @if($contractSignature)
                @if($contractSignature->status == 'draft')
                    <p>Your contract is currently awaiting approval.</p>
                @elseif($contractSignature->status == 'declined')
                    <p>Your contract has been declined.</p>
                    @if($contractSignature->decline_reason)
                        <p><strong>Reason:</strong> {{ $contractSignature->decline_reason }}</p>
                    @endif
                    <form action="{{ route('onboarding.restart') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">Restart Onboarding Process</button>
                    </form>
                @elseif($contractSignature->status == 'terminated')
                    <p>Your previous contract has been terminated.</p>
                    @if($contractSignature->termination_reason)
                        <p><strong>Reason:</strong> {{ $contractSignature->termination_reason }}</p>
                    @endif
                    @if($contractSignature->termination_date)
                        <p><strong>Termination Date:</strong> {{ $contractSignature->termination_date->format('d M, Y') }}</p>
                    @endif
                    <form action="{{ route('onboarding.restart') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">Start New Contract Process</button>
                    </form>
                @elseif($contractSignature->status == 'expired')
                    <p>Your previous contract has expired.</p>
                    @if($contractSignature->completion_date)
                        <p><strong>Expiry Date:</strong> {{ $contractSignature->completion_date->format('d M, Y') }}</p>
                    @endif
                    <form action="{{ route('onboarding.restart') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">Start New Contract Process</button>
                    </form>
                @elseif($contractSignature->status == 'inactive')
                    <p>Your previous contract is no longer active.</p>
                    @if($contractSignature->completion_reason)
                        <p><strong>Reason:</strong> {{ $contractSignature->completion_reason }}</p>
                    @endif
                    <form action="{{ route('onboarding.restart') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">Start New Contract Process</button>
                    </form>
                @endif
            @else
                <p>No contract information available.</p>
                <form action="{{ route('onboarding.restart') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">Start Contract Process</button>
                </form>
            @endif
        </div>
    </div>
@else
    <div>
    </div>
@endif


@section('scripts')
@parent
<script>
document.addEventListener('DOMContentLoaded', function() {
    const viewBtn = document.getElementById('view-contract-btn');
    const printBtn = document.getElementById('print-contract');
    const modal = document.getElementById('viewContractModal');

    if (viewBtn && modal) {
        viewBtn.addEventListener('click', function() {
            if (typeof bootstrap !== 'undefined') {
                const contractModal = new bootstrap.Modal(modal);
                contractModal.show();
            } else {
                console.error('Bootstrap is not loaded');
            }
        });
    }

    if (printBtn) {
        printBtn.addEventListener('click', function() {
            const iframe = document.querySelector('#viewContractModal iframe');
            iframe.contentWindow.print();
        });
    }

    if (modal) {
        modal.addEventListener('hidden.bs.modal', function () {
            console.log('Modal is hidden');
        });
    }
});
</script>
@endsection