@extends('admin.layouts.app')

@section('title', 'Artikel Wilayah | Buat Artikel')

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
                    <h3>Artikel Wilayah - Buat Artikel</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item text-subtitle"><a
                                    href="{{ route('admin.home.detail.blog.index', ['id_provinsi' => $findRegion->slug]) }}">Artikel
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
                action="{{ route('admin.home.detail.blog.store', ['id_provinsi' => $findRegion->slug]) }}" method="POST">
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
                                            <div class="col-12 col-md-12">
                                                <div class="border">
                                                    <div class="row p-2">
                                                        <label>Gambar<span class="text-danger"> *</span></label>
                                                        <small>Mendukung lebih dari 1 file format Gambar</small>
                                                        <div class="col-12 col-md-7 mb-2">
                                                            <div class="input-group">
                                                                <span class="input-group-btn">
                                                                    <a id="lfm1" data-input="thumbnail1"
                                                                        class="btn btn-primary">
                                                                        <i class="bi bi-image"></i> Choose
                                                                    </a>
                                                                </span>
                                                                <input id="thumbnail1"
                                                                    class="form-control @error('image') is-invalid @enderror"
                                                                    readonly type="text" name="image">
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-md-5 px-4">
                                                            <div id="holder1" class="preview-images-zone row">
                                                                <div class="preview-image col-12 col-md-12">
                                                                    <img loading="lazy" width="100%"
                                                                        class="img img-responsive"
                                                                        src="{{ asset('assets/images/default/no-image.jpg') }}" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6 mt-2">
                                                <label>Judul<span class="text-danger"> *</span></label>
                                                <div class="form-group has-icon-left">
                                                    <div class="position-relative">
                                                        <input type="text" required
                                                            class="form-control @error('title') is-invalid @enderror"
                                                            placeholder="Judul" value="{{ old('title') ?? null }}"
                                                            name="title">
                                                        <div class="form-control-icon">
                                                            <i class="bi bi-braces"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6 mt-2">
                                                <label>Sub Judul</label>
                                                <div class="form-group has-icon-left">
                                                    <div class="position-relative">
                                                        <input type="text"
                                                            class="form-control @error('sub_title') is-invalid @enderror"
                                                            placeholder="Sub Judul" value="{{ old('sub_title') ?? null }}"
                                                            name="sub_title">
                                                        <div class="form-control-icon">
                                                            <i class="bi bi-braces"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-12">
                                                <label>Konten<span class="text-danger"> *</span></label>
                                                <div class="form-group has-icon-left">
                                                    <div class="position-relative">
                                                        <textarea rows="5" required class="editor-textarea form-control @error('content') is-invalid @enderror"
                                                            placeholder="Konten" id="content" name="content">{{ old('content') ?? null }}</textarea>
                                                        <div class="form-control-icon">
                                                            <i class="bi bi-braces"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-4 col-md-2 mt-2">
                                                <div class="form-check form-check-lg d-flex align-items-end">
                                                    <input class="form-check-input me-2" type="checkbox" name="is_active"
                                                        value="active" id="is_active" checked>
                                                    <label class="form-check-label text-gray-600" for="is_active">
                                                        Publish Blog
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-4 col-md-4 mt-2">
                                                <div class="form-check form-check-lg d-flex align-items-end">
                                                    <input class="form-check-input me-2" type="checkbox"
                                                        name="is_general_blog" value="active" id="is_general_blog">
                                                    <label class="form-check-label text-gray-600" for="is_general_blog">
                                                        Tampilkan Di Tentang Inovtek
                                                    </label>
                                                </div>
                                            </div>
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
