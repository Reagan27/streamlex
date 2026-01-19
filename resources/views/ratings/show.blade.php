@extends('layouts.app')

@section('page-title', $item->title)
@section('page-heading', $item->title)

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap5.min.css">
@endpush

@section('content')
<div class="container">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Overall Rating Summary Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
                    <h5 class="mb-0">Rating Summary</h5>
                    <div>
                        <button class="btn btn-success" onclick="generateShareableLink({{ $item->id }})">
                            <i class="fas fa-link me-1"></i> Share
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Individual Attribute Charts -->
                    <div class="row">
                        @foreach($item->attributes as $attribute)
                            <div class="col-md-6 mb-4">
                                <div class="attribute-summary">
                                    <h6 class="mb-2">{{ $attribute->name }}</h6>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="h3 mb-0 me-2">{{ number_format($attribute->average_rating, 1) }}</div>
                                        <div class="star-display me-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= round($attribute->average_rating))
                                                    <i class="fas fa-star text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-muted"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <small class="text-muted">({{ $attribute->ratings_count }})</small>
                                    </div>

                                    <!-- Rating Distribution -->
                               
@foreach(range(5, 1) as $star)
    @php
        $count = $attribute->ratings()->where('rating', $star)->count();
        $percentage = $attribute->ratings_count > 0 
            ? ($count / $attribute->ratings_count) * 100 
            : 0;
    @endphp
    <div class="d-flex align-items-center small mb-1">
        <div class="text-muted me-2" style="width: 45px;">{{ $star }} star</div>
        <div class="progress flex-grow-1" style="height: 6px">
            <div class="progress-bar bg-warning" style="width: {{ $percentage }}%"></div>
        </div>
        <div class="text-muted ms-2" style="width: 35px">{{ $count }}</div>
    </div>
@endforeach

                                </div>
                            </div>
                            @if($loop->iteration % 2 == 0)
                                <div class="w-100"></div>
                            @endif
                        @endforeach
                    </div>
                </div>
</div>    
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Item Details -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Item Details</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Status</dt>
                        <dd class="col-sm-8">
                            <span class="badge bg-{{ $item->status ? 'success' : 'danger' }}">
                                {{ $item->status ? 'Active' : 'Inactive' }}
                            </span>
                        </dd>

                        <dt class="col-sm-4">Created</dt>
                        <dd class="col-sm-8">{{ $item->created_at->format('M d, Y') }}</dd>

                        <dt class="col-sm-4">Expires</dt>
                        <dd class="col-sm-8">{{ $item->expires_at?->format('M d, Y') ?? 'Never' }}</dd>

                        <dt class="col-sm-4">Total Ratings</dt>
                        <dd class="col-sm-8">{{ $item->ratings->count() }}</dd>
                    </dl>
                </div>
            </div>

            <!-- Actions Card -->
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-icon" title="@lang('Generate Link')" onclick="generateShareableLink({{ $item->id }})">
                            <i class="fas fa-link me-2"></i>
                        </button>
                        <a href="{{ route('ratings.edit', $item->id) }}" title="@lang('Edit Item')" class="btn btn-icon">
                            <i class="fas fa-edit me-2"></i>
                        </a>
                        @if($item->ratings_count === 0)
                            <form action="{{ route('ratings.destroy', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-icon w-100" title="@lang('Delete User')" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash me-2"></i>
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('ratings.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mx-2"></i>Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm w-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Recent Reviews</h5>
                </div>
                <div class="card-body">
        <table id="reviewsTable" class="table table-striped table-borderless">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Reviewer</th>
                    <th>Location</th>
                    @foreach($item->attributes as $attribute)
                        <th>{{ $attribute->name }}</th>
                    @endforeach
                    <th>Comment</th>
                </tr>
            </thead>
            <tbody>
                @foreach($item->ratings()->latest()->get() as $rating)
                    <tr>
                        <td>{{ $rating->created_at->format('M d, Y H:i') }}</td>
                        <td>{{ $rating->rater_name }}</td>
                        <td>{{ $rating->location ?? '-' }}</td>
                        @foreach($item->attributes as $attribute)
                            <td>
                                @php
                                    $attrRating = $rating->attributeRatings
                                        ->where('rateable_attribute_id', $attribute->id)
                                        ->first();
                                @endphp
                                {{ $attrRating ? $attrRating->rating : '-' }}
                            </td>
                        @endforeach
                        <td>{{ $rating->comment ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
            </div> 
    </div>
</div>

@include('ratings.partials.share-modal')

@push('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/2.3.6/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#reviewsTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel me-1"></i> Export Excel',
                        className: 'btn btn-success btn-sm mb-2',
                        title: '{{ $item->title }} - Reviews'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fas fa-file-pdf me-1"></i> Export PDF',
                        className: 'btn btn-danger btn-sm mb-2',
                        title: '{{ $item->title }} - Reviews'
                    }
                ],
                order: [[0, 'desc']],
                pageLength: 20,
                language: {
                    search: "",
                    searchPlaceholder: "Search reviews..."
                },
                columnDefs: [
                {
                    targets: [2], 
                    searchable: true,
                    orderable: true
                }
            ]
            });
        });
async function generateShareableLink(id) {
    try {
        const response = await fetch(`/ratings/${id}/share-link`);
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('shareLink').value = data.link;
            new bootstrap.Modal(document.getElementById('shareLinkModal')).show();
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to generate link. Please try again.',
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
</script>
@endpush
@endsection