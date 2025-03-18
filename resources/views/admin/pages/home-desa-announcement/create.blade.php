@extends('admin.layouts.app')

@section('title', 'Pengumuman | Buat Pengumuman')

@section('customCss')
    @include('generals._lfm_css')
@endsection

@section('customJs')
    @include('generals._lfm_js')
    @include('generals._ckeditor')
@endsection

@section('main')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Pengumuman - Buat Pengumuman</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item text-subtitle"><a
                                    href="{{ route('admin.home.detail.pengumuman.index', ['id_provinsi' => $findRegion->slug]) }}">Pengumuman
                                    Wilayah</a>
                            <li class="breadcrumb-item active text-subtitle" aria-current="page">Create</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-12 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                    <div class="stats-icon purple mb-2">
                                        <i class="bi bi-globe-asia-australia"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Nama Provinsi</h6>
                                    <h4 class="font-extrabold mb-0">{{ $findRegion->province }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                    <div class="stats-icon blue mb-2">
                                        <i class="bi bi-geo-alt"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Nama Kabupaten/Wilayah</h6>
                                    <h4 class="font-extrabold mb-0">{{ $findRegion->regency }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if (!$findRegion->is_active)
                <div class="alert alert-warning text-white"><i class="bi bi-info-circle"></i> <small>Provinsi
                        {{ $findRegion->province }} Kabupaten/Wilayah {{ $findRegion->regency }} saat ini sedang
                        tidak aktif
                        dan
                        tidak akan muncul pada halaman INOVTEK</small>
                </div>
            @endif
            @include('generals._validation')
            <form class="form form-horizontal"
                action="{{ route('admin.home.detail.pengumuman.store', ['id_provinsi' => $findRegion->slug]) }}"
                method="POST">
                @csrf
                <div class="row">
                    <div class="col-12 col-md-12">
                        <div class="card shadow-sm">
                            <div class="card-header">
                                <span class="text-danger">* Wajib diisi</span>
                            </div>
                            <div class="card-body">
                                <section id="basic-horizontal-layouts">
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-12 col-md-12 mt-2">
                                                <label>Nama Pengumuman<span class="text-danger"> *</span></label>
                                                <div class="form-group has-icon-left">
                                                    <div class="position-relative">
                                                        <input type="text" required
                                                            class="form-control @error('name') is-invalid @enderror"
                                                            placeholder="Judul" value="{{ old('name') ?? null }}"
                                                            name="name">
                                                        <div class="form-control-icon">
                                                            <i class="bi bi-braces"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-12 mt-2">
                                                <label>Tautan Pengumuman<span class="text-danger"> *</span></label>
                                                <div class="form-group has-icon-left">
                                                    <div class="position-relative">
                                                        <input type="url" required
                                                            class="form-control @error('announcement_link') is-invalid @enderror"
                                                            placeholder="Tautan Pengumuman"
                                                            value="{{ old('announcement_link') ?? null }}"
                                                            name="announcement_link">
                                                        <div class="form-control-icon">
                                                            <i class="bi bi-link-45deg"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @if (!$checkExistActivePengumuman)
                                                <div class="col-4 col-md-2 mt-2">
                                                    <div class="form-check form-check-lg d-flex align-items-end">
                                                        <input class="form-check-input me-2" type="checkbox"
                                                            name="is_active" value="active" id="is_active"
                                                            {{ !$checkExistActivePengumuman ? 'checked' : '' }}>
                                                        <label class="form-check-label text-gray-600" for="is_active">
                                                            Aktifkan Pengumuman
                                                        </label>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="col-12 d-flex justify-content-end mt-3">
                                                <button type="submit" class="btn btn-sm btn-primary me-1 mb-1">Simpan
                                                    Data</button>
                                                <button type="reset"
                                                    class="btn btn-sm btn-light-secondary me-1 mb-1">Reset</button>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>
@endsection
