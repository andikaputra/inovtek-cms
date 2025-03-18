@extends('admin.layouts.app')

@section('title', 'Dashboard Wilayah | Data Wilayah')

@section('customCss')
    @include('admin.pages.home.css._index_css')
@endsection

@section('customJs')
    @include('admin.pages.home.js._index_js')
@endsection

@section('main')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Dashboard Wilayah - Data Wilayah</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item text-subtitle"><a href="{{ route('admin.home.index') }}">Dashboard
                                    Wilayah</a>
                            <li class="breadcrumb-item active text-subtitle" aria-current="page">Index</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="page-content">
        <div class="row">
            <div class="col-md-12 col-12">
                <div class="card">
                    <div class="card-body pt-3 pb-1">
                        <div class="d-flex justify-content-between flex-wrap">
                            <div>
                                <h6 class="py-0 pt-2 me-5"> Wilayah Tersedia ({{ $countData['all'] }}) | Aktif
                                    ({{ $countData['active'] }}) | Nonaktif ({{ $countData['deactive'] }})
                                </h6>
                            </div>
                            <div>
                                <a href="{{ route('admin.home.index') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-arrow-clockwise"></i> Refresh
                                </a>
                                <a href="{{ route('admin.home.create') }}" class="btn btn-sm btn-success">
                                    <i class="bi bi-plus"></i> Buat Wilayah Baru
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-12">
                <div class="card shadow-sm">
                    <form method="GET" action="{{ route('admin.home.index') }}">
                        <div class="card-header">
                            <div class="row justify-content-between g-2">
                                <div class="col-auto">
                                    <h6 class="py-0"> <i class="bi bi-funnel"></i> Filter
                                    </h6>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary btn-sm "><i class="bi bi-search"></i>
                                        Cari Data</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <label>Status</label>
                                    <div class="form-group has-icon-left">
                                        <div class="input-group">
                                            <select class="form-select" id="select2-filter-status" name="is_active">
                                                <option value="all"
                                                    {{ request()->is_active == 'all' ? 'selected' : '' }}>Semua Status
                                                </option>
                                                <option value="aktif"
                                                    {{ request()->is_active == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                                <option value="nonaktif"
                                                    {{ request()->is_active == 'nonaktif' ? 'selected' : '' }}>Non Aktif
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label>Produk</label>
                                    <div class="form-group has-icon-left">
                                        <div class="input-group">
                                            <select class="form-select" id="select2-filter-produk" name="existing_app">
                                                <option value="">Semua Produk</option>
                                                @foreach ($existingApp as $index => $item)
                                                    <option value="{{ $item->code }}"
                                                        {{ request()->existing_app == $item->code ? 'selected' : '' }}>
                                                        {{ $item->display }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4" id="parent-status-approval-po">
                                    <label>Cari Nama Wilayah</label>
                                    <input type="text" class="form-control" placeholder="Search" id="search"
                                        name="search" value="{{ request()->search }}">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-12 col-12">
                <div class="row">
                    {{-- card web list --}}
                    @forelse ($getAllRegion as $index=>$item)
                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12 mt-3">
                            <a href="{{ route('admin.home.detail.desa.index', $item->slug) }}"
                                class="card position-relative">
                                <div class="card-content">
                                    <img loading="lazy"
                                        src="{{ $item->assets[0]?->asset_path && Storage::disk('public')->exists($item->assets[0]?->asset_path)
                                            ? asset('storage/' . $item->assets[0]?->asset_path)
                                            : asset('assets/images/default/no-image.jpg') }}"
                                        class="card-img-top img-fluid" id="custom-all-wilayah" alt="Cover Image">
                                    <div class="card-body">
                                        <h5 class="card-title text-primary">Provinsi {{ $item->province }}</h5>
                                        <p class="card-text">
                                            @if ($item->existingApps && $item->existingApps->isNotEmpty())
                                                @foreach ($item->existingApps as $indexExistingApp => $existingAppItem)
                                                    <span class="badge bg-primary">{{ $existingAppItem->display }}</span>
                                                @endforeach
                                            @endif
                                        </p>
                                        <small class="card-text">
                                            Kabupaten/Wilayah {{ $item->regency }}
                                        </small>
                                    </div>
                                </div>

                                <!-- Overlay -->
                                @if (!$item->is_active)
                                    <!-- Cek jika item nonaktif -->
                                    <div
                                        class="overlay position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-flex justify-content-center align-items-center">
                                        <span class="text-white fw-bold">Nonaktif</span>
                                    </div>
                                @endif
                            </a>

                        </div>
                        {{ $getAllRegion->links() }}
                    @empty
                        <div class="col-md-12 mt-5">
                            {{-- image --}}
                            <div class="text-center">
                                <img loading="lazy" src="{{ asset('assets/images/default/box.png') }}" alt="empty"
                                    class="img-fluid empty" width="300">
                            </div>
                            {{-- message --}}
                            <div class="text-center">
                                <h5 class="mt-3">Belum Ada Wilayah Yang Ditambahkan
                                </h5>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
