@extends('admin.layouts.app')

@section('title', 'Dashboard Wilayah | Buat Wilayah')

@section('customCss')
    @include('admin.pages.home.css._edit_css')
    @include('generals._lfm_css')
@endsection

@section('customJs')
    @include('admin.pages.home.js._edit_js')
    @include('generals._lfm_js')
@endsection

@section('main')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Dashboard Wilayah - Ubah Wilayah</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item text-subtitle"><a href="{{ route('admin.home.index') }}">Dashboard
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
                        {{ $findRegion->province }} Kabupaten/Wilayah {{ $findRegion->regency }} saat ini sedang tidak aktif
                        dan
                        tidak akan muncul pada halaman INOVTEK</small>
                </div>
            @endif
            @include('generals._validation')
            <div class="row">
                <div class="col-md-4 col-12">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <span class="text-danger">* Wajib diisi</span>
                        </div>
                        <div class="card-body">
                            <section id="basic-horizontal-layouts">
                                <div class="form-body">
                                    <div class="row p-2">
                                        <div class="col-12 col-md-12">
                                            <div id="holder1" class="preview-images-zone row">
                                                @foreach ($findRegion->assets as $asset)
                                                    <div class="preview-image col-12 col-md-6">
                                                        <img loading="lazy" width="100%" class="img img-responsive"
                                                            src="{{ $asset->asset_path && Storage::disk('public')->exists($asset->asset_path)
                                                                ? asset('storage/' . $asset->asset_path)
                                                                : asset('assets/images/default/no-image.jpg') }}" />
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 col-12">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <span class="text-danger">* Wajib diisi</span>
                        </div>
                        <div class="card-body">
                            <section id="basic-horizontal-layouts">
                                <form class="form form-horizontal"
                                    action="{{ route('admin.home.detail.setting-wilayah.update', $findRegion->slug) }}"
                                    method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="form-body">
                                        <div class="col-md-12 col-12">
                                            <label>Slider Wilayah<span class="text-danger">
                                                    *</span></label>
                                            <div class="input-group">
                                                <span class="input-group-btn">
                                                    <a id="lfm1" data-input="thumbnail1" class="btn btn-primary">
                                                        <i class="bi bi-image"></i> Choose
                                                    </a>
                                                </span>
                                                <input id="thumbnail1"
                                                    class="form-control @error('image') is-invalid @enderror" readonly
                                                    type="text" name="image" value="{{ $findRegion->asset_arr }}">
                                            </div>
                                            <small>Mendukung lebih dari 1 file format Gambar (jpg, jpeg, png, gif, svg,
                                                webp)</small>
                                        </div>
                                        <div class="col-12 col-md-12 mt-2">
                                            <label>Provinsi<span class="text-danger"> *</span></label>
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="text" required
                                                        class="form-control @error('province') is-invalid @enderror"
                                                        placeholder="Provinsi" name="province"
                                                        value="{{ old('province') ?? $findRegion->province }}">
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-envelope"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-12">
                                            <label>Kabupaten/Wilayah<span class="text-danger"> *</span></label>
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="text" required
                                                        class="form-control @error('regency') is-invalid @enderror"
                                                        placeholder="Nama Kabupaten/Wilayah" name="regency"
                                                        value="{{ old('regency') ?? $findRegion->regency }}">
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-person"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-12">
                                            <label>Produk Tersedia</label>
                                            <div class="form-group has-icon-left">
                                                <div class="input-group">
                                                    <select class="form-select" id="select2-produk" name="product[]"
                                                        multiple>
                                                        @foreach ($existingApp as $index => $item)
                                                            <option value="{{ $item->id }}"
                                                                {{ in_array($item->id, $findRegion->existingApps?->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                                {{ $item->display }}
                                                            </option>
                                                        @endforeach
                                                    </select>
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
                    <div class="card shadow-sm border border-danger">
                        <div class="card-header">
                            <p class="text-danger">Zona Sensitif</p>
                            <small>
                                <em>
                                    Area untuk mengatur konfigurasi sensitif,
                                    lakukan
                                    perubahan hanya jika Anda paham dengan setiap resikonya. Setiap tindakan yang
                                    dipilih tidak dapat dibatalkan.
                                </em>
                            </small>
                        </div>
                        <div class="card-body">
                            <section id="basic-horizontal-layouts">
                                <div class="form-body">
                                    <div class="col-12 col-md-12 d-flex justify-content-between">
                                        <div class="col-6 text-start">
                                            <label>Ubah Status Wilayah</label>
                                        </div>
                                        <div class="col-6 text-end">
                                            <div class="form-group has-icon-left">
                                                <form id="change-{{ $findRegion->slug }}"
                                                    action="{{ route('admin.home.detail.setting-wilayah.switch', $findRegion->slug) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button data-id="{{ $findRegion->slug }}" type="button"
                                                        class="main-toggle btn-change-status changeStatus {{ $findRegion->is_active ? 'on' : '' }}"><span></span>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-12 mt-2 d-flex justify-content-between">
                                        <div class="col-6 text-start">
                                            <label>Hapus Data Wilayah</label>
                                        </div>
                                        <div class="col-6 text-end">
                                            <div class="form-group has-icon-left">
                                                <button data-bs-target="#formConfirmDeleteWilayah" data-bs-toggle="modal"
                                                    class="btn btn-danger btn-sm"><i class="bi bi-trash"></i>
                                                    Hapus Wilayah</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @include('admin.pages.home.modal._confirm_delete_modal')
@endsection
