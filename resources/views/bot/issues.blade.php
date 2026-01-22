@extends('layouts.app')

@section('page-title', __('Bot Issues'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-exclamation-circle"></i> Bot Issues & Support
                    </h5>
                    <a href="{{ route('bot.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Batches
                    </a>
                </div>
                <div class="card-body">
                    <!-- Stats Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h3>{{ $stats['total_issues'] }}</h3>
                                    <p>Total Issues</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h3>{{ $stats['pending'] }}</h3>
                                    <p>Pending</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h3>{{ $stats['in_progress'] }}</h3>
                                    <p>In Progress</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h3>{{ $stats['resolved'] }}</h3>
                                    <p>Resolved</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Issues Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Issue ID</th>
                                    <th>User</th>
                                    <th>Phone</th>
                                    <th>Category</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Assigned To</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($issues as $issue)
                                <tr>
                                    <td>
                                        <span class="badge badge-secondary">{{ $issue->issue_id }}</span>
                                    </td>
                                    <td>{{ $issue->user ? $issue->user->first_name . ' ' . $issue->user->last_name : 'Guest' }}</td>
                                    <td>{{ $issue->phone_number }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ ucfirst(str_replace('_', ' ', $issue->category)) }}</span>
                                    </td>
                                    <td>
                                        <span data-toggle="tooltip" data-placement="top" title="{{ $issue->description }}">
                                            {{ Str::limit($issue->description, 50) }}
                                        </span>
                                    </td>
                                    <td>
                                        @switch($issue->status)
                                            @case('pending')
                                                <span class="badge badge-warning">Pending</span>
                                                @break
                                            @case('in_progress')
                                                <span class="badge badge-info">In Progress</span>
                                                @break
                                            @case('resolved')
                                                <span class="badge badge-success">Resolved</span>
                                                @break
                                            @case('closed')
                                                <span class="badge badge-secondary">Closed</span>
                                                @break
                                            @default
                                                <span class="badge badge-light">{{ ucfirst($issue->status) }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        @if($issue->assignedUser)
                                            {{ $issue->assignedUser->first_name . ' ' . $issue->assignedUser->last_name }}
                                        @else
                                            <em class="text-muted">Unassigned</em>
                                        @endif
                                    </td>
                                    <td>{{ $issue->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" 
                                                    class="btn btn-primary view-issue" 
                                                    data-issue-id="{{ $issue->id }}"
                                                    data-toggle="modal" 
                                                    data-target="#issueModal">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-success update-status" 
                                                    data-issue-id="{{ $issue->id }}"
                                                    data-current-status="{{ $issue->status }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">
                                        <em>No issues found.</em>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $issues->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Issue Details Modal -->
<div class="modal fade" id="issueModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Issue Details</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="issue-details">
                <!-- Issue details will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="update-status-form">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Update Issue Status</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="issue-id" name="issue_id">
                    
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="issue-status" class="form-control" required>
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="resolved">Resolved</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Resolution Notes (Optional)</label>
                        <textarea name="resolution_notes" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // View issue details
    $('.view-issue').on('click', function() {
        const issueId = $(this).data('issue-id');
        const issue = @json($issues->items());
        const selectedIssue = issue.find(i => i.id === issueId);
        
        if (selectedIssue) {
            let html = `
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Issue ID:</strong> ${selectedIssue.issue_id}</p>
                        <p><strong>Phone:</strong> ${selectedIssue.phone_number}</p>
                        <p><strong>Category:</strong> ${selectedIssue.category.replace(/_/g, ' ')}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Status:</strong> <span class="badge badge-info">${selectedIssue.status}</span></p>
                        <p><strong>Created:</strong> ${new Date(selectedIssue.created_at).toLocaleString()}</p>
                        <p><strong>User:</strong> ${selectedIssue.user ? selectedIssue.user.first_name + ' ' + selectedIssue.user.last_name : 'Guest'}</p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <p><strong>Description:</strong></p>
                        <p>${selectedIssue.description}</p>
                    </div>
                </div>
            `;
            
            if (selectedIssue.resolution_notes) {
                html += `
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <p><strong>Resolution Notes:</strong></p>
                            <p>${selectedIssue.resolution_notes}</p>
                        </div>
                    </div>
                `;
            }
            
            $('#issue-details').html(html);
        }
    });

    // Update status
    $('.update-status').on('click', function() {
        const issueId = $(this).data('issue-id');
        const currentStatus = $(this).data('current-status');
        
        $('#issue-id').val(issueId);
        $('#issue-status').val(currentStatus);
        $('#statusModal').modal('show');
    });

    // Submit status update
    $('#update-status-form').on('submit', function(e) {
        e.preventDefault();
        
        const issueId = $('#issue-id').val();
        const formData = $(this).serialize();
        
        $.ajax({
            url: `/bot/issues/${issueId}`,
            method: 'PUT',
            data: formData,
            success: function(response) {
                $('#statusModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Issue status updated successfully',
                    timer: 2000
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to update issue status'
                });
            }
        });
    });
});
</script>
@endpush
@endsection