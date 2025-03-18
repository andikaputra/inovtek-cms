@extends('admin.layouts.app')

@section('title', 'Informasi Produk | Ubah Produk')

@section('main')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Informasi Produk - Ubah Informasi</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item text-subtitle"><a
                                    href="{{ route('admin.tentang-aplikasi.edit') }}">Informasi
                                    Produk</a>
                            <li class="breadcrumb-item active text-subtitle" aria-current="page">Edit</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            @include('generals._validation')
            <div class="row">
                <div class="col-md-12 col-12">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="card-title">Produk 360 VR Tour</h5>
                            <span class="text-danger">* Wajib diisi</span>
                        </div>
                        <div class="card-body">
                            <section id="basic-horizontal-layouts">
                                <form class="form form-horizontal" action="{{ route('admin.tentang-aplikasi.update') }}"
                                    method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-12 col-md-12 mt-2">
                                                <label>Link Video Iframe Pengenalan<span class="text-danger">
                                                        *</span></label>
                                                <div class="form-group has-icon-left">
                                                    <div class="position-relative">
                                                        <input type="url" required
                                                            class="form-control @error('intro_video_url') is-invalid @enderror"
                                                            placeholder="Link Video Iframe Pengenalan"
                                                            value="{{ old('intro_video_url') ?? $vr360Tour->existingAppInfo?->intro_video_url }}"
                                                            name="intro_video_url">
                                                        <div class="form-control-icon">
                                                            <i class="bi bi-braces"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-12 mt-2">
                                                <label>Link Video Tutorial<span class="text-danger"> *</span></label>
                                                <div class="form-group has-icon-left">
                                                    <div class="position-relative">
                                                        <input type="url" required
                                                            class="form-control @error('tutorial_video_url') is-invalid @enderror"
                                                            placeholder="Link Video Tutorial"
                                                            value="{{ old('tutorial_video_url') ?? $vr360Tour->existingAppInfo?->tutorial_video_url }}"
                                                            name="tutorial_video_url">
                                                        <div class="form-control-icon">
                                                            <i class="bi bi-braces"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-sm btn-primary me-1 mb-1">Simpan
                                                Data</button>
                                            <button type="reset"
                                                class="btn btn-sm btn-light-secondary me-1 mb-1">Reset</button>
                                        </div>
                                    </div>
                                </form>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
