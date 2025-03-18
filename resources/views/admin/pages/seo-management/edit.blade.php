@extends('admin.layouts.app')

@section('title', 'Pengaturan SEO | Ubah SEO')

@section('main')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Pengaturan SEO - Ubah SEO</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item text-subtitle"><a
                                    href="{{ route('admin.home.detail.seo-wilayah.edit', ['id_provinsi' => $findRegion->slug, 'type' => $type, 'id_key' => $id]) }}">Pengaturan
                                    SEO</a>
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
                            action="{{ route('admin.home.detail.seo-wilayah.update', ['id_provinsi' => $findRegion->slug, 'type' => $type, 'id_key' => $id]) }}"
                            method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-4 col-12">
                                        <label>Title<span class="text-danger"> *</span></label>
                                        <div class="form-group has-icon-left">
                                            <div class="position-relative">
                                                <input type="text" required
                                                    class="form-control @error('meta_title') is-invalid @enderror"
                                                    placeholder="Meta title" value="{{ $seo->meta_title ?? '' }}"
                                                    name="meta_title">
                                                <div class="form-control-icon">
                                                    <i class="bi bi-link"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label>Meta Author<span class="text-danger"> *</span></label>
                                        <div class="form-group has-icon-left">
                                            <div class="position-relative">
                                                <input type="text" required
                                                    class="form-control @error('meta_author') is-invalid @enderror"
                                                    placeholder="Meta Author" value="{{ $seo->meta_author ?? '' }}"
                                                    name="meta_author">
                                                <div class="form-control-icon">
                                                    <i class="bi bi-link"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label>Meta Keyword<span class="text-danger"> *</span></label>
                                        <div class="form-group has-icon-left">
                                            <div class="position-relative">
                                                <input type="text" required
                                                    class="form-control @error('meta_keyword') is-invalid @enderror"
                                                    placeholder="Meta Keyword" value="{{ $seo->meta_keyword ?? '' }}"
                                                    name="meta_keyword">
                                                <div class="form-control-icon">
                                                    <i class="bi bi-link"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label>Meta Language<span class="text-danger"> *</span></label>
                                        <div class="form-group has-icon-left">
                                            <div class="position-relative">
                                                <input type="text" required
                                                    class="form-control @error('meta_language') is-invalid @enderror"
                                                    placeholder="Meta Language" value="{{ $seo->meta_language ?? '' }}"
                                                    name="meta_language">
                                                <div class="form-control-icon">
                                                    <i class="bi bi-link"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label>Meta Robot<span class="text-danger"> *</span></label>
                                        <div class="form-group has-icon-left">
                                            <div class="position-relative">
                                                <input type="text" required
                                                    class="form-control @error('meta_robot') is-invalid @enderror"
                                                    placeholder="Meta Robot" value="{{ $seo->meta_robot ?? '' }}"
                                                    name="meta_robot">
                                                <div class="form-control-icon">
                                                    <i class="bi bi-robot"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label>Meta Description<span class="text-danger"> *</span></label>
                                        <div class="form-group has-icon-left">
                                            <div class="position-relative">
                                                <textarea class="form-control @error('meta_description') is-invalid @enderror" placeholder="Meta Description" required
                                                    rows="5" name="meta_description">{{ $seo->meta_description ?? '' }}</textarea>
                                                <div class="form-control-icon">
                                                    <i class="bi bi-link"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label>Meta Og Title</label>
                                        <div class="form-group has-icon-left">
                                            <div class="position-relative">
                                                <input type="text"
                                                    class="form-control @error('meta_og_title') is-invalid @enderror"
                                                    placeholder="Meta Og Title" value="{{ $seo->meta_og_title ?? '' }}"
                                                    name="meta_og_title">
                                                <div class="form-control-icon">
                                                    <i class="bi bi-link"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label>Meta Og Type</label>
                                        <div class="form-group has-icon-left">
                                            <div class="position-relative">
                                                <select class="form-control @error('meta_og_type') is-invalid @enderror"
                                                    name="meta_og_type">
                                                    @foreach (App\Constants\SeoConst::SEO_TYPE_MAP as $index => $item)
                                                        <option value="{{ $index }}"
                                                            {{ isset($seo->meta_og_type) ? ($seo->meta_og_type == $index ? 'selected' : '') : '' }}>
                                                            {{ $item }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="form-control-icon">
                                                    <i class="bi bi-link"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label>Meta Og Url</label>
                                        <div class="form-group has-icon-left">
                                            <div class="position-relative">
                                                <input
                                                    type="url"class="form-control @error('meta_og_url') is-invalid @enderror"
                                                    placeholder="Meta Og Url" value="{{ $seo->meta_og_url ?? '' }}"
                                                    name="meta_og_url">
                                                <div class="form-control-icon">
                                                    <i class="bi bi-link"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label>Meta Og Description</label>
                                        <div class="form-group has-icon-left">
                                            <div class="position-relative">
                                                <textarea class="form-control @error('meta_og_description') is-invalid @enderror" placeholder="Meta Og Description"
                                                    rows="5" name="meta_og_description">{{ $seo->meta_og_description ?? '' }}</textarea>
                                                <div class="form-control-icon">
                                                    <i class="bi bi-link"></i>
                                                </div>
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
