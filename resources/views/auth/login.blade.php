@extends('auth.layouts.app')

@section('title', 'Login')

@section('customJs')
    {!! RecaptchaV3::initJs() !!}
@endsection

@section('content')
    <div class="row h-100">
        <div class="col-lg-5 col-12">
            <div id="auth-left">
                <div class="auth-logo">
                    <a href="{{ route('safety-entrance.login', ['safety_entrance' => config('app.safety_entrance')]) }}">
                        <img loading="lazy" src="{{ asset('assets/images/logo/logo.png') }}" alt="Logo" class="img-fluid" />
                    </a>
                </div>
                <h1 class="auth-title text-center mt-4">SAFETY LOGIN</h1>
                <form method="POST"
                    action="{{ route('safety-entrance.login-action', ['safety_entrance' => config('app.safety_entrance')]) }}"
                    class="mt-3">
                    @csrf
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                            value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email" />
                        <div class="form-control-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                            required autocomplete="current-password" placeholder="Password" />
                        <div class="form-control-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-check form-check-lg d-flex align-items-end">
                        <input class="form-check-input me-2" type="checkbox" name="remember" id="remember"
                            {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label text-gray-600" for="remember">
                            Ingat Saya
                        </label>
                    </div>
                    {!! RecaptchaV3::field('login') !!}
                    <button type="submit" class="btn btn-primary btn-block  shadow-lg mt-5">
                        Masuk
                    </button>
                </form>

                <div class="row">
                    <div class="d-flex col-6 justify-content-start mt-3 text-sm fs-5">
                        <p>
                            <a class="font-bold text-secondary"
                                href="{{ route('safety-entrance.password.request', ['safety_entrance' => config('app.safety_entrance')]) }}">Lupa
                                password?</a>
                        </p>
                    </div>
                </div>
                <div class="text-center">
                </div>
            </div>
        </div>
        <div class="col-lg-7 d-none d-lg-block">
            <div id="auth-right"></div>
        </div>
    </div>
@endsection
