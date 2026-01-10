<div class="modal fade" id="shareLinkModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Shareable Link</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <div class="input-group">
                        <input type="text" 
                               id="shareLink" 
                               class="form-control" 
                               readonly>
                        <button class="btn btn-outline-secondary" 
                                type="button" 
                                onclick="copyLink()">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
                <p class="text-muted small mb-0">
                    Share this link with others to let them rate this item
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" 
                        class="btn btn-secondary" 
                        data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>