@extends('layouts.app')
@section('page-title', 'Employee Non-Disclosure Agreement')
@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-body">

            <div class="text-center mb-3">
                <img src="{{ asset('assets/img/Header.png') }}" alt="Header" style="width:100%;max-width:900px;">
            </div>

            <h3 class="mb-4 text-center">EMPLOYEE NON-DISCLOSURE AGREEMENT</h3>
            <p>This EMPLOYEE NON-DISCLOSURE AGREEMENT, hereinafter known as the "Agreement", is entered into between <strong>{{ $user->name ?? '________________' }}</strong> and CPHRM Group Limited collectively known as the "Parties" as of the <strong>{{ $nda_date ?? '_____' }}</strong> day of <strong>{{ $nda_month ?? '_____' }}</strong>, <strong>{{ $nda_year ?? '20__' }}</strong>.</p>
            <h5>Article I: Scope of Agreement</h5>
            <p>This Agreement acknowledges that certain confidential information, trade secrets, and proprietary data (hereinafter defined and referred to as "Confidential Information") of or regarding the Company may be discussed between Employee and the Company. The provisions set forth in this Agreement define the circumstances in which the Employee can and cannot disclose Confidential Information, and include the remedies, penalties and lawful action the Company may take should such information be used or disclosed by Employee. Both Parties agree that it is in their best interests to protect the Company's Confidential Information, and that the terms of this Agreement create a bond of trust and confidentiality between them. In consideration of Employee's commencement of employment, or continued employment with the Company, the Parties agree as follows:</p>
            <h5>Article II: Confidential Information</h5>
            <strong>Definitions</strong>
            <p>Confidential Information is any material, knowledge, information and data (verbal, electronic, written or any other form) concerning the Company or its businesses not generally known to the public consisting of, but not limited to, inventions, discoveries, plans, concepts, designs, blueprints, drawings, models, devices, equipment, apparatus, products, prototypes, formulae, algorithms, techniques, research projects, computer programs, software, firmware, hardware, business, development and marketing plans, merchandising systems, financial and pricing data, information concerning investors, customers, suppliers, Employees and Employees, and any other concepts, ideas or information involving or related to the business which, if misused or disclosed, could adversely affect the Company's business.</p>
            <strong>Exclusions.</strong>
            <ul>
                <li>the information was publicly known;</li>
                <li>the information was received from a third party not subject to the restrictions of this Agreement and becomes available to Employee through no wrongful act or breach of Agreement on their part; or</li>
                <li>the information was approved for release by Employer through written authorization.</li>
            </ul>
            <strong>Period of Confidentiality</strong>
            <p>Employee agrees not to use or disclose Confidential Information for their own personal benefit or the benefit of any other person, corporation or entity other than the Company during the Employee's employment with the company or any time thereafter.</p>
            <strong>Limitations</strong>
            <p>Employee shall limit access to Confidential Information to individuals on a strictly need-to-know basis, involving only those who are carrying out duties related to the Company and its business. Individuals under the Employee's command (affiliates, agents, Employees, representatives and other Employees) are bound by and shall comply with the terms of this Agreement.</p>
            <strong>Ownership</strong>
            <p>All repositories of information containing or in any way relating to Confidential Information is considered property of the Employer. The removal of Confidential Information from the Company's premises is prohibited unless prior written consent is provided by the Company. All such items made, compiled or used by the Employee shall be delivered to the Employer by Employee upon termination of employment or at any other time as per the Employer's request.</p>
            <h5>Article III: Inventions</h5>
            <strong>Prior inventions</strong>
            <p>Any inventions created or conceptualized by the Employee prior to signing the Agreement are excluded from the provisions herein.</p>
            <strong>Ownership of Inventions</strong>
            <p>Inventions constructed while under the Company's employment are the sole property of the Company except those described under subsection (C.) of this section.</p>
            <strong>Personal Inventions</strong>
            <p>Inventions developed by Employee on their own personal time not constructed on Company property, and that were not created using any Company materials, equipment, technology or information, are exempt from the provisions of the Agreement.</p>
            <h5>Article IV: Entire Agreement</h5>
            <strong>Previous Agreements</strong>
            <p>This Agreement constitutes the entire agreement and the signing thereof by both Parties nullifies any and all previous agreements made between Employer and Employee.</p>
            <strong>Modifications and Amendments</strong>
            <p>No modifications, amendments, changes or alterations can be made to the Agreement unless in writing and signed by authorized representatives of both Parties.</p>
            <strong>Successors and Assigns</strong>
            <p>This Agreement shall be binding upon the successors, subsidiaries, assigns and corporations controlling or controlled by the Parties. The Company may assign this Agreement to any party at any time, whereas Employee is prohibited from assigning any of their rights or obligations in the Agreement without prior written consent from Company.</p>
            <h5>Article V: Nature of Relationship</h5>
            <strong>Non-contract</strong>
            <p>The Agreement does not constitute a contract of employment, nor does it guarantee continuing employment for the Employee.</p>
            <strong>Non-partner</strong>
            <p>The Agreement does not create a partnership or joint venture between Company and Employee. Any financial arrangements made between both Parties shall not be included in this Agreement but must be disclosed in a separate document.</p>
            <h5>Article VI: Severability</h5>
            <p>Any provision within the Agreement (or any portion thereof) deemed invalid, unlawful or otherwise unusable by a court of law shall be dissolved from the Agreement and the remainder of the Agreement shall continue to be enforceable. A severed provision shall not alter the integrity of the Agreement, and the terms set forth in any severed provision shall be construed in such a way as to interpret the purpose for which it was drafted.</p>
            <h5>Article VII: Governing Law</h5>
            <p>This Agreement shall be governed in accordance with the laws of Kenya</p>
            <h5>Article VIII: Immunity</h5>
            <p>Disclosing Confidential Information to an attorney, government representative or court official in confidence while assisting or taking part in a case involving a suspected violation of law is not considered a breach of this Agreement. Should the Employee be required to disclose Confidential Information by law, the Employee shall provide Employer with prompt notice of such request.</p>
            <h5>Article IX: Breach of agreement</h5>
            <strong>Cause for Action</strong>
            <p>Employee understands that the use or disclosure of any Confidential Information may be cause for an action at law in an appropriate court in Kenya and that the Employer shall be entitled to an injunction prohibiting the use or disclosure of the Confidential Information.</p>
            <strong>Indemnification</strong>
            <p>Employee understands and agrees that if the use or disclosure of Confidential Information by them or any affiliate, Employee or representative of the Employee causes damage, loss, cost or expense to the Company, the Employee shall be held responsible and shall indemnify the Company.</p>
            <strong>Injunctive Relief</strong>
            <p>The Employee understands and agrees that the use or disclosure of Confidential Information could cause the Company irreparable harm and the Company has the right to pursue legal action beyond remedies of a monetary nature in the form of injunctive or equitable relief. This may be in addition to any other remedy, penalty or claim the law can provide.</p>
            <strong>Notice of Unauthorized Use or Disclosure</strong>
            <p>Employee is bound by this Agreement to notify the Company in the event of a breach of agreement involving the dissemination of Confidential Information, either by the Employee or a third party, and will do everything possible to help the Company regain possession of the Confidential Information.</p>
            <h5>Article X: Prevailing party</h5>
            <p>In a dispute arising out of or in relation to this Agreement, the prevailing party shall have the right to collect from the other party its reasonable attorney fees, costs and necessary expenditures.</p>
            <p class="mt-4"><strong>IN WITNESS WHEREOF</strong>, the Parties hereto agree to the terms of this Agreement and signed on the dates written below.</p>
            <hr>
            <div class="row mt-4">
                <div class="col-md-6">
                    <p>Employee Name: <strong>{{ $user->name ?? '________________' }}</strong></p>
                    <p>Date: <strong>{{ $nda_signed_date ?? '________________' }}</strong></p>
                    <p>Signature:</p>
                    @if(isset($contract_signature) && $contract_signature->signature)
                        <img src="{{ $contract_signature->signature }}" alt="Signature" style="max-width:200px;max-height:80px;">
                    @else
                        <div style="width:200px;height:80px;border:1px solid #ccc;"></div>
                    @endif
                </div>
                <div class="col-md-6 text-right">
                    @if(!$user->nda_accepted)
                        <form method="POST" action="{{ route('nda.accept') }}">
                            @csrf
                            <input type="hidden" name="accept" value="1">
                            <button type="submit" class="btn btn-success">I Agree</button>
                            <a href="{{ route('nda.download') }}" class="btn btn-secondary ml-2">Download PDF</a>
                        </form>
                    @else
                        <a href="{{ route('nda.download') }}" class="btn btn-secondary">Download PDF</a>
                    @endif
                </div>
            </div>

            <!-- <div class="text-center mt-4"> -->
                <!-- <img src="{{ asset('assets/img/Footer.png') }}" alt="Footer" style="width:100%;max-width:900px;"> -->
            <!-- </div> -->

        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if ({{ !$user->nda_accepted ? 'true' : 'false' }}) {
        Swal.fire({
            icon: 'warning',
            title: 'Action Required',
            html: '<b>You must sign the NDA before proceeding.</b>',
            confirmButtonText: 'OK',
            allowOutsideClick: false,
            allowEscapeKey: false
        });
    }
});
</script>
@endpush

@endsection