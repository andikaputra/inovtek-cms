@extends('admin.layouts.app')

@section('title', 'Buat Short Link')

@section('main')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Manajemen Short Link - Buat Short Link</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item text-subtitle"><a
                                    href="{{ route('admin.short-link.index') }}">Manajemen
                                    Short Link</a>
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
                        <form class="form form-horizontal" action="{{ route('admin.short-link.store') }}" method="POST">
                            @csrf
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-12 col-md-12">
                                        <label>Original Link<span class="text-danger"> *</span></label>
                                        <div class="form-group has-icon-left">
                                            <div class="position-relative">
                                                <input type="url" required
                                                    class="form-control @error('original_url') is-invalid @enderror"
                                                    placeholder="Original Link" name="original_url"
                                                    value="{{ old('original_url') }}">
                                                <div class="form-control-icon">
                                                    <i class="bi bi-link-45deg"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-check form-check-lg d-flex align-items-end">
                                            <input class="form-check-input me-2" type="checkbox" name="is_active"
                                                value="active" id="is_active" checked>
                                            <label class="form-check-label text-gray-600" for="is_active">
                                                Aktifkan Short Link
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
