@extends('admin.layouts.app')

@section('title', 'Registrasi Kuis | Data Registrasi Kuis')

@section('customCss')
    <link rel="stylesheet" href="{{ asset('assets/extensions/flatpickr/flatpickr.min.css') }}">
    <style>
        .select2-container--default .select2-selection--single {
            height: 36px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px;
        }
    </style>

@endsection

@section('customJs')
    @include('admin.pages.home-desa-registrasi.js._index_js')
@endsection

@section('main')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Registrasi Kuis - Data Registrasi Kuis</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item text-subtitle"><a
                                    href="{{ route('admin.home.detail.kuis.index', ['id_provinsi' => $findRegion->slug]) }}">Kuis
                                    Wilayah</a>
                            </li>
                            <li class="breadcrumb-item text-subtitle"><a
                                    href="{{ route('admin.home.detail.kuis.registrant.index', ['id_provinsi' => $findRegion->slug, 'id' => $findKuis->id]) }}">Registrasi</a>
                            </li>
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
                                        <i class="bi bi-book"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Nama Kuis</h6>
                                    <h4 class="font-extrabold mb-0">{{ Illuminate\Support\Str::limit($findKuis->name, 10) }}
                                    </h4>
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
                            <div class="row justify-content-between g-2">
                                <div class="col-auto">
                                    <h6 class="py-0">Total Registrasi Kuis (<span
                                            id="pendaftaran-total"><small>...</small></span>)
                                    </h6>
                                </div>
                                <div class="col-auto"><a href="javascript:void(0)"><button
                                            class="btn btn-secondary btn-sm reload"><i class="bi bi-arrow-clockwise"></i>
                                            Refresh</button></a></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-12">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <div class="row justify-content-between g-2">
                                <div class="col-auto">
                                    <h6 class="py-0"> <i class="bi bi-funnel"></i> Filter
                                    </h6>
                                </div>
                                <div class="col-auto">
                                    <button type="button" id="filterRegistrasi" class="btn btn-primary btn-sm "><i
                                            class="bi bi-search"></i>
                                        Cari Data</button>
                                    <button type="button" id="resetFilterRegistrasi"
                                        class="btn btn-danger btn-sm d-none"><i class="bi bi-x-lg"></i>
                                        Reset Filter</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <label>Tanggal Pendaftaran</label>
                                    <div class="form-group has-icon-left">
                                        <div class="position-relative">
                                            <input type="date" class="form-control flatpickr-range mb-3"
                                                placeholder="Pilih Tanggal" name="date_range" id="filter-date-range">
                                            <div class="form-control-icon">
                                                <i class="bi bi-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label>Desa/Kelurahan</label>
                                    <div class="form-group has-icon-left">
                                        <div class="input-group">
                                            <select class="form-select" id="select2-region" name="village_id">
                                                <option value="semua"
                                                    {{ request()->village_id == 'semua' ? 'selected' : '' }}>Semua
                                                    Desa/Kelurahan</option>
                                                @foreach ($findAllVillage as $village)
                                                    <option value="{{ $village->id }}"
                                                        {{ request()->village_id == $village->id ? 'selected' : '' }}>
                                                        {{ $village->village }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4" id="parent-status-approval-po">
                                    <label>Cari Data</label>
                                    <input type="text" class="form-control" placeholder="Search" id="filter-search"
                                        name="search" value="{{ request()->search }}">
                                </div>
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
                                    <form
                                        action="{{ route('admin.home.detail.kuis.registrant.export', ['id_provinsi' => $findRegion->slug, 'id' => $findKuis->id]) }}"
                                        method="POST">
                                        @csrf
                                        <input type="hidden" id="export-date-range" name="export_date_range">
                                        <input type="hidden" id="export-village-id" name="export_village_id">
                                        <input type="hidden" id="export-search" name="export_search">
                                        <button type="submit" class="btn btn-success btn-sm icon-left">
                                            <i class="bi bi-file-spreadsheet"></i> Export Excel</button>
                                    </form>
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
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Nama Desa</th>
                                            <th>Tgl Pendaftaran</th>
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
