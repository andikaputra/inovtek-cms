@extends('errors.layouts.app')

@section('title', $error)

@section('content')
    <div class="error-page container">
        <div class="col-md-8 col-12 offset-md-2">
            <div class="text-center">
                <img loading="lazy" class="img-error" src="{{ asset('assets/images/logo/error.png') }}" width="350px"
                    alt="Not Found" />
                <h1 class="error-title">Integrasi BNPB Gagal Dilakukan</h1>
                <p class="fs-5 text-gray-600">
                    {{ $error }}
                </p>
                <a href="{{ config('services.bnpb.login_url') }}" class="btn btn-lg btn-outline-primary mt-3">Ke Halaman
                    Utama</a>
            </div>
        </div>
    </div>
@endsection
