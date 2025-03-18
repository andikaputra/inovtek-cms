@extends('admin.layouts.app')

@section('title', 'Informasi Mitigasi Bencana | Ubah Informasi')

@section('customJs')
    @include('generals._ckeditor')
@endsection

@section('main')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Informasi Mitigasi Bencana - Ubah Informasi</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item text-subtitle"><a
                                    href="{{ route('admin.home.detail.tentang-aplikasi.edit', $findRegion->slug) }}">Mitigasi
                                    Bencana</a>
                            <li class="breadcrumb-item active text-subtitle" aria-current="page">Edit</li>
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
                        {{ $findRegion->province }} Kabupaten/Wilayah {{ $findRegion->regency }} saat ini sedang tidak aktif
                        dan
                        tidak akan muncul pada halaman INOVTEK</small>
                </div>
            @endif
            @include('generals._validation')
            <div class="row">
                <div class="col-md-12 col-12">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <span class="text-danger">* Wajib diisi</span>
                        </div>
                        <div class="card-body">
                            <section id="basic-horizontal-layouts">
                                <form class="form form-horizontal"
                                    action="{{ route('admin.home.detail.tentang-aplikasi.update', $findRegion->slug) }}"
                                    method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-12 col-md-12">
                                                <label>Informasi Mitigasi Bencana<span class="text-danger"> *</span></label>
                                                <div class="form-group has-icon-left">
                                                    <div class="position-relative">
                                                        <textarea rows="5" required class="editor-textarea form-control @error('mitigation') is-invalid @enderror"
                                                            placeholder="Konten" id="mitigation" name="mitigation">{{ old('mitigation') ?? $findDetailInfo?->mitigation }}</textarea>
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
