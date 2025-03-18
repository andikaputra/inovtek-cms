@extends('admin.layouts.app')

@section('title', 'Jalur ' . $findDetailRegionMapbox->name . ' | Desa ' . $findDetailRegion->village)

@section('customCss')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endsection

@section('customJs')
    <!-- Leaflet JS -->
    @include('admin.pages.home-desa-jalur-mapbox.js._create_js')
@endsection


@section('main')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Jalur {{ $findDetailRegionMapbox->name }} - Buat Titik</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item text-subtitle"><a
                                    href="{{ route('admin.home.detail.desa.index', $findRegion->slug) }}">Dashboard
                                    Desa</a>
                            <li class="breadcrumb-item text-subtitle"><a
                                    href="{{ route('admin.home.detail.desa.segmentasi-mapbox.index', ['id_provinsi' => $findRegion->slug, 'id_desa' => $findDetailRegion->slug]) }}">Segmentasi
                                    Mapbox</a>
                            <li class="breadcrumb-item text-subtitle"><a
                                    href="{{ route('admin.home.detail.desa.segmentasi-mapbox.jalur.index', ['id_provinsi' => $findRegion->slug, 'id_desa' => $findDetailRegion->slug, 'id_mapbox' => $findDetailRegionMapbox->id]) }}">Titik
                                    Jalur</a>
                            <li class="breadcrumb-item active text-subtitle" aria-current="page">Index</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-12 col-lg-4 col-md-4">
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
                <div class="col-12 col-lg-4 col-md-4">
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
                <div class="col-12 col-lg-4 col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                    <div class="stats-icon red mb-2">
                                        <i class="bi bi-compass"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Nama Desa</h6>
                                    <h4 class="font-extrabold mb-0">{{ $findDetailRegion->village }}</h4>
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
            <div class="card shadow-sm">
                <div class="card-header">
                    <span class="text-danger">* Wajib diisi</span>
                </div>
                <div class="card-body">
                    <section id="basic-horizontal-layouts">
                        <form class="form form-horizontal"
                            action="{{ route('admin.home.detail.desa.segmentasi-mapbox.jalur.store', ['id_provinsi' => $findRegion->slug, 'id_desa' => $findDetailRegion->slug, 'id_mapbox' => $findDetailRegionMapbox->id]) }}"
                            method="POST">
                            @csrf
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-12 col-md-12 mt-2">
                                        <label>Nama Jalur<span class="text-danger"> *</span></label>
                                        <div class="form-group has-icon-left">
                                            <div class="position-relative">
                                                <input type="text" required
                                                    class="form-control @error('name') is-invalid @enderror"
                                                    placeholder="Nama Jalur" name="name"
                                                    value="{{ old('name') ?? null }}">
                                                <div class="form-control-icon">
                                                    <i class="bi bi-cursor"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 mt-2">
                                        <label>Latitude dan Longitude<span class="text-danger"> *</span></label>
                                        <div class="form-group has-icon-left">
                                            <div class="position-relative">
                                                <input type="text" required class="form-control"
                                                    placeholder="Latitude dan Longitude" id="lat_long" name="lat_long">
                                                <div class="form-control-icon">
                                                    <i class="bi bi-geo-alt-fill"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-2 mt-2">
                                        <div class="form-check form-check-lg d-flex align-items-end mt-4">
                                            <input class="form-check-input me-2" type="checkbox" name="is_active"
                                                value="active" id="is_active" checked>
                                            <label class="form-check-label text-gray-600" for="is_active">
                                                Aktifkan Titik
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-12 mb-3 mt-2">
                                        <div id="map"></div>
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-sm btn-primary me-1 mb-1">Simpan
                                        Data</button>
                                    <button type="reset" class="btn btn-sm btn-light-secondary me-1 mb-1">Reset</button>
                                </div>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </section>
    </div>
@endsection
