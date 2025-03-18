@extends('admin.layouts.app')

@section('title', 'Segmentasi Mapbox | Desa ' . $findDetailRegion->village)

@section('customCss')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    {{-- Dragula Drag and Drop --}}
    <link rel="stylesheet" href="{{ asset('assets/extensions/dragula/dragula.min.css') }}">
    <link rel="stylesheet" crossorigin href="{{ asset('assets/css/widgets/ui-widgets-todolist.css') }}">

@endsection

@section('customJs')
    @include('admin.pages.home-desa-segmentasi-mapbox.js._index_js')
@endsection

@section('main')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Segmentasi Mapbox - Data Mapbox</h3>
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
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body pt-3 pb-2">
                            <div class="row justify-content-between   g-2">
                                <div class="col-auto">
                                    <h6 class="py-0">Total Mapbox (<span id="desa-total"><small>...</small></span>) |
                                        Aktif
                                        (<span id="active-total"><small>...</small></span>) | Non Aktif
                                        (<span id="inactive-total"><small>...</small></span>)</h6>
                                </div>
                                <div class="col-auto"><a href="javascript:void(0)"><button
                                            class="btn btn-secondary btn-sm reload"><i class="bi bi-arrow-clockwise"></i>
                                            Refresh</button></a></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    @if (!$findRegion->is_active)
                        <div class="alert alert-warning text-white"><i class="bi bi-info-circle"></i> <small>Provinsi
                                {{ $findRegion->province }} Kabupaten/Wilayah {{ $findRegion->regency }} saat ini sedang
                                tidak aktif
                                dan
                                tidak akan muncul pada halaman INOVTEK</small>
                        </div>
                    @endif
                    @include('generals._validation')
                    <div class="row">
                        <div class="col-md-7 col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row justify-content-between g-2">
                                        <div class="col-auto">
                                            <a href="{{ route('admin.home.detail.desa.segmentasi-mapbox.create', ['id_provinsi' => $findRegion->slug, 'id_desa' => $findDetailRegion->slug]) }}"
                                                class="btn btn-primary btn-sm icon-left">
                                                <i class="bi bi-send-plus"></i> Tambah
                                                Data</a>
                                            <a href="javascript:void(0)" class="btn btn-warning text-white btn-sm icon-left"
                                                data-bs-toggle="modal" id="loadDataMapboxButton"
                                                data-url_mapbox="{{ route('admin.home.detail.desa.segmentasi-mapbox.order-list', ['id_provinsi' => $findRegion->slug, 'id_desa' => $findDetailRegion->slug]) }}"
                                                data-bs-target="#modalSesuaikanRute">
                                                <i class="bi bi-list-ol"></i> Sesuaikan Rute</a>
                                        </div>
                                        <div class="col-auto">
                                        </div>
                                    </div>
                                    <div class="d-flex d-inline-block ">

                                    </div>

                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped" id="dataTable">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>No</th>
                                                    <th>Aksi</th>
                                                    <th>Nama</th>
                                                    <th>Status</th>
                                                    <th>Diubah terakhir</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="col-12 col-md-12 mb-3">
                                        <div id="map"></div>
                                        <small><em>Preview map diperbaharui setiap
                                                {{ \App\Constants\AppConst::MAP_REFRESH_LOAD_MINUTE }} menit, akan
                                                diperbaharui lagi pada
                                                {{ $next_update }}</em></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @include('admin.pages.home-desa-segmentasi-mapbox.modal._sesuaikan_rute_modal')
@endsection
