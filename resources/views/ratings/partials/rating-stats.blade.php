<div class="card shadow-sm mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">Rating Statistics</h5>
    </div>
    <div class="card-body">
        @forelse($item->attributes as $attribute)
            <div class="attribute-stats mb-4">
                <h6 class="mb-2">{{ $attribute->name }}</h6>
                <div class="d-flex align-items-center mb-2">
                    <div class="h3 mb-0 me-2">{{ number_format($attribute->average_rating, 1) }}</div>
                    <div class="star-display">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= round($attribute->average_rating))
                                <i class="fas fa-star text-warning"></i>
                            @else
                                <i class="far fa-star text-muted"></i>
                            @endif
                        @endfor
                    </div>
                    <div class="ms-2 text-muted">
                        ({{ $attribute->ratings_count }} ratings)
                    </div>
                </div>

                {{-- Rating breakdown bars --}}
                @foreach(range(5, 1) as $rating)
                    @php
                        $count = $attribute->ratings()->where('rating', $rating)->count();
                        $percentage = $attribute->ratings_count > 0 
                            ? ($count / $attribute->ratings_count) * 100 
                            : 0;
                    @endphp
                    <div class="d-flex align-items-center mb-1">
                        <span class="me-2" style="min-width: 60px;">{{ $rating }} stars</span>
                        <div class="progress flex-grow-1" style="height: 8px;">
                            <div class="progress-bar bg-warning" 
                                 style="width: {{ $percentage }}%">
                            </div>
                        </div>
                        <span class="ms-2 text-muted small" style="min-width: 40px;">
                            {{ $count }}
                        </span>
                    </div>
                @endforeach
            </div>
            @if(!$loop->last)
                <hr>
            @endif
        @empty
            <p class="text-muted text-center mb-0">No attributes defined</p>
        @endforelse
    </div>
</div>