<nav class="navbar fixed-top align-items-start navbar-expand-lg pl-0 pr-0 py-2">

    <div class="navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand mr-0" href="{{ url('/') }}">
            <x-logo class="logo-lg" height="95" />
            <x-logo variant="no-text" class="logo-sm" height="95" />
        </a>
    </div>

    <div>
        @if (app('impersonate')->isImpersonating())
            <a href="{{ route('impersonate.leave') }}" class="navbar-toggler text-danger hidden-md">
                <i class="fas fa-user-secret"></i>
            </a>
        @endif

        <button class="navbar-toggler" type="button" id="sidebar-toggle">
            <i class="fas fa-align-right text-muted"></i>
        </button>

        <button class="navbar-toggler mr-3" type="button" data-toggle="collapse"
                data-target="#top-navigation" aria-controls="top-navigation"
                aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars text-muted"></i>
        </button>
    </div>

    <div class="collapse navbar-collapse py-2" id="top-navigation">
        <div class="row ml-2">
            <div class="col-lg-12 d-flex align-items-left align-items-md-center flex-column flex-md-row py-3">
                <h4 class="page-header mb-0">@yield('page-heading')</h4>
                <ol class="breadcrumb mb-0 font-weight-light">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/') }}" class="text-muted">
                            <i class="fa fa-home"></i>
                        </a>
                    </li>
                    @yield('breadcrumbs')
                </ol>
            </div>
        </div>

        <ul class="navbar-nav ml-auto pr-3 flex-row">

            {{-- Project Selector --}}
            @if(isset($projects))
                @php
                    // Handle both paginated projects (from index) and collections (from view composer)
                    if ($projects instanceof \Illuminate\Pagination\LengthAwarePaginator) {
                        $projectItems = $projects->items();
                    } else {
                        $projectItems = $projects;
                    }
                @endphp
                
                @if(count($projectItems) > 0)
                <li class="nav-item mr-3 d-flex align-items-center">
                    <small class="text-muted mr-2 mt-1">Project:</small>
                    <select name="project_id" id="global-project-select" class="form-control input-solid" style="width: 220px;">
                        <option value="">@lang('All Projects')</option>
                        @foreach($projectItems as $project)
                            <option value="{{ $project->id }}" 
                                    {{ (isset($currentActiveProject) && $currentActiveProject && $currentActiveProject->id == $project->id) ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                </li>
                @endif
            @endif

            {{-- Active Project Badge --}}
            @if(isset($currentActiveProject) && $currentActiveProject)
            <li class="nav-item mr-3 d-flex align-items-center">
                <span class="badge bg-primary">
                    <i class="fas fa-briefcase"></i> 
                    {{ $currentActiveProject->name }}
                </span>
            </li>
            @endif

            {{-- Impersonate Button --}}
            @if (app('impersonate')->isImpersonating())
            <li class="nav-item d-flex align-items-center">
                <a href="{{ route('impersonate.leave') }}" class="btn text-danger">
                    <i class="fas fa-user-secret mr-2"></i> @lang('Stop Impersonating')
                </a>
            </li>
            @endif

            {{-- Other Navbar Items --}}
            @hook('navbar:items')

            {{-- User Avatar Dropdown --}}
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    @php $user = auth()->user(); @endphp
                    @if($user && $user->present())
                        <img src="{{ $user->present()->avatar }}" width="50" height="50"
                             class="rounded-circle img-thumbnail img-responsive">
                    @else
                        <img src="/default-avatar.png" width="50" height="50"
                             class="rounded-circle img-thumbnail img-responsive">
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-right position-absolute p-0" aria-labelledby="navbarDropdown">
                    <div class="text-center py-3">
                        <h5 class="mt-2">
                            @if($user && $user->present())
                                <a href="{{ route('profile') }}">{{ $user->present()->nameOrEmail }}</a>
                            @else
                                Guest
                            @endif
                        </h5>
                        <p class="text-muted mb-0">
                            {{ $user && $user->role ? $user->role->display_name : 'No Role Assigned' }}
                        </p>
                    </div>
                    <a class="dropdown-item py-2" href="{{ route('profile') }}">
                        <i class="fas fa-user text-muted mr-2"></i> @lang('My Profile')
                    </a>
                    @if (config('session.driver') == 'database')
                    <a href="{{ route('profile.sessions') }}" class="dropdown-item py-2">
                        <i class="fas fa-list text-muted mr-2"></i> @lang('Active Sessions')
                    </a>
                    @endif
                    @hook('navbar:dropdown')
                    <div class="dropdown-divider m-0"></div>
                    <a class="dropdown-item py-2" href="{{ route('auth.logout') }}">
                        <i class="fas fa-sign-out-alt text-muted mr-2"></i> @lang('Logout')
                    </a>
                </div>
            </li>
        </ul>
    </div>
</nav>

{{-- Project Selector JavaScript --}}
@push('scripts')
<script>
$(document).ready(function() {
    $('#global-project-select').on('change', function() {
        var projectId = $(this).val();
        
        if (projectId) {
            // Set active project
            $.ajax({
                url: '{{ route("projects.set-active", ":id") }}'.replace(':id', projectId),
                method: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    location.reload();
                },
                error: function(xhr) {
                    alert('Error setting active project');
                }
            });
        } else {
            // Clear active project
            $.ajax({
                url: '{{ route("projects.clear-active") }}',
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    location.reload();
                },
                error: function(xhr) {
                    alert('Error clearing active project');
                }
            });
        }
    });
});
</script>
@endpush