@extends('errors.layouts.app')

@section('title', '404 Not Found')

@section('content')
    <div class="error-page container">
        <div class="col-md-8 col-12 offset-md-2">
            <div class="text-center">
                <img loading="lazy" class="img-error" src="{{ asset('assets/images/logo/404.png') }}" width="350px"
                    alt="Not Found" />

                <h1 class="error-title">404 - Page Not Found</h1>
                <p class="fs-5 text-gray-600">
                    Maaf, halaman yang Anda cari tidak ditemukan
                </p>
                <a href="{{ route('admin.home.index') }}" class="btn btn-lg btn-outline-primary mt-3">Ke Halaman Utama</a>
            </div>
        </div>
    </div>
@endsection
