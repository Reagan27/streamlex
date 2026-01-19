@extends('layouts.app')

@section('page-title', __('Asset History'))
@section('page-heading', __('Asset History'))

@section('content')
<div class="card">
    <div class="card-body">
        <!-- Search and Filter Section -->
        <div class="row mb-4">
            <div class="col-md-6">
                <form action="{{ route('asset.history.index') }}" method="GET" class="mb-0">
                    <div class="input-group">
                        <input type="text"
                               class="form-control"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Search by IMEI, Serial, or Name...">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-6 text-right">
                <div class="btn-group" role="group">
                    <button type="button" 
                            class="btn btn-outline-primary active" 
                            id="showSingleAssignments">
                        Single Assignments
                    </button>
                    <button type="button" 
                            class="btn btn-outline-primary" 
                            id="showBulkDistributions">
                        Bulk Distributions
                    </button>
                </div>
            </div>
        </div>

        <!-- Single Assignments Section -->
        <div id="singleAssignmentsSection">
            <div class="table-responsive">
                <table class="table table-borderless table-striped">
                    <thead>
                        <tr>
                            <th>Asset</th>
                            <th>Assigned To</th>
                            <th>Assigned By</th>
                            <th>
                                @if(auth()->user()->hasRole('Admin'))
                                    <i class="fas fa-edit text-primary small"></i>
                                @endif
                                IMEI Number
                            </th>
                            <th>
                                @if(auth()->user()->hasRole('Admin'))
                                    <i class="fas fa-edit text-primary small"></i>
                                @endif
                                Serial Number
                            </th>
                            <th>Physical Condition</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($singleAssignments as $assignment)
                            <tr>
                                <td>{{ $assignment->asset_name }}</td>
                                <td>{{ $assignment->assigned_to_name }} {{ $assignment->assigned_to_lastname }}</td>
                                <td>{{ $assignment->assigned_by_name }} {{ $assignment->assigned_by_lastname }}</td>
                                <td class="editable-cell {{ auth()->user()->hasRole('Admin') ? 'editable' : '' }}">
                                    @if(auth()->user()->hasRole('Admin'))
                                        <span class="display-text">{{ $assignment->imei_number ?: 'Click to add IMEI' }}</span>
                                        <input type="text" 
                                               class="form-control edit-input d-none" 
                                               data-field="imei_number"
                                               data-assignment-id="{{ $assignment->id }}"
                                               value="{{ $assignment->imei_number }}">
                                    @else
                                        {{ $assignment->imei_number }}
                                    @endif
                                </td>
                                <td class="editable-cell {{ auth()->user()->hasRole('Admin') ? 'editable' : '' }}">
                                    @if(auth()->user()->hasRole('Admin'))
                                        <span class="display-text">{{ $assignment->serial_number ?: 'Click to add Serial' }}</span>
                                        <input type="text" 
                                               class="form-control edit-input d-none" 
                                               data-field="serial_number"
                                               data-assignment-id="{{ $assignment->id }}"
                                               value="{{ $assignment->serial_number }}">
                                    @else
                                        {{ $assignment->serial_number }}
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-{{ $assignment->physical_condition === 'Good' ? 'success' : ($assignment->physical_condition === 'Poor' ? 'warning' : ($assignment->physical_condition === 'Damaged' ? 'danger' : 'info')) }}">
                                        {{ $assignment->physical_condition }}
                                    </span>
                                </td>
                                <td>
                                        {{ $assignment->assignment_status }}
                                </td>
                                <td>{{ $assignment->created_at }}</td>
                                <td>
                                    <div 
                                            class="" 
                                            data-toggle="modal" 
                                            data-target="#detailsModal_{{ $assignment->id }}">
                                        <i class="fas fa-eye"></i>
</div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $singleAssignments->appends(request()->except('page'))->links() }}
            </div>
        </div>

        <!-- Bulk Distributions Section -->
        <div id="bulkDistributionsSection" style="display: none;">
            <div class="table-responsive">
                <table class="table table-borderless table-striped">
                    <thead>
                        <tr>
                            <th>Asset</th>
                            <th>Distributed To</th>
                            <th>Distributed By</th>
                            <th>Quantity</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bulkDistributions as $distribution)
                            <tr>
                                <td>{{ $distribution->asset_name }}</td>
                                <td>{{ $distribution->distributed_to_name }} {{ $distribution->distributed_to_lastname }}</td>
                                <td>{{ $distribution->distributed_by_name }} {{ $distribution->distributed_by_lastname }}</td>
                                <td>{{ $distribution->quantity }}</td>
                                <td>{{ $distribution->created_at }}</td>
                                <td>
                                    <div
                                            class="" 
                                            data-toggle="modal" 
                                            data-target="#bulkDetailsModal_{{ $distribution->id }}">
                                        <i class="fas fa-eye"></i>
