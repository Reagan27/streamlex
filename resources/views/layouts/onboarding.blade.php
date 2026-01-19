<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Onboarding - {{ setting('app_name') }}</title>
    <link href="{{ asset('assets/css/vendor.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/onboarding.css') }}" rel="stylesheet">
    <style>
        /* Custom responsive styles */
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .logo-wrapper {
            padding: 1.5rem 1rem;
        }
        
        @media (max-width: 768px) {
            .logo-wrapper {
                padding: 1rem 0.5rem;
            }
            
            .logo {
                max-width: 200px;
                margin: 0 auto;
            }
            
            .wizard {
                padding: 1rem;
                margin: 0;
                width: 100%;
            }
            
            .container-fluid {
                padding: 0;
            }
        }
        
        .wizard {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .step-content {
            flex: 1;
            min-height: 0;
            overflow-y: auto;
            padding: 1.5rem;
        }
        
        /* Adjust contract view for mobile */
        @media (max-width: 768px) {
            .contract-description-container {
                height: 300px;
            }
            
            #signature-pad canvas {
                width: 100% !important;
                height: 150px !important;
            }
        }
        
        /* Adjust form groups for better mobile spacing */
        .form-group {
            margin-bottom: 1.25rem;
        }
        
        /* Make buttons more touch-friendly on mobile */
        @media (max-width: 768px) {
            .btn {
                padding: 0.625rem 1.25rem;
                font-size: 1rem;
            }
            
            .d-flex.justify-content-between {
                padding: 1rem 0;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="container-fluid d-flex flex-column min-vh-100">
        <div class="row">
            <div class="col-12 col-md-6 offset-md-3 logo-wrapper text-center">
                <x-logo class="logo" />
            </div>
        </div>
        <div class="row flex-grow-1">
            <div class="col-12 col-md-6 offset-md-3 wizard">
                @yield('content')
            </div>
        </div>
    </div>

    <script type="text/javascript" src="{{ mix("assets/js/vendor.js") }}"></script>
    <script type="text/javascript" src="{{ url('assets/js/as/app.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/js/as/btn.js') }}"></script>
    <script>
        $("a[data-toggle=loader], button[data-toggle=loader]").click(function () {
            if ($(this).parents('form').valid()) {
                as.btn.loading($(this), $(this).data('loading-text'));
                $(this).parents('form').submit();
            }
        });
    </script>
    @yield('scripts')
</body>
</html>