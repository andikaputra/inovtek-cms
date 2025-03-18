@extends('admin.layouts.app')

@section('title', 'Artikel Umum | Buat Artikel')

@section('customCss')
    @include('admin.pages.blog.css._create_css')
@endsection

@section('customJs')
    @include('admin.pages.blog.js._create_js')
    @include('generals._ckeditor')
@endsection

@section('main')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Artikel Umum - Buat Artikel</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item text-subtitle"><a href="{{ route('admin.blog.index') }}">Artikel
                                    Umum</a>
                            <li class="breadcrumb-item active text-subtitle" aria-current="page">Create</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            @include('generals._validation')
            <form class="form form-horizontal" action="{{ route('admin.blog.store') }}" method="POST">
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
                                            <div class="col-12 col-md-12">
                                                <label>Tampilkan Sebagai Artikel Wilayah</label>
                                                <div class="form-group has-icon-left">
                                                    <div class="input-group">
                                                        <select class="form-select" id="select2-region" name="region_id[]"
                                                            multiple>
                                                            @foreach ($regions as $index => $item)
                                                                <option value="{{ $item->id }}">Provinsi
                                                                    {{ $item->province }} Kabupaten/Wilayah
                                                                    {{ $item->regency }}
                                                                </option>
                                                            @endforeach
                                                        </select>
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
