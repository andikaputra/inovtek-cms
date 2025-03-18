@extends('errors.layouts.app')

@section('title', 'Akun Dibatasi')

@section('content')
    <div class="error-page container">
        <div class="col-md-8 col-12 offset-md-2">
            <div class="text-center">
                <img loading="lazy" class="img-error" src="{{ asset('assets/images/logo/disabled.png') }}" width="350px"
                    alt="Not Found" />
                <h1 class="error-title">Akun Dibatasi</h1>
                <p class="fs-5 text-gray-600">
                    Kami menonaktifkan akun Anda karena terindikasi aktivitas mencurigakan. <br>
                    Hubungi administrator
                    aplikasi
                </p>
                <a href="#" id="logout-link" class="btn btn-lg btn-outline-primary mt-3">Keluar</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </div>
@endsection
