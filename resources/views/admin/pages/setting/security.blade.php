@extends('admin/layouts/app')

@section('title', 'Pengaturan | Keamanan')

@section('main')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Pengaturan - Keamanan</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item text-subtitle"><a
                                    href="{{ route('admin.setting.security.index') }}">Pengaturan</a>
                            <li class="breadcrumb-item active text-subtitle" aria-current="page">Security</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <!-- Basic Tables start -->
        <section class="section">
            @include('admin.pages.setting.tab._setting_tab')
            @include('generals._validation')
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between">
                    <div class="d-flex d-inline-block">
                        <span class="text-danger">*Wajib diisi</span>
                    </div>
                </div>
                <div class="card-body">
                    <form class="form form-horizontal" enctype="multipart/form-data"
                        action="{{ route('admin.setting.security.update') }}" method="post">
                        @csrf
                        @method('PATCH')
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <label>Email<span class="text-danger">*</span></label>
                                    <div class="form-group has-icon-left">
                                        <div class="position-relative">
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                required placeholder="Email" id="first-name-icon" name="email"
                                                value="{{ old('email') ?? Auth::user()->email }}">
                                            <div class="form-control-icon">
                                                <i class="bi bi-envelope"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label>Password</label>
                                    <div class="form-group has-icon-left">
                                        <div class="position-relative">
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                placeholder="Password" name="password" autocomplete="new-password"
                                                placeholder="Password" />
                                            <div class="form-control-icon">
                                                <i class="bi bi-shield-lock"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label>Konfirmasi Password</label>
                                    <div class="form-group has-icon-left">
                                        <div class="position-relative">
                                            <input type="password"
                                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                                placeholder="Konfirmasi Password" name="password_confirmation"
                                                autocomplete="new-password" placeholder="Password" />
                                            <div class="form-control-icon">
                                                <i class="bi bi-shield-lock"></i>
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
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection
