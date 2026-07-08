<!-- Terminate Contract Modal -->
<div class="modal fade" id="terminateModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Terminate Contract')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="terminateForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="termination_reason">@lang('Termination Reason')</label>
                        <textarea class="form-control" id="termination_reason" name="termination_reason" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="termination_date">@lang('Termination Date')</label>
                        <input type="date" class="form-control" id="termination_date" name="termination_date" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Cancel')</button>
                    <button type="submit" class="btn btn-danger">@lang('Terminate Contract')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reset User Modal -->
<div class="modal fade" id="resetModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Reset User Status')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="resetForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <p class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        @lang('This will reset the user\'s contract status and allow them to start a new onboarding process. Are you sure?')
                    </p>
                    <div class="form-group">
                        <label for="reset_reason">@lang('Reset Reason')</label>
                        <textarea class="form-control" id="reset_reason" name="reset_reason" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Cancel')</button>
                    <button type="submit" class="btn btn-warning">@lang('Reset User')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Contract Details')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="contract-details-content">
                    <!-- Content will be loaded dynamically -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>