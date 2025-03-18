@extends('admin.layouts.app')

@section('title', 'Galeri Wilayah | Ubah Galeri')

@section('customCss')
    @include('generals._lfm_css')
@endsection

@section('customJs')
    @include('generals._lfm_js')
@endsection

@section('main')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Galeri Wilayah - Ubah Galeri</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item text-subtitle"><a
                                    href="{{ route('admin.home.detail.galeri-wilayah.edit', $findRegion->slug) }}">Galeri
                                    Wilayah</a>
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
                <div class="col-md-6 col-12">
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
                                                @if (isset($findGallery->assets))
                                                    @forelse ($findGallery->assets as $asset)
                                                        <div class="preview-image col-12 col-md-6">
                                                            <img loading="lazy" width="100%" class="img img-responsive"
                                                                src="{{ $asset->asset_path && Storage::disk('public')->exists($asset->asset_path)
                                                                    ? asset('storage/' . $asset->asset_path)
                                                                    : asset('assets/images/default/no-image.jpg') }}" />
                                                        </div>
                                                    @empty
                                                        <div class="preview-image col-12 col-md-6">
                                                            <img loading="lazy" width="100%" class="img img-responsive"
                                                                src="{{ asset('assets/images/default/no-image.jpg') }}" />
                                                        </div>
                                                    @endforelse
                                                @else
                                                    <div class="preview-image col-12 col-md-6">
                                                        <img loading="lazy" width="100%" class="img img-responsive"
                                                            src="{{ asset('assets/images/default/no-image.jpg') }}" />
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <span class="text-danger">* Wajib diisi</span>
                        </div>
                        <div class="card-body">
                            <section id="basic-horizontal-layouts">
                                <form class="form form-horizontal"
                                    action="{{ route('admin.home.detail.galeri-wilayah.update', $findRegion->slug) }}"
                                    method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="form-body">
                                        <div class="col-md-12 col-12">
                                            <label>Galeri Wilayah<span class="text-danger">
                                                    *</span></label>
                                            <div class="input-group">
                                                <span class="input-group-btn">
                                                    <a id="lfm1" data-input="thumbnail1" class="btn btn-primary">
                                                        <i class="bi bi-image"></i> Choose
                                                    </a>
                                                </span>
                                                <input id="thumbnail1"
                                                    class="form-control @error('image') is-invalid @enderror" readonly
                                                    type="text" name="image"
                                                    value="{{ $findGallery->asset_arr ?? null }}">
                                            </div>
                                            <small>Mendukung lebih dari 1 file format Gambar (jpg, jpeg, png, gif, svg,
                                                webp), untuk hasil yang optimal mohon masukkan gambar minimal 10
                                                Gambar</small>
                                        </div>
                                        <div class="col-6 col-md-6 mt-2">
                                            <div class="form-check form-check-lg d-flex align-items-end">
                                                <input class="form-check-input me-2" type="checkbox" name="is_active"
                                                    value="active" id="is_active"
                                                    {{ isset($findGallery) ? ($findGallery->is_active ? 'checked' : '') : 'checked' }}>
                                                <label class="form-check-label text-gray-600" for="is_active">
                                                    Aktifkan Galeri
                                                </label>
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
