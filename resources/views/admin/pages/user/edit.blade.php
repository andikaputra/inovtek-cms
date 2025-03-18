@extends('admin.layouts.app')

@section('title', 'Ubah User')

@section('customCss')
    @include('admin.pages.user.css._edit_css')
@endsection

@section('customJs')
    @include('admin.pages.user.js._edit_js')
@endsection

@section('main')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Manajemen User - Ubah User</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item text-subtitle"><a href="{{ route('admin.user.index') }}">Manajemen
                                    User</a>
                            <li class="breadcrumb-item active text-subtitle" aria-current="page">Edit</li>
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
                        <form class="form form-horizontal" action="{{ route('admin.user.update', $user->id) }}"
                            method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <label>Email User<span class="text-danger"> *</span></label>
                                        <div class="form-group has-icon-left">
                                            <div class="position-relative">
                                                <input type="email" required
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    placeholder="Email User" name="email"
                                                    value="{{ old('email') ?? $user->email }}"
                                                    {{ $user->guid_user != null ? 'readonly' : '' }}>
                                                <div class="form-control-icon">
                                                    <i class="bi bi-envelope"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label>Nama User<span class="text-danger"> *</span></label>
                                        <div class="form-group has-icon-left">
                                            <div class="position-relative">
                                                <input type="text" required
                                                    class="form-control @error('name') is-invalid @enderror"
                                                    placeholder="Nama User" name="name"
                                                    value="{{ old('name') ?? $user->name }}">
                                                <div class="form-control-icon">
                                                    <i class="bi bi-person"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if ($user->guid_user == null)
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
                                    @endif
                                    <div class="col-12 col-md-6 mb-3">
                                        <label>Hak Akses<span class="text-danger"> *</span></label>
                                        <select required class="form-control @error('role_access') is-invalid @enderror"
                                            name="role_access" id="select2-role">
                                            <option value="">-- Pilih Hak Akses --</option>
                                            <option value="super_admin" {{ $user->is_super_admin ? 'selected' : '' }}>Super
                                                Admin</option>
                                            <option value="admin" {{ !$user->is_super_admin ? 'selected' : '' }}>Admin
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6 mt-4">
                                        <div class="form-check form-check-lg d-flex align-items-end">
                                            <input class="form-check-input me-2" type="checkbox" name="is_active"
                                                value="active" id="is_active" {{ $user->is_active ? 'checked' : '' }}>
                                            <label class="form-check-label text-gray-600" for="is_active">
                                                Aktifkan User
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
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </section>
    </div>
@endsection