</div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $bulkDistributions->appends(request()->except('page'))->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Single Assignment Details Modal -->
@foreach($singleAssignments as $assignment)
<div class="modal fade" id="detailsModal_{{ $assignment->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Asset Assignment Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <strong>Asset:</strong>
                        <p>{{ $assignment->asset_name }}</p>
                    </div>
                    <div class="col-md-4">
                        <strong>IMEI Number:</strong>
                        <p>{{ $assignment->imei_number ?: 'Not specified' }}</p>
                    </div>
                    <div class="col-md-4">
                        <strong>Serial Number:</strong>
                        <p>{{ $assignment->serial_number ?: 'Not specified' }}</p>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-4">
                        <strong>Assigned To:</strong>
                        <p>{{ $assignment->assigned_to_name }} {{ $assignment->assigned_to_lastname }}</p>
                    </div>
                    <div class="col-md-4">
                        <strong>Assigned By:</strong>
                        <p>{{ $assignment->assigned_by_name }} {{ $assignment->assigned_by_lastname }}</p>
                    </div>
                    <div class="col-md-4">
                        <strong>Date:</strong>
                        <p>{{ $assignment->created_at }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <strong>Physical Condition:</strong>
                        <p>
                            <span class="badge badge-{{ $assignment->physical_condition === 'Good' ? 'success' : ($assignment->physical_condition === 'Poor' ? 'warning' : ($assignment->physical_condition === 'Damaged' ? 'danger' : 'info')) }}">
                                {{ $assignment->physical_condition }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <strong>Assignment Status:</strong>
                        <p>
                            <span class="badge badge-{{ $assignment->assignment_status === 'Assigned' ? 'primary' : 'secondary' }}">
                                {{ $assignment->assignment_status }}
                            </span>
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <strong>Comments:</strong>
                        <p class="mt-2">{{ $assignment->comments ?: 'No comments available.' }}</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Bulk Distribution Details Modal -->
@foreach($bulkDistributions as $distribution)
<div class="modal fade" id="bulkDetailsModal_{{ $distribution->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Distribution Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <strong>Asset:</strong>
                        <p>{{ $distribution->asset_name }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Quantity:</strong>
                        <p>{{ $distribution->quantity }}</p>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-4">
                        <strong>Distributed To:</strong>
                        <p>{{ $distribution->distributed_to_name }} {{ $distribution->distributed_to_lastname }}</p>
                    </div>
                    <div class="col-md-4">
                        <strong>Distributed By:</strong>
                        <p>{{ $distribution->distributed_by_name }} {{ $distribution->distributed_by_lastname }}</p>
                    </div>
                    <div class="col-md-4">
                        <strong>Date:</strong>
                        <p>{{ $distribution->created_at }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <strong>Comments:</strong>
                        <p class="mt-2">{{ $distribution->comments ?: 'No comments available.' }}</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection

@push('styles')
<style>
.editable-cell {
    position: relative;
}

.editable-cell.editable {
    border: 2px dotted #007bff;
    cursor: pointer;
}

.editable-cell .display-text {
    padding: 5px;
    display: inline-block;
    min-width: 50px;
}

.editable-cell.editable:hover {
    background-color: #f8f9fa;
}

.editable-cell .edit-input {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    padding: 5px;
    box-sizing: border-box;
}

.edit-input.active {
    z-index: 1;
}

.d-none {
    display: none;
}

.editable-cell .display-text:empty::after {
    content: 'Click to add';
    color: #6c757d;
    font-style: italic;
}

.fas.fa-edit {
    font-size: 0.8em;
    margin-left: 4px;
    opacity: 0.7;
}

.btn-group .btn.active {
    background-color: #007bff;
    color: white;
}

.btn-icon {
    padding: 0.25rem 0.5rem;
}

.badge {
    font-size: 85%;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // View toggle functionality
    $('#showSingleAssignments').click(function() {
        $(this).addClass('active');
        $('#showBulkDistributions').removeClass('active');
        $('#singleAssignmentsSection').show();
        $('#bulkDistributionsSection').hide();
    });

    $('#showBulkDistributions').click(function() {
        $(this).addClass('active');
        $('#showSingleAssignments').removeClass('active');
        $('#singleAssignmentsSection').hide();
        $('#bulkDistributionsSection').show();
    });

    // Editable cells functionality
    $('.editable-cell .display-text').on('click', function() {
        var cell = $(this).closest('.editable-cell');
        $(this).addClass('d-none');
        cell.find('.edit-input')
            .removeClass('d-none')
            .addClass('active')
            .focus();
    });
    // Save on enter or blur
    $('.editable-cell .edit-input').on('keypress blur', function(e) {
        if (e.type !== 'blur' && e.which !== 13) return;
        
        var input = $(this);
        var cell = input.closest('.editable-cell');
        var displayText = cell.find('.display-text');
        var assignmentId = input.data('assignment-id');
        var field = input.data('field');
        var value = input.val().trim();

        $.ajax({
            url: route('asset.history.update-numbers', assignmentId),
            method: 'PATCH',
            data: {
                _token: '{{ csrf_token() }}',
                [field]: value
            },
            success: function(response) {
                displayText.text(value || 'Click to add ' + (field === 'imei_number' ? 'IMEI' : 'Serial'));
                input.removeClass('active').addClass('d-none');
                displayText.removeClass('d-none');
                
                // Show success feedback
                toastr.success('Updated successfully');
            },
            error: function(xhr) {
                toastr.error('Error updating value. Please try again.');
                input.val(displayText.text());
                input.removeClass('active').addClass('d-none');
                displayText.removeClass('d-none');
            }
        });
    });

    // Cancel on escape
    $('.editable-cell .edit-input').on('keyup', function(e) {
        if (e.key === "Escape") {
            var input = $(this);
            var cell = input.closest('.editable-cell');
            var displayText = cell.find('.display-text');
            
            input.removeClass('active').addClass('d-none');
            displayText.removeClass('d-none');
        }
    });

    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Initialize modals
    $('.modal').on('show.bs.modal', function () {
        // Ensure modal is centered
        $(this).css('display', 'block');
        var modalDialog = $(this).find('.modal-dialog');
        modalDialog.css('margin-top', Math.max(0, ($(window).height() - modalDialog.height()) / 2));
    });

    // Handle search form submission
    $('form').on('submit', function(e) {
        var searchInput = $(this).find('input[name="search"]');
        if (searchInput.val().trim() === '') {
            e.preventDefault();
            searchInput.focus();
        }
    });

    // Add responsive handling for tables
    $(window).on('resize', function() {
        if ($(window).width() < 768) {
            $('.table-responsive').addClass('table-sm');
        } else {
            $('.table-responsive').removeClass('table-sm');
        }
    }).trigger('resize');

    // Add hover effect for action buttons
    $('.btn-icon').hover(
        function() { $(this).find('.fas').addClass('text-white'); },
        function() { $(this).find('.fas').removeClass('text-white'); }
    );

    // Handle tab state persistence
    let activeTab = localStorage.getItem('assetHistoryActiveTab');
    if (activeTab === 'bulk') {
        $('#showBulkDistributions').click();
    }

    // Save tab state
    $('#showSingleAssignments, #showBulkDistributions').click(function() {
        localStorage.setItem('assetHistoryActiveTab', 
            $(this).attr('id') === 'showBulkDistributions' ? 'bulk' : 'single');
    });

    // Add loading indicator for AJAX requests
    $(document).ajaxStart(function() {
        $('body').addClass('loading');
    }).ajaxStop(function() {
        $('body').removeClass('loading');
    });

    // Enhance modal scrolling behavior
    $('.modal').on('shown.bs.modal', function() {
        if ($(this).height() > $(window).height()) {
            $(this).find('.modal-body').css({
                'max-height': $(window).height() * 0.7,
                'overflow-y': 'auto'
            });
        }
    });

    // Add keyboard navigation for modals
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            $('.modal').modal('hide');
        }
    });
});
</script>
@endpush

@push('styles')
<style>
/* Loading indicator styles */
body.loading:before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.8);
    z-index: 1000;
}

body.loading:after {
    content: 'Loading...';
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 1.2em;
    color: #007bff;
    z-index: 1001;
}

/* Enhanced modal styles */
.modal-dialog {
    transition: transform 0.3s ease-out;
}

.modal.fade .modal-dialog {
    transform: translate(0, -5%);
}

.modal.show .modal-dialog {
    transform: translate(0, 0);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .btn-group {
        width: 100%;
        margin-top: 1rem;
    }

    .btn-group .btn {
        flex: 1;
    }

    .modal-dialog {
        margin: 0.5rem;
    }
}

/* Print styles */
@media print {
    .btn-group,
    .modal-footer,
    .search-section {
        display: none !important;
    }

    .modal {
        position: static;
        display: block;
    }

    .modal-dialog {
        margin: 0;
        width: 100%;
    }
}
</style>
@endpush