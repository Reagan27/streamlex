<div class="card widget text-center custom-card-height mt-5">
    <div class="card-body">
        <h5 class="card-title">Welcome, <span>{{ Auth::user()->first_name }}</span></h5>
        <p>Your onboarding process is not complete. Please complete the onboarding to access all features.</p>
        <a href="{{ route('onboarding.navigate', 'welcome') }}" class="btn btn-primary">Continue Onboarding</a>
    </div>
</div>