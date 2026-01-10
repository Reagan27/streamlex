
<div class="row mb-4">
    <div class="col-md-12">
        <form action="{{ route('field-reports.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <div class="input-group">
                    <input type="text" 
                           class="form-control" 
                           name="search" 
                           placeholder="Search by title or location..." 
                           value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>

            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Submitted</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                </select>
            </div>

            <div class="col-md-2">
                <input type="date" 
                       name="date_from" 
                       class="form-control" 
                       value="{{ request('date_from') }}"
                       placeholder="Date From">
            </div>

            <div class="col-md-2">
                <input type="date" 
                       name="date_to" 
                       class="form-control" 
                       value="{{ request('date_to') }}"
                       placeholder="Date To">
            </div>

            <div class="col-md-3">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-filter me-1"></i>Apply Filters
                </button>
                @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                    <a href="{{ route('field-reports.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i>Clear
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>