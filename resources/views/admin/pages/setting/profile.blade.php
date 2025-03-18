@extends('admin/layouts/app')

@section('title', 'Pengaturan | Profil')

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
                    <h3>Pengaturan - Profil</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item text-subtitle"><a
                                    href="{{ route('admin.setting.profile.index') }}">Pengaturan</a>
                            <li class="breadcrumb-item active text-subtitle" aria-current="page">Profile</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <!-- Basic Tables start -->
        <section class="section">
            @include('admin.pages.setting.tab._setting_tab')
            @include('generals._validation')
            <div class="row">
                <div class="col-12 col-md-5">
                    <div class="card shadow-sm">
                        <div class="card-header">
                        </div>
                        <div class="card-body">
                            <section id="basic-horizontal-layouts">
                                <div id="holder1" class="preview-images-zone row">
                                    <div class="preview-image col-12 col-md-12">
                                        <img loading="lazy" width="100%" class="img img-responsive"
                                            src="{{ $userProfileImage && Storage::disk('public')->exists($userProfileImage)
                                                ? asset('storage/' . $userProfileImage)
                                                : 'https://ui-avatars.com/api/?name=' . Auth::user()->username . '&background=19816D&color=fff' }}" />
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-7">
                    <div class="card shadow-sm ">
                        <div class="card-header">
                            <span class="text-danger">* Wajib diisi</span>
                        </div>
                        <div class="card-body">
                            <section id="basic-horizontal-layouts">
                                <form class="form form-horizontal" enctype="multipart/form-data"
                                    action="{{ route('admin.setting.profile.update') }}" method="post">
                                    @csrf
                                    @method('PATCH')
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label>Foto Profil</label>
                                                <div class="input-group">
                                                    <span class="input-group-btn">
                                                        <a id="lfm1" data-input="thumbnail1" class="btn btn-primary">
                                                            <i class="bi bi-image"></i> Choose
                                                        </a>
                                                    </span>
                                                    <input id="thumbnail1"
                                                        class="form-control @error('image') is-invalid @enderror" readonly
                                                        type="text" value="{{ $userProfileImage ?? null }}"
                                                        name="image">
                                                </div>
                                            </div>
                                            <div class="col-md-12 mb-2">
                                                <label>Username<span class="text-danger">*</span></label>
                                                <div class="form-group has-icon-left">
                                                    <div class="position-relative">
                                                        <input type="text" disabled class="form-control" required
                                                            placeholder="Kantor" id="first-name-icon"
                                                            value="{{ Auth::user()->username ?? null }}">
                                                        <div class="form-control-icon">
                                                            <i class="bi bi-key"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mb-2">
                                                <label>Nama<span class="text-danger">*</span></label>
                                                <div class="form-group has-icon-left">
                                                    <div class="position-relative">
                                                        <input type="text"
                                                            class="form-control @error('name') is-invalid @enderror"
                                                            required placeholder="Nama" id="first-name-icon" name="name"
                                                            value="{{ old('name') ?? Auth::user()->name }}">
                                                        <div class="form-control-icon">
                                                            <i class="bi bi-person"></i>
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
                </div>
            </div>
        </section>
    </div>

@endsection
