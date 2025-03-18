@extends('admin.layouts.app')

@section('title', 'Tautan Wilayah | Data Tautan')

@section('customJs')
    @include('admin.pages.home-desa-link.js._index_js')
@endsection

@section('main')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Tautan Wilayah - Data Tautan</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item text-subtitle"><a
                                    href="{{ route('admin.home.detail.link.index', $findRegion->slug) }}">Tautan Wilayah</a>
                            </li>
                            <li class="breadcrumb-item active text-subtitle" aria-current="page">Index</li>
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
            @include('generals.tab._link_sosmed_tab')
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body pt-3 pb-2">
                            <div class="row justify-content-between g-2">
                                <div class="col-auto">
                                    <h6 class="py-0">Total Tautan (<span id="tautan-total"><small>...</small></span>) |
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
                    <div class="card">
                        <div class="card-header">
                            <div class="row justify-content-between g-2">
                                <div class="col-auto">
                                    <a href="{{ route('admin.home.detail.link.create', $findRegion->slug) }}"
                                        class="btn btn-primary btn-sm icon-left">
                                        <i class="bi bi-send-plus"></i> Tambah
                                        Data</a>
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
                                            <th>No</th>
                                            <th>Aksi</th>
                                            <th>Icon</th>
                                            <th>Url</th>
                                            <th>Display</th>
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
            </div>
        </section>
    </div>
@endsection
