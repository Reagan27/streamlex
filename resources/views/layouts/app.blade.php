<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('page-title') - {{ setting('app_name') }}</title>

    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ url('assets/img/icons/apple-touch-icon-144x144.png') }}" />
    <link rel="apple-touch-icon-precomposed" sizes="152x152" href="{{ url('assets/img/icons/apple-touch-icon-152x152.png') }}" />
    <link rel="icon" type="image/png" href="{{ url('assets/img/icons/favicon-32x32.png') }}" sizes="32x32" />
    <link rel="icon" type="image/png" href="{{ url('assets/img/icons/favicon-16x16.png') }}" sizes="16x16" />
    <meta name="application-name" content="{{ setting('app_name') }}"/>
    <meta name="msapplication-TileColor" content="#FFFFFF" />
    <meta name="msapplication-TileImage" content="{{ url('assets/img/icons/mstile-144x144.png') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link media="all" type="text/css" rel="stylesheet" href="{{ url(mix('assets/css/vendor.css')) }}">
    <link media="all" type="text/css" rel="stylesheet" href="{{ url(mix('assets/css/app.css')) }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('assets/css/custom-detail-fix.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">


    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @yield('styles')

    @hook('app:styles')

    @yield('scripts-head')
</head>
<body>
@php
    $userCanAccessFullDashboard = Auth::check() && (
        Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Manager') || 
        (Auth::user()->contractSignature && in_array(Auth::user()->contractSignature->status, ['accepted', 'approved']))
    );
@endphp


    {{-- Custom Field Activities Topnav --}}
    @if (request()->is('field-activities*'))
        <nav class="topnav" style="position:fixed;top:0;left:0;right:0;z-index:1000;height:64px;background:#179970;display:flex;align-items:center;padding:0 1.25rem;box-shadow:0 2px 12px rgba(0,0,0,0.2);">
            <a class="brand" href="{{ route('field-activities.fam') }}" style="font-weight:700;font-size:1rem;color:#fff;letter-spacing:-0.01em;display:flex;align-items:center;gap:0.5rem;text-decoration:none;">
                <div class="brand-icon" style="width:34px;height:34px;border-radius:8px;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;font-size:1rem;">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <span class="desktop-nav">Field Activities</span>
            </a>
            <ul class="topnav-tabs ms-4 desktop-nav" id="desktopNavLinks" style="display:flex;gap:1rem;">
                <li><a class="topnav-tab{{ request()->routeIs('field-activities.fam') ? ' topnav-tab-active' : '' }}" href="{{ route('field-activities.fam') }}" style="color:#fff;background:#179970;border-radius:6px;padding:8px 16px;font-weight:500;">Dashboard</a></li>
                <li><a class="topnav-tab" href="#view-calendar" style="color:#fff;background:#179970;border-radius:6px;padding:8px 16px;font-weight:500;">Calendar</a></li>
                <!-- Removed 'Activities' and 'New' links due to missing routes -->
            </ul>
        <div class="topnav-right" style="margin-left:auto;display:flex;align-items:center;gap:0.5rem;">
            <button class="topnav-btn" style="background:rgba(255,255,255,0.12);border:none;color:#fff;width:36px;height:36px;border-radius:8px;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:1rem;"><i class="bi bi-bell"></i></button>
            <div class="topnav-user ms-1" style="display:flex;align-items:center;gap:0.4rem;color:rgba(255,255,255,0.9);font-size:0.85rem;">
                <div class="avatar" style="width:32px;height:32px;border-radius:50%;background:#00c896;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.75rem;">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                </div>
                <span class="desktop-nav" style="font-size:0.82rem;">{{ auth()->user()->name ?? 'User' }}</span>
            </div>
        </div>
    </nav>
    <div style="height:64px;"></div>
    @else
        @include('partials.navbar')
    @endif

    <!-- Sidebar Navigation -->
    <ul class="nav flex-column" id="mainSidebar">
        <!-- Other menu items ... -->
        <li class="nav-item">
            <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#fieldActivitiesMenu" role="button" aria-expanded="false" aria-controls="fieldActivitiesMenu">
                <span><i class="fas fa-tasks me-2"></i>Field Activities</span>
                <i class="fas fa-chevron-down small"></i>
            </a>
            <div class="collapse" id="fieldActivitiesMenu">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('field-activities.index') }}">
                            <i class="fas fa-list me-2"></i>Field Activities
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('regions.index') }}">
                            <i class="fas fa-map me-2"></i>Region
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        <!-- Other menu items ... -->
    </ul>
    <!-- ...existing code... -->

    <div class="container-fluid">
        <div class="row">
            @include('partials.sidebar.main')
            <main role="main" class="col-md-10 px-4" style="margin-left:250px;">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
    <script src="{{ url('assets/js/as/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
    
    @yield('scripts')
    @hook('app:scripts')
    @stack('scripts')

    @if(Auth::check() && !Auth::user()->nda_accepted && !request()->is('nda*'))
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        $.ajax({
            url: '{{ route('nda.show') }}',
            method: 'GET',
            success: function(data) {
                Swal.fire({
                    title: 'Employee Non-Disclosure Agreement',
                    html: $(data).find('.card-body').html(),
                    width: '80vw',
                    showConfirmButton: false,
                    showCloseButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                });
            }
        });
    });
    </script>
    @endif

    {{-- Employee Info Gating Modal --}}
    @if(session('modal_employeeinfo'))
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'info',
            title: 'Complete Your Employee Info',
            html: '<div style="text-align:left">To access the system, please complete your Employee Info:<ul style="text-align:left"><li>Next of Kin / Emergency Contact</li><li>Education & Professional Qualifications (at least one document)</li><li>Statutory & Compliance Declarations</li></ul></div>',
            confirmButtonText: 'Go to Employee Info',
            allowOutsideClick: false,
            allowEscapeKey: false,
        }).then(function() {
            window.location.href = "{{ route('profile') }}#employeeinfo";
        });
    });
    </script>
    @endif
</body>
    @yield('scripts')
    @hook('app:scripts')
    @stack('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const projectSelect = document.getElementById('global-project-select');
        if (projectSelect) {
            projectSelect.addEventListener('change', function () {
                const value = this.value;
                const url = new URL(window.location);
                if (value) {
                    url.searchParams.set('project_id', value);
                } else {
                    url.searchParams.delete('project_id');
                }
                window.location.href = url.toString();
            });
        }
    });
    </script>
</body>
</html>
</html>
