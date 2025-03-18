@extends('admin.layouts.app')

@section('title', 'Dashboard Wilayah | Buat Wilayah')

@section('customCss')
    @include('admin.pages.home.css._create_css')
    @include('generals._lfm_css')
@endsection

@section('customJs')
    @include('admin.pages.home.js._create_js')
    @include('generals._lfm_js')
@endsection

@section('main')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Dashboard Wilayah - Buat Wilayah</h3>
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
            @include('generals._validation')
            <div class="card shadow-sm">
                <div class="card-header">
                    <span class="text-danger">* Wajib diisi</span>
                </div>
                <div class="card-body">
                    <section id="basic-horizontal-layouts">
                        <form class="form form-horizontal" action="{{ route('admin.home.store') }}" method="POST">
                            @csrf
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-4 col-12">
                                        <div class="row p-2">
                                            <div class="col-12 col-md-12">
                                                <div id="holder1" class="preview-images-zone row">
                                                    <div class="preview-image col-12 col-md-12">
                                                        <img loading="lazy" width="100%" class="img img-responsive"
                                                            src="{{ asset('assets/images/default/no-image.jpg') }}" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-12">
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
                                                    type="text" name="image">
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
                                                        placeholder="Provinsi" value="{{ old('province') ?? null }}"
                                                        name="province">
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
                                                        placeholder="Nama Kabupaten/Wilayah"
                                                        value="{{ old('regency') ?? null }}" name="regency">
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
                                                            <option value="{{ $item->id }}">{{ $item->display }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 mt-2">
                                            <div class="form-check form-check-lg d-flex align-items-end">
                                                <input class="form-check-input me-2" type="checkbox" name="is_active"
                                                    value="active" id="is_active" checked>
                                                <label class="form-check-label text-gray-600" for="is_active">
                                                    Aktifkan Wilayah
                                                </label>
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
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </section>
    </div>
@endsection
