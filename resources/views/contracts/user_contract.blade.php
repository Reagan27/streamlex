@extends('layouts.app')

@section('page-title', __('Your Contract'))
@section('page-heading', __('Your Contract'))

@section('content')
<div class="card widget text-center custom-card-height mt-5">
    <div class="card-body">
        <h5 class="card-title">Hello, <span>{{ Auth::user()->first_name }}</span></h5>

        @if($contractSignature)
            @if($contractSignature->status == 'approved')
                <p>Your contract has been approved and is awaiting final acceptance.</p>
            @elseif($contractSignature->status == 'accepted')
                <p>Your contract has been accepted.</p>
                <div class="d-flex justify-content-center align-items-center mt-3">
                    <button type="button" class="btn btn-primary btn-sm" id="view-contract-btn" data-bs-toggle="modal" data-bs-target="#viewContractModal">
                        <i class="fa fa-eye"></i> View Contract
                    </button>
                </div>
            @elseif($contractSignature->status == 'draft')
                <p>Your contract is currently awaiting approval.</p>
            @elseif($contractSignature->status == 'declined')
                <p>Your contract has been declined.</p>
                @if($contractSignature->decline_reason)
                    <p><strong>Reason:</strong> {{ $contractSignature->decline_reason }}</p>
                @endif
            @endif
        @else
            <p>No contract information available.</p>
        @endif
    </div>
</div>

<!-- Modal for viewing contract -->
@if($contractSignature && $contractSignature->status == 'accepted')
<div class="modal fade" id="viewContractModal" tabindex="-1" aria-labelledby="viewContractModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewContractModalLabel">View Your Contract</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <iframe src="{{ route('user.contract.view') }}" style="width: 100%; height: 500px;"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-sm" id="print-contract">Print Contract</button>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const viewBtn = document.getElementById('view-contract-btn');
    const printBtn = document.getElementById('print-contract');
    const modal = document.getElementById('viewContractModal');

    let bootstrapModal;

    if (viewBtn && modal) {
        viewBtn.addEventListener('click', function() {
            bootstrapModal = new bootstrap.Modal(modal);
            bootstrapModal.show();
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
            if (bootstrapModal) {
                bootstrapModal.dispose();
            }
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('padding-right');
            document.body.style.removeProperty('overflow');
        });
    }
});
</script>
@endsection