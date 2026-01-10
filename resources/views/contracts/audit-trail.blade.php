@extends('layouts.app')

@section('page-title', __('Contract Audit Trail'))
@section('page-heading', __('Contract Audit Trail'))

@section('styles')
<style>
    .timeline {
        position: relative;
        padding: 20px 0;
    }
    
    .timeline::before {
        content: '';
        background: #ddd;
        width: 2px;
        height: 100%;
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
    }
    
    .timeline-item {
        width: 100%;
        margin-bottom: 30px;
    }
    
    .timeline-content {
        margin-left: 50%;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 5px;
        position: relative;
        width: calc(100% - 70px);
    }
    
    .timeline-content::before {
        content: '';
        width: 20px;
        height: 20px;
        background: #fff;
        border: 2px solid #ddd;
        position: absolute;
        left: -60px;
        top: 20px;
        border-radius: 50%;
    }
    
    .timeline-date {
        position: absolute;
        left: -200px;
        top: 20px;
        width: 150px;
        text-align: right;
    }
    
    .badge-version {
        position: absolute;
        right: 15px;
        top: 15px;
    }
</style>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="mb-4">
            <h4>Contract Details</h4>
            <p><strong>Title:</strong> {{ $contract->title }}</p>
            <p><strong>Current Status:</strong> 
                <span class="badge badge-{{ $contract->status }}">
                    {{ ucfirst($contract->status) }}
                </span>
            </p>
        </div>

        <div class="timeline">
            @foreach($versions as $index => $version)
                <div class="timeline-item">
                    <div class="timeline-content">
                        <span class="badge badge-info badge-version">Version {{ $versions->count() - $index }}</span>
                        <div class="timeline-date">
                            {{ $version->created_at->format('M d, Y H:i') }}
                        </div>
                        
                        <h5>Status: 
                            <span class="badge badge-{{ $version->status }}">
                                {{ ucfirst($version->status) }}
                            </span>
                        </h5>
                        
                        <p><strong>Changed By:</strong> {{ $version->changedByUser->first_name }} {{ $version->changedByUser->last_name }}</p>
                        <p><strong>Reason for Change:</strong> {{ $version->change_reason }}</p>
                        
                        <div class="mt-3">
                            <button class="btn btn-sm btn-primary view-version" 
                                    data-contract-id="{{ $contract->id }}"
                                    data-version-id="{{ $version->id }}">
                                View Contract Version
                            </button>
                            
                            <a href="{{ route('contracts.download-version', ['contract' => $contract->id, 'version' => $version->id]) }}" 
                               class="btn btn-sm btn-secondary">
                                Download PDF
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Contract Version Preview Modal -->
<div class="modal fade" id="versionPreviewModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Contract Version Preview</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <iframe id="versionPreviewFrame" style="width: 100%; height: 700px; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function() {
    $('.view-version').click(function() {
        var contractId = $(this).data('contract-id');
        var versionId = $(this).data('version-id');
        var previewUrl = `/contracts/${contractId}/versions/${versionId}/preview`;
        
        $('#versionPreviewFrame').attr('src', previewUrl);
        $('#versionPreviewModal').modal('show');
    });
});
</script>
@endsection