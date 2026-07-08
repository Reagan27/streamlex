@extends('layouts.app')

@section('page-title', __('Policy Acknowledgement'))
@section('page-heading', __('HR Policy & Procedures Manual'))

@section('breadcrumbs')
<li class="breadcrumb-item active">@lang('Policy Acknowledgement')</li>
@stop

@section('styles')
@parent
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=DM+Sans:wght@300;400;500&display=swap');

    :root {
        --brand:       #179970;
        --brand-light: #e8f5f0;  
        --accent:      #c8902a;
        --accent-soft: #fdf4e3;
        --success:     #2d6a4f;
        --success-bg:  #d8f3dc;
        --text:        #1c1c1c;
        --muted:       #6b7280;
        --border:      #dde3ec;
        --white:       #ffffff;
        --shadow:      0 4px 24px rgba(26,60,94,.10);
    }

    body { font-family: 'DM Sans', sans-serif; background: #f4f7fb; color: var(--text); }

    /* ── Hero banner ── */
    .policy-hero {
        background: linear-gradient(135deg, #179970 0%, #0a3d2b 100%);
        color: var(--white);
        padding: 3rem 2.5rem 2rem;
        border-radius: 16px;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    .policy-hero::before {
        content: '';
        position: absolute; inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    .policy-hero h1 {
        font-family: 'Playfair Display', serif;
        font-size: 2rem; font-weight: 700;
        margin-bottom: .4rem; position: relative;
    }
    .policy-hero p { font-size: .95rem; opacity: .8; max-width: 600px; position: relative; margin-bottom: 0; }
    .policy-hero .badge-company {
        display: inline-block;
        background: var(--accent);
        color: var(--white);
        font-size: .7rem; font-weight: 500;
        letter-spacing: .08em;
        text-transform: uppercase;
        padding: .25rem .75rem;
        border-radius: 20px;
        margin-bottom: 1rem;
        position: relative;
    }

    /* ── Progress bar ── */
    .read-progress-wrap {
        background: var(--white);
        border-radius: 12px;
        padding: 1.2rem 1.5rem;
        box-shadow: var(--shadow);
        margin-bottom: 1.5rem;
        display: flex; align-items: center; gap: 1rem;
    }
    .read-progress-wrap .label { font-size: .85rem; color: var(--muted); white-space: nowrap; }
    .read-progress-wrap .bar-outer {
        flex: 1; height: 8px; background: var(--border); border-radius: 99px; overflow: hidden;
    }
    .read-progress-wrap .bar-inner {
        height: 100%; width: 0%; background: linear-gradient(90deg, var(--brand), var(--accent));
        border-radius: 99px; transition: width .5s ease;
    }
    .read-progress-wrap .pct { font-size: .85rem; font-weight: 600; color: var(--brand); min-width: 36px; text-align: right; }

    /* ── Policy reader card ── */
    .policy-reader-card {
        background: var(--white);
        border-radius: 16px;
        box-shadow: var(--shadow);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .policy-reader-header {
        background: var(--brand-light);
        border-bottom: 1px solid var(--border);
        padding: 1rem 1.5rem;
        display: flex; align-items: center; justify-content: space-between;
    }
    .policy-reader-header h5 {
        font-family: 'Playfair Display', serif;
        font-size: 1.05rem; font-weight: 700;
        color: var(--brand); margin: 0;
    }
    .policy-reader-header span { font-size: .8rem; color: var(--muted); }
    .policy-scroll-area {
        height: 420px; overflow-y: scroll;
        padding: 1.8rem 2rem;
        font-size: .9rem; line-height: 1.8;
        color: #2d2d2d;
        scroll-behavior: smooth;
    }
    .policy-scroll-area::-webkit-scrollbar { width: 6px; }
    .policy-scroll-area::-webkit-scrollbar-thumb { background: var(--border); border-radius: 99px; }
    .policy-scroll-area::-webkit-scrollbar-thumb:hover { background: var(--brand); }

    .policy-scroll-area h2 {
        font-family: 'Playfair Display', serif;
        font-size: 1.4rem; color: var(--brand);
        margin: 1.5rem 0 .5rem; border-bottom: 2px solid var(--accent-soft);
        padding-bottom: .5rem;
    }
    .policy-scroll-area h3 {
        font-size: 1rem; font-weight: 600; color: var(--brand);
        margin: 1.2rem 0 .3rem;
    }
    .policy-scroll-area ul { padding-left: 1.3rem; margin-bottom: 1rem; }
    .policy-scroll-area ul li { margin-bottom: .35rem; }
    .policy-scroll-area .policy-section {
        border-left: 3px solid var(--brand-light);
        padding-left: 1rem; margin-bottom: 1.5rem;
    }
    .policy-scroll-area table {
        width: 100%; border-collapse: collapse; margin: 1rem 0;
    }
    .policy-scroll-area table th {
        background: var(--brand); color: var(--white);
        padding: .5rem .75rem; font-size: .8rem; text-align: left;
    }
    .policy-scroll-area table td {
        padding: .5rem .75rem; border-bottom: 1px solid var(--border); font-size: .85rem;
    }
    .policy-scroll-area table td:last-child { text-align: center; }

    /* scroll-to-bottom indicator */
    .scroll-hint {
        text-align: center; padding: .6rem;
        background: var(--accent-soft);
        border-top: 1px solid #f0e0c0;
        font-size: .78rem; color: var(--accent); font-weight: 500;
        transition: opacity .4s;
    }
    .scroll-hint.hidden { opacity: 0; pointer-events: none; }

    /* ── Declaration box ── */
    .declaration-card {
        background: var(--white);
        border-radius: 16px;
        box-shadow: var(--shadow);
        padding: 2rem;
        margin-bottom: 1.5rem;
        border-top: 4px solid var(--accent);
    }
    .declaration-card h5 {
        font-family: 'Playfair Display', serif;
        font-size: 1.1rem; color: var(--brand); margin-bottom: 1rem;
    }
    .declaration-check-wrap {
        display: flex; align-items: flex-start; gap: .9rem;
        background: var(--brand-light); border-radius: 10px;
        padding: 1rem 1.2rem; margin-bottom: 1.2rem;
        transition: background .3s;
    }
    .declaration-check-wrap:hover { background: #d6e6f5; }
    .declaration-check-wrap input[type="checkbox"] {
        width: 20px; height: 20px; flex-shrink: 0;
        accent-color: var(--brand); margin-top: 2px; cursor: pointer;
    }
    .declaration-check-wrap label {
        font-size: .9rem; line-height: 1.6; cursor: pointer; margin: 0;
        color: var(--text);
    }

    /* ── Signature fields ── */
    .sig-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1.2rem; margin-top: 1.2rem; }
    .sig-group label {
        display: block; font-size: .8rem; font-weight: 500;
        color: var(--muted); text-transform: uppercase; letter-spacing: .06em;
        margin-bottom: .4rem;
    }
    .sig-group input {
        width: 100%; border: none; border-bottom: 2px solid var(--border);
        padding: .5rem 0; font-size: .95rem; background: transparent;
        color: var(--text); transition: border-color .2s; outline: none;
    }
    .sig-group input:focus { border-color: var(--brand); }

    /* ── Submit button ── */
    .btn-acknowledge {
        display: inline-flex; align-items: center; gap: .6rem;
        background: linear-gradient(135deg, #179970, #0a3d2b);
        color: var(--white); border: none; border-radius: 10px;
        padding: .9rem 2rem; font-size: .95rem; font-weight: 500;
        cursor: pointer; transition: transform .2s, box-shadow .2s;
        margin-top: 1.5rem;
    }
    .btn-acknowledge:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(26,60,94,.3); color: var(--white); }
    .btn-acknowledge:disabled { opacity: .5; cursor: not-allowed; transform: none; }
    .btn-acknowledge svg { width: 18px; height: 18px; }

    /* ── Already-acknowledged banner ── */
    .already-ack {
        background: var(--success-bg);
        border: 1px solid #95d5b2;
        border-radius: 12px;
        padding: 1.2rem 1.5rem;
        display: flex; align-items: center; gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .already-ack .icon { font-size: 1.6rem; }
    .already-ack p { margin: 0; font-size: .9rem; color: var(--success); }
    .already-ack p strong { display: block; font-size: 1rem; }

    /* locked overlay */
    .locked-overlay {
        pointer-events: none; opacity: .6; filter: grayscale(.4);
    }
</style>
@endsection

@section('content')
@include('partials.messages')

{{-- Download HR Policy PDF --}}
<div class="mb-3 text-end">
    <a href="{{ route('download.hr_policy') }}" class="btn btn-outline-primary" target="_blank">
        <i class="bi bi-download"></i> Download HR Policy PDF
    </a>
</div>

{{-- Hero --}}
<div class="policy-hero">
    <span class="badge-company">CPHRM Group &copy; 2026</span>
    <h1>HR Policy &amp; Procedures Manual</h1>
    <p>Please read the full manual carefully before submitting your acknowledgement. You only need to do this once.</p>
</div>

{{-- Already acknowledged banner --}}
@if(isset($alreadyAcknowledged) && $alreadyAcknowledged)
<div class="already-ack">
    <span class="icon">✅</span>
    <p>
        <strong>You have already acknowledged this policy.</strong>
        Submitted on {{ $acknowledgement->acknowledged_at->format('d M Y, H:i') }}.
        Your signed record has been saved to your personnel file.
    </p>
</div>
@endif

{{-- Progress bar --}}
<div class="read-progress-wrap">
    <span class="label">Reading progress</span>
    <div class="bar-outer"><div class="bar-inner" id="readBar"></div></div>
    <span class="pct" id="readPct">0%</span>
</div>

{{-- Policy Reader --}}
<div class="policy-reader-card">
    <div class="policy-reader-header">
        <h5>📄 CPHRM Group Human Resources Policy and Procedure Manual</h5>
        <span>Scroll to the bottom to unlock acknowledgement</span>
    </div>

    <div class="policy-scroll-area" id="policyScroll">

        <h2>Welcome</h2>
        <p>Congratulations on your appointment and welcome to the team at <strong>CPHRM GROUP LIMITED</strong>! We are excited that you have decided to join us and look forward to a long, happy and successful partnership together.</p>
        <p>Our business is primarily about <em>Transforming delivery of development projects through innovation, value and project management.</em></p>
        <p>This Manual should be read in conjunction with your Contract of Employment. If you have any questions, please contact <strong>hr@selistar.africa</strong> on 0748778 304 or <a href="mailto:hr@selistar.africa">hr@selistar.africa</a>.</p>

        <h2>Our Company History</h2>
        <div class="policy-section">
            <p>The story of Centre for Population for Health Research and Management (CPHRM) started in <strong>2012</strong> to fill a gap in Research, Monitoring and Evaluation Services among development partners in Health.</p>
            <p>Since <strong>2018</strong>, CPHRM re-branded to <strong>CPHRM Group</strong>, expanding into health, agriculture, environment, water &amp; sanitation, and industrialisation.</p>
        </div>
        <h3>Mission</h3>
        <p>To transform the delivery of development and health projects through innovation, value and project management.</p>
        <h3>Vision</h3>
        <p>To collaborate across disciplines to find solutions to Africa's development problems.</p>
        <h3>Values</h3>
        <ul>
            <li>Respected &amp; Trusted Advisors</li>
            <li>Experts &amp; Flexible</li>
            <li>Known for high quality outcomes and growth strategies</li>
            <li>Change catalysts</li>
        </ul>

        <h2>Employment Contract</h2>
        <p>Your employment is governed by your contract of employment, CPHRM's Policies, and this Manual.</p>

        <h2>Payroll</h2>
        <div class="policy-section">
            <p>Your pay cycle is <strong>monthly</strong>. Payments are processed electronically into the bank account details provided. Taxation payments are automatically deducted. Please notify the Finance &amp; HR Manager via email for any banking detail changes.</p>
        </div>

        <h2>Hours of Work</h2>
        <div class="policy-section">
            <p>Office hours are generally <strong>8am – 5pm, Monday to Friday</strong>, with a half day on Saturdays depending on your contract. Employees are allowed a <strong>1-hour lunch break</strong>.</p>
            <p>Overtime is work performed at the direction of the manager beyond contracted hours and is compensated only where stipulated in an employment contract.</p>
        </div>

        <h2>Reimbursement of Expenses</h2>
        <div class="policy-section">
            <p>CPHRM Group will reimburse pre-approved expenses with receipts. Travel will generally be economy class via a carrier chosen by the Group.</p>
        </div>

        <h2>Code of Conduct Policy</h2>
        <div class="policy-section">
            <ul>
                <li>Act and maintain a high standard of integrity and professionalism.</li>
                <li>Be responsible in the proper use of Company information, funds, equipment and facilities.</li>
                <li>Exercise fairness, equality, courtesy and sensitivity in all dealings.</li>
                <li>Under no circumstances may employees offer or accept money.</li>
                <li>Any breach of this policy may result in disciplinary action, including termination.</li>
            </ul>
        </div>

        <h2>Dress Code Policy</h2>
        <div class="policy-section">
            <p>Office employees must dress <strong>business casual</strong>. Every <strong>Friday</strong> all employees will dress in the CPHRM polo T-Shirt. Formal wear is required on client-meeting days.</p>
            <p><strong>Prohibited:</strong> ripped clothing, low-cut clothing, tracksuits, open-toed shoes.</p>
        </div>

        <h2>IT, Internet, Email &amp; Social Media Policies</h2>
        <div class="policy-section">
            <p>Internet and email facilities are provided for <strong>business use</strong>. Limited private use is permitted if it does not interfere with work. Management may access system logs. No defamatory, confidential or discriminatory material may be sent.</p>
            <p>No employee may engage on Social Media as a representative of CPHRM Group without written approval. Breaches may result in dismissal.</p>
        </div>

        <h2>Recruitment Policy</h2>
        <div class="policy-section">
            <p>All appointments are made on the <strong>Principle of Merit</strong>. The process includes: Personnel Requisition → Intake Meeting → Advertisement → Screening → Interviews → Reference Checks → Background Checks → Job Offer. A minimum of <strong>3 professional references</strong> are required.</p>
        </div>

        <h2>Induction Policy</h2>
        <div class="policy-section">
            <p><strong>Phase 1 (Day 1 Paperwork):</strong> Employment Agreement, Confidentiality Agreement, Employee Details Form, Banking Details, Tax Declaration, NSSF, NHIF, National ID copy, Passport photos.</p>
            <p><strong>Phase 2:</strong> Workplace tour, OH&amp;S procedures, business overview, IT orientation, buddy assignment.</p>
        </div>

        <h2>Training &amp; Development Policy</h2>
        <p>CPHRM will provide adequate training. Employees are encouraged to highlight gaps in their skills or knowledge. Safety training takes precedence.</p>

        <h2>Trial / Probationary Period Policy</h2>
        <div class="policy-section">
            <p>A probationary period applies to all new employees. At least one formal appraisal is given four weeks before the end of probation. Outcomes: <em>Confirmation, Termination, or Extension (max one extension).</em></p>
        </div>

        <h2>Occupational Health &amp; Safety Policy</h2>
        <div class="policy-section">
            <p>CPHRM Group will, as far as practicable, provide a safe work environment. All persons responsible for other employees must identify and control safety risks, ensure PPE is used, and report unacceptable risks.</p>
        </div>

        <h2>Manual Handling Policy</h2>
        <p>Risk assessments, lifting equipment provision, safe work procedures, and staff training are required for all hazardous manual tasks. Employees must report any manual handling incidents.</p>

        <h2>Workers' Compensation Policy</h2>
        <p>Any injury at work must be reported to the HR Manager. A Register of Injuries entry is required with full details including time, date, location, witnesses, and nature of injury.</p>

        <h2>Smoking Policy</h2>
        <p>Non-smoking policy. Smoking is not permitted on CPHRM Group property at any time. Smoking breaks must not exceed 10 minutes per day beyond the lunch break. Excessive smoking breaks will be treated as absenteeism.</p>

        <h2>Alcohol &amp; Drugs Policy</h2>
        <p>CPHRM Group has a <strong>zero tolerance</strong> policy on illicit drugs on premises. Attending work under the influence of alcohol will result in performance improvement action or dismissal.</p>

        <h2>Equal Employment Opportunity (EEO) &amp; Anti-Bullying Policy</h2>
        <div class="policy-section">
            <p>Discrimination, sexual harassment and bullying will not be tolerated. Protected characteristics include: Ethnicity, Age, Colour, Sex, Language, Religion, Political opinion, Nationality, Disability, Pregnancy, Mental status, HIV Status.</p>
            <p>Employees must report any such behaviour to their manager. Any employee found to have contravened this policy may be dismissed.</p>
        </div>

        <h2>Pregnancy at Work Policy</h2>
        <p>CPHRM encourages early notification of pregnancy. Reasonable adjustments will be made. Pregnant employees may transfer to a 'safe job' where necessary. Employees may work until the expected birth date subject to a medical certificate in the final 6 weeks.</p>

        <h2>Flexible Working Arrangements Policy</h2>
        <p>Options include flexible rostering, job-sharing, and work from home. Requests must be submitted in writing. CPHRM will respond in writing within <strong>21 days</strong>.</p>

        <h2>Leave Policy</h2>
        <div class="policy-section">
            <ul>
                <li><strong>Annual Leave:</strong> Minimum 21 working days per year. Applications for 3+ consecutive days require 2 weeks' notice.</li>
                <li><strong>Maternity Leave:</strong> 3 months with pay for full-time female employees. Notify HR at least 1 month prior.</li>
                <li><strong>Paternity Leave:</strong> 14 days with pay. Notify supervisor 1 month prior. Proof of marriage and birth notice required.</li>
                <li><strong>Sick Leave:</strong> 7 days full pay, then 7 days half pay per year (after 2 months of service). Medical certificate required.</li>
                <li><strong>Time in Lieu:</strong> Must be approved in advance. Max 24 hours accrued per month. Min 30 minutes overtime to qualify.</li>
                <li><strong>Leave Without Pay:</strong> Management discretion.</li>
            </ul>
        </div>

        <h2>Salary Advance Policy</h2>
        <div class="policy-section">
            <p>Salary advances are discouraged but may be considered for extraordinary circumstances. Conditions: Probation must be completed, no existing company loan, no advance in past 3 months. Amount must not exceed <strong>2/3 of monthly salary</strong>. Repayment is deducted from subsequent pay over a maximum of <strong>3 installments</strong>. No interest or admin fee is charged.</p>
        </div>

        <h2>Performance Management Policy</h2>
        <p>All employees undergo a formal performance review at least <strong>twice a year</strong> with their immediate manager. The process is two-way — employees may also give feedback to management.</p>

        <h2>Performance Improvement Policy</h2>
        <p>If an employee does not meet standards, corrective action including verbal/written warnings, counselling or retraining will be applied. Each employee must understand their responsibilities and be given an opportunity to respond before further action is taken.</p>

        <h2>Gross or Serious Misconduct Policy</h2>
        <p>Summary dismissal is possible for gross or serious misconduct. A thorough investigation, including the employee's response, must precede dismissal. The employee will receive a formal letter of termination.</p>

        <h2>Grievance Complaints Policy</h2>
        <p>All employees have the right to lodge a grievance. Issues should first be resolved informally. If unresolved, a formal written grievance is submitted. Escalation to senior management is available if needed.</p>

        <h2>Conflict of Interest Policy</h2>
        <p>Employees must declare all actual, potential or perceived conflicts of interest to management. Employees must not engage in private business in direct competition with CPHRM Group using knowledge gained during employment.</p>

        <h2>Intellectual Property &amp; Security Policy</h2>
        <p>All intellectual property developed during employment belongs to CPHRM Group. Confidential information must not be disclosed without written consent. Failure to comply may result in dismissal and legal action.</p>

        <h2>Environmental Best Practice Policy</h2>
        <p>CPHRM Group complies with all applicable environmental laws. Annual targets will be set to increase energy and water efficiency and reduce waste.</p>

        {{-- Policy checklist table --}}
        <h2>Policies Covered in This Manual</h2>
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Policy</th>
                    <th>Included ✓</th>
                </tr>
            </thead>
            <tbody>
                @php
                $policies = [
                    'Code of Conduct Policy',
                    'Dress Code Policy',
                    'IT, Email and Internet Policy',
                    'Recruitment & Selection Policy',
                    'Induction Policy',
                    'Training & Development Policy',
                    'Probation Policy',
                    'Occupational Health & Safety Policy',
                    'EEO and Anti-Bullying Policy',
                    'Pregnancy at Work Policy',
                    'Flexible Work Arrangements Policy',
                    'Leave Policy',
                    'Salary Advance Policy',
                    'Performance Management Policy',
                    'Performance Improvement Policy',
                    'Gross & Serious Misconduct Policy',
                    'Grievance and Complaint Policy',
                    'Conflict of Interest Policy',
                    'Intellectual Property & Security Policy',
                    'Environmental Best Practice',
                ];
                @endphp
                @foreach($policies as $i => $policy)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $policy }}</td>
                    <td>✓</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <p style="margin-top:2rem; color: var(--muted); font-size:.85rem; text-align:center;">
            — End of CPHRM Group HR Policy &amp; Procedures Manual —
        </p>

    </div>{{-- end scroll area --}}

    <div class="scroll-hint" id="scrollHint">
        ↓ Scroll down to read the full manual and unlock acknowledgement
    </div>
</div>{{-- end reader card --}}


{{-- Declaration & Acknowledgement --}}
<div class="declaration-card {{ (isset($alreadyAcknowledged) && $alreadyAcknowledged) ? 'locked-overlay' : '' }}" id="declarationSection">
    <h5>Employee Declaration &amp; Acknowledgement</h5>

    <form action="{{ route('policy.acknowledge') }}" method="POST" id="ackForm">
        @csrf

        <div class="declaration-check-wrap">
            <input type="checkbox" id="confirm_read" name="confirm_read" value="1"
                {{ (isset($alreadyAcknowledged) && $alreadyAcknowledged) ? 'checked disabled' : '' }}>
            <label for="confirm_read">
                I confirm that I have <strong>read and understood</strong> the full contents of the CPHRM Group HR Policy &amp; Procedures Manual and all policies listed therein.
            </label>
        </div>

        <div class="declaration-check-wrap">
            <input type="checkbox" id="confirm_comply" name="confirm_comply" value="1"
                {{ (isset($alreadyAcknowledged) && $alreadyAcknowledged) ? 'checked disabled' : '' }}>
            <label for="confirm_comply">
                I agree to <strong>comply with the terms and conditions</strong> of these documents and understand that breach of any policy may lead to disciplinary action, including termination.
            </label>
        </div>

        <div class="declaration-check-wrap">
            <input type="checkbox" id="confirm_accurate" name="confirm_accurate" value="1"
                {{ (isset($alreadyAcknowledged) && $alreadyAcknowledged) ? 'checked disabled' : '' }}>
            <label for="confirm_accurate">
                I understand that this acknowledgement will be <strong>recorded in my personnel file</strong> as confirmation of my awareness of CPHRM Group's policies and procedures.
            </label>
        </div>

        <div class="sig-row">
            <div class="sig-group">
                <label>Full Name</label>
                <input type="text" name="employee_name" id="employee_name"
                    value="{{ isset($alreadyAcknowledged) && $alreadyAcknowledged ? $acknowledgement->employee_name : (Auth::user()->first_name . ' ' . Auth::user()->last_name) }}"
                    {{ (isset($alreadyAcknowledged) && $alreadyAcknowledged) ? 'readonly' : '' }}
                    placeholder="Your full name" required>
            </div>
            <div class="sig-group">
                <label>Date</label>
                <input type="text" name="acknowledged_date" id="acknowledged_date"
                    value="{{ isset($alreadyAcknowledged) && $alreadyAcknowledged ? $acknowledgement->acknowledged_at->format('d M Y') : now()->format('d M Y') }}"
                    readonly>
            </div>
        </div>

        @if(!(isset($alreadyAcknowledged) && $alreadyAcknowledged))
        <button type="submit" class="btn-acknowledge" id="ackBtn" disabled>
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            I Acknowledge – Submit Declaration
        </button>
        <p style="font-size:.78rem; color:var(--muted); margin-top:.6rem;">
            ⚠️ Please scroll through the entire manual and tick all three boxes above to enable submission.
        </p>
        @else
        <p style="margin-top:1rem; font-size:.85rem; color:var(--success); font-weight:500;">
            ✅ Acknowledgement submitted on {{ $acknowledgement->acknowledged_at->format('d M Y \a\t H:i') }}.
        </p>
        @endif
    </form>
</div>

@endsection

@section('scripts')
@parent
<script>
(function () {
    const scroll  = document.getElementById('policyScroll');
    const bar     = document.getElementById('readBar');
    const pct     = document.getElementById('readPct');
    const hint    = document.getElementById('scrollHint');
    const ackBtn  = document.getElementById('ackBtn');
    const checks  = document.querySelectorAll('#ackForm input[type="checkbox"]');

    let hasScrolledToBottom = false;

    // ── Scroll progress ──
    scroll.addEventListener('scroll', function () {
        const scrolled   = scroll.scrollTop;
        const maxScroll  = scroll.scrollHeight - scroll.clientHeight;
        const progress   = Math.min(100, Math.round((scrolled / maxScroll) * 100));

        bar.style.width   = progress + '%';
        pct.textContent   = progress + '%';

        if (progress >= 98) {
            hasScrolledToBottom = true;
            hint.classList.add('hidden');
            tryUnlock();
        }
    });

    // ── Checkbox gating ──
    checks.forEach(function (cb) {
        cb.addEventListener('change', function () {
            // Can only tick if scrolled to bottom
            if (!hasScrolledToBottom) {
                cb.checked = false;
                alert('Please read the full manual before ticking the acknowledgement boxes.');
            }
            tryUnlock();
        });
    });

    function tryUnlock() {
        if (!ackBtn) return;
        const allChecked = Array.from(checks).every(function (c) { return c.checked; });
        ackBtn.disabled  = !(hasScrolledToBottom && allChecked);
    }

    // ── Prevent form submit without all checks ──
    const form = document.getElementById('ackForm');
    if (form) {
        form.addEventListener('submit', function (e) {
            const allChecked = Array.from(checks).every(function (c) { return c.checked; });
            if (!hasScrolledToBottom || !allChecked) {
                e.preventDefault();
                alert('Please read the full manual and tick all acknowledgement boxes before submitting.');
            }
        });
    }
})();
</script>
@endsection
