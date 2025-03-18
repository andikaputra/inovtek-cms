@extends('auth.layouts.app')

@section('title', 'Lupa Password')

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
                <div class="row justify-content-between g-2">
                    <div class="col-auto">
                        <h1 class="auth-title">Lupa Password</h1>
                    </div>
                    <div class="col-auto">
                        <a
                            href="{{ route('safety-entrance.login', ['safety_entrance' => config('app.safety_entrance')]) }}">
                            <p><i class="bi bi-arrow-left"></i> Kembali</p>
                        </a>
                    </div>
                </div>
                <p class="mb-3 auth-subtitle">
                    Email Reset Password akan dikirim ke Email Anda
                </p>
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <form method="POST"
                    action="{{ route('safety-entrance.password.email', ['safety_entrance' => config('app.safety_entrance')]) }}">
                    @csrf
                    <div class="form-group position-relative has-icon-left mb-2">
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
                    {!! RecaptchaV3::field('forgot') !!}
                    <button type="submit" class="btn btn-primary btn-block shadow-lg mt-3">
                        Kirim Email Reset Password
                    </button>
                </form>
            </div>
        </div>
        <div class="col-lg-7 d-none d-lg-block">
            <div id="auth-right"></div>
        </div>
    </div>
@endsection
