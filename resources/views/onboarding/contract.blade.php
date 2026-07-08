@extends('layouts.onboarding')

@section('styles')
<style>
    .contract-description-container {
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 10px;
        background-color: #f9f9f9;
        height: 400px;
        overflow: hidden;
    }
    .contract-description {
        height: 100%;
        overflow-y: auto;
        padding-right: 10px;
        color: #333;
    }
    .contract-description::-webkit-scrollbar {
        width: 8px;
    }
    .contract-description::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    .contract-description::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }
    .contract-description::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    #signature-pad {
        border: 1px solid #ccc;
        border-radius: 4px;
        margin-bottom: 10px;
    }
</style>
@endsection

@section('content')
    @include('onboarding.steps', ['currentStep' => 'contract'])

    @include('partials.messages')
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if($contract)
    <form action="{{ route('onboarding.store', 'contract') }}" method="POST">
        @csrf
        <div class="step-content">
            <h3>{{ $contract->title }}</h3>
            <hr>
            <div class="form-group">
               <p>This Contract is entered into on <span>{{ \Carbon\Carbon::parse($contract->start_date)->format('F j, Y')}}</span>,
                for a total period of <span>{{ $contract->number_of_days }} days</span>, with an address of
                CPHRM , and with an address of <span>{{ Auth::user()->name }}</span> collectively referred to as the "Parties".</p>
                <div class="contract-description-container">
                    <div id="contract-description" class="contract-description">
                        {!! $contract->description !!}
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="agreed" name="agreed" required>
                                <label class="form-check-label" for="agreed">
                                    I agree to the terms of this contract
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="signature">Signature</label>
                <div id="signature-pad" class="signature-pad">
                    <canvas></canvas>
                </div>
                <input type="hidden" name="signature" id="signature-data">
                <button type="button" class="btn btn-secondary" id="clear-signature">Clear Signature</button>
            </div>
            
            <div class="d-flex justify-content-between mt-3">
                <a href="{{ route('onboarding.navigate', 'policy-agreement') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Previous
                </a>
                <button type="submit" class="btn btn-primary" id="submit-contract">
                    Sign and Complete <i class="fa fa-arrow-right"></i>
                </button>
            </div>
        </div>
    </form>
    @else
        <div class="alert alert-danger">
            <strong>We're sorry, but there is no contract available for your role at this time.</strong>
            <p>Please ensure:</p>
            <ul>
                <li>You are assigned to an active project</li>
                <li>Your role and county match a published contract</li>
                <li>Contact the administrator for more information</li>
            </ul>
        </div>
    @endif
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var canvas = document.querySelector("#signature-pad canvas");
        canvas.width = 400;
        canvas.height = 200;
        var signaturePad = new SignaturePad(canvas);

        document.getElementById('clear-signature').addEventListener('click', function() {
            signaturePad.clear();
        });

        document.getElementById('submit-contract').addEventListener('click', function(e) {
            if (signaturePad.isEmpty()) {
                e.preventDefault();
                alert('Please provide a signature');
            } else {
                var signatureData = signaturePad.toDataURL();
                document.getElementById('signature-data').value = signatureData;
            }
        });

        // Note: The download functionality is commented out as there's no download button in the HTML
        // Uncomment and add a download button if this feature is needed
        /*
        document.getElementById('download-contract').addEventListener('click', function() {
            const { jsPDF } = window.jspdf;
            const contractText = document.getElementById('contract-description').innerText;
            const doc = new jsPDF();
            const lines = doc.splitTextToSize(contractText, 190);
            doc.text(lines, 10, 10);
            doc.save('contract.pdf');
        });
        */
    });
</script>
@endsection