@extends('layouts.auth')

@section('page-title', trans('Login'))

@section('content')
<div class="col-md-8 col-lg-6 col-xl-5 mx-auto" id="login">
    <div class="text-center">
        <x-logo class="logo-lg" height="200" />
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title text-center mt-4 text-uppercase">
                @lang('Login')
            </h5>

            <div class="p-4">
                @include('partials.messages')

                <form role="form" action="<?= url('login') ?>" method="POST" id="login-form" autocomplete="off" class="mt-3">
                    <input type="hidden" value="<?= csrf_token() ?>" name="_token">

                    @if (Request::has('to'))
                        <input type="hidden" value="{{ Request::get('to') }}" name="to">
                    @endif

                    <div class="form-group">
                        <label for="username" class="sr-only">@lang('Email or Username')</label>
                        <input type="text"
                               name="username"
                               id="username"
                               class="form-control input-solid"
                               placeholder="@lang('Email or Username')"
                               value="{{ old('username') }}">
                    </div>

                    <div class="form-group password-field">
                        <label for="password" class="sr-only">@lang('Password')</label>
                        <div class="input-group">
                            <input type="password"
                                   name="password"
                                   id="password"
                                   class="form-control input-solid"
                                   placeholder="@lang('Password')">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-eye" id="togglePassword" style="cursor: pointer;"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    @if (setting('remember_me'))
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="remember" id="remember" value="1"/>
                            <label class="custom-control-label font-weight-normal" for="remember">
                                @lang('Remember me?')
                            </label>
                        </div>
                    @endif

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary btn-lg btn-block" id="btn-login">
                            @lang('Log In')
                        </button>
                    </div>
                </form>

                @if (setting('forgot_password'))
                    <a href="<?= route('password.request') ?>" class="forgot">@lang('I forgot my password')</a>
                @endif
            </div>
        </div>
    </div>
<!-- 
    <div class="text-center text-muted">
        @if (setting('reg_enabled'))
            @lang("Don't have an account?")
            <a class="font-weight-bold" href="<?= url("register") ?>">@lang('Sign Up')</a>
        @endif
    </div> -->
</div>
@stop

@section('scripts')
    <script src="{{ asset('assets/js/as/login.js') }}"></script>
    {!! JsValidator::formRequest('Vanguard\Http\Requests\Auth\LoginRequest', '#login-form') !!}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    });
    </script>
@stop