<div class="steps">
    <ul>
        @php
            $allSteps = ['welcome', 'personal-info', 'banking-details', 'documents', 'policy-agreement', 'contract', 'final-confirmation'];
            $currentStepIndex = array_search($currentStep, $allSteps);
        @endphp

        @foreach($allSteps as $index => $step)
            <li>
                <a class="{{ $currentStep == $step ? 'selected' : '' }} {{ $index < $currentStepIndex ? 'done' : '' }}">
                    <div class="stepNumber"><i class="fa fa-{{ $stepIcons[$step] ?? 'circle' }}"></i></div>
                    <span class="stepDesc text-small">{{ ucfirst(str_replace('-', ' ', $step)) }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</div>