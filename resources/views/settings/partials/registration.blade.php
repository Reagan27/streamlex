<div class="card">
    <h6 class="card-header">@lang('Email Confirmation')</h6>
    <div class="card-body">
        <form action="{{ route('settings.auth.update') }}" method="POST" id="email-confirmation-settings-form">
            @csrf
            <div class="form-group my-4">
                <div class="d-flex align-items-center">
                    <div class="switch">
                        <input type="hidden" value="0" name="reg_email_confirmation">
                        <input
                            value="1"
                            type="checkbox"
                            name="reg_email_confirmation"
                            id="switch-reg-email-confirm"
                            class="switch"
                            {{ $settings['reg_email_confirmation'] ?? false ? 'checked' : '' }}>
                        <label for="switch-reg-email-confirm"></label>
                    </div>
                    <div class="ml-3 d-flex flex-column">
                        <label class="mb-0">
                            @lang('Email Confirmation')
                        </label>
                        <small class="text-muted">
                            @lang('Send email notifications to newly created, approved, declined, and accepted users.')
                        </small>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">
                @lang('Update')
            </button>
        </form>
    </div>
</div>