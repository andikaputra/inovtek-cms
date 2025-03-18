@extends('admin.layouts.app')

@section('title', 'Tautan Wilayah | Ubah Tautan')

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
                    <h3>Tautan Wilayah - Ubah Tautan</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item text-subtitle"><a
                                    href="{{ route('admin.home.detail.link.index', $findRegion->slug) }}">Tautan Wilayah</a>
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
                            action="{{ route('admin.home.detail.link.update.content', ['id_provinsi' => $findRegion->slug, 'id' => $findLink->id]) }}"
                            method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-12 col-md-4">
                                        <div class="row p-2">
                                            <div class="col-12 col-md-12">
                                                <div id="holder1" class="preview-images-zone row">
                                                    <div class="preview-image col-12 col-md-12">
                                                        <img loading="lazy" width="100%" class="img img-responsive"
                                                            src="{{ $findLink->icon && Storage::disk('public')->exists($findLink->icon)
                                                                ? asset('storage/' . $findLink->icon)
                                                                : asset('assets/images/default/no-image.jpg') }}" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-8">
                                        <div class="col-md-12 col-12">
                                            <label>Icon<span class="text-danger">
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
                                                    value="{{ $findLink->icon ? asset('storage/' . $findLink->icon) : null }}">
                                            </div>
                                            <small>Mendukung hanya 1 file format Gambar (jpg, jpeg, png, gif, svg,
                                                webp)</small>
                                        </div>
                                        <div class="col-12 col-md-12 mt-2">
                                            <label>Url<span class="text-danger"> *</span></label>
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="url" required
                                                        class="form-control @error('url') is-invalid @enderror"
                                                        placeholder="Url" value="{{ old('url') ?? $findLink->url }}"
                                                        name="url">
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-link-45deg"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-12">
                                            <label>Display<span class="text-danger"> *</span></label>
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="text" required
                                                        class="form-control @error('display') is-invalid @enderror"
                                                        placeholder="Display"
                                                        value="{{ old('display') ?? $findLink->display }}" name="display">
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-share"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 mt-2">
                                            <div class="form-check form-check-lg d-flex align-items-end">
                                                <input class="form-check-input me-2" type="checkbox" name="is_active"
                                                    value="active" id="is_active"
                                                    {{ $findLink->is_active ? 'checked' : '' }}>
                                                <label class="form-check-label text-gray-600" for="is_active">
                                                    Aktifkan Tautan
                                                </label>
                                            </div>
                                        </div>
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
