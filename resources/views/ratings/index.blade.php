@extends('layouts.app')

@section('page-title', __('Rateable Items'))
@section('page-heading', __('Rateable Items'))

@section('content')
<div class="container-fluid">
    @include('partials.messages')

    <div class="card">
        <div class="card-body">
            <!-- Search and Create Button -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <!-- Search Form -->
                <form action="{{ route('ratings.index') }}" method="GET" class="d-flex gap-2">
                    <div class="input-group" style="width: 300px;">
                        <input type="text" 
                               class="form-control" 
                               name="search" 
                               placeholder="Search by title..." 
                               value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                        @if(request()->has('search'))
                            <a href="{{ route('ratings.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </form>

                <!-- Create Button -->
                <a href="{{ route('ratings.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create New Item
                </a>
            </div>

            <!-- Items Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Attributes</th>
                            <th class="text-center">Ratings</th>
                            <th>Created</th>
                            <th>Status</th>
                            <th>Expires</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr>
                                <td style="max-width: 200px;">
                                    <a href="{{ route('ratings.show', $item) }}" 
                                       class="text-decoration-none text-dark">
                                        {{ Str::limit($item->title, 40) }}
                                    </a>
                                </td>
                                <td>
    <div class="d-flex flex-wrap gap-1">
        @foreach($item->attributes as $attribute)
            <span class="badge bg-light text-dark">
                {{ $attribute->name }}
                <small class="text-muted">
                    ({{ number_format($attribute->average_rating, 1) }})
                </small>
            </span>
        @endforeach
    </div>
</td>
<td class="text-center">
    <div>
        <div class="h5 mb-0">{{ number_format($item->overall_average_rating, 1) }}</div>
        <small class="text-muted">{{ $item->ratings_count }} ratings</small>
    </div>
</td>
                                <td>
                                    {{ $item->created_at->format('M d, Y') }}
                                </td>
                                <td>
                                    <span class="badge bg-{{ $item->status ? 'success' : 'danger' }}">
                                        {{ $item->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    @if($item->expires_at)
                                        @if($item->isExpired())
                                            <span class="badge bg-danger">Expired</span>
                                        @else
                                            {{ $item->expires_at->format('M d, Y') }}
                                        @endif
                                    @else
                                        <span class="text-muted">Never</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('ratings.show', $item) }}" 
                                           class="btn btn-sm btn-outline-secondary"
                                           data-bs-toggle="tooltip"
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <a href="{{ route('ratings.edit', $item) }}" 
                                           class="btn btn-sm btn-outline-primary"
                                           data-bs-toggle="tooltip"
                                           title="Edit Item">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-success"
                                                onclick="generateShareableLink({{ $item->id }})"
                                                data-bs-toggle="tooltip"
                                                title="Share Link">
                                            <i class="fas fa-link"></i>
                                        </button>

                                        @if($item->ratings_count === 0)
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="confirmDelete({{ $item->id }})"
                                                    data-bs-toggle="tooltip"
                                                    title="Delete Item">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>

                                    <form id="delete-form-{{ $item->id }}"
                                          action="{{ route('ratings.destroy', $item) }}"
                                          method="POST"
                                          style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-2x mb-3"></i>
                                        <p class="mb-0">No rateable items found</p>
                                        @if(request()->has('search'))
                                            <div class="mt-2">
                                                <a href="{{ route('ratings.index') }}" class="btn btn-sm btn-outline-primary">
                                                    Clear Search
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $items->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Share Link Modal -->
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
                        <input type="text" id="shareLink" class="form-control" readonly>
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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltips.forEach(tooltip => {
        new bootstrap.Tooltip(tooltip);
    });
});

async function generateShareableLink(id) {
    try {
        const response = await fetch(`/ratings/${id}/share-link`);
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('shareLink').value = data.link;
            new bootstrap.Modal(document.getElementById('shareLinkModal')).show();
            
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Link generated successfully',
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to generate link. Please try again.'
        });
    }
}

function copyLink() {
    const linkInput = document.getElementById('shareLink');
    linkInput.select();
    document.execCommand('copy');
    
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: 'Link copied to clipboard',
        timer: 2000,
        showConfirmButton: false,
        toast: true,
        position: 'top-end'
    });
}

function confirmDelete(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`delete-form-${id}`).submit();
        }
    });
}
</script>
@endpush

@push('styles')
<style>
.attribute-badge {
    max-width: 150px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.table td {
    vertical-align: middle;
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
</style>
@endpush
@endsection