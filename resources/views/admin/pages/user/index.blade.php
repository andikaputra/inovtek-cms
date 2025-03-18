@extends('admin.layouts.app')

@section('title', 'User')

@section('customJs')
    @include('admin.pages.user.js._index_js')
@endsection

@section('main')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Manajemen User - Data User</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item text-subtitle"><a href="{{ route('admin.user.index') }}">Manajemen
                                    User</a>
                            </li>
                            <li class="breadcrumb-item active text-subtitle" aria-current="page">Index</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body pt-3 pb-2">
                            <div class="row justify-content-between   g-2">
                                <div class="col-auto">
                                    <h6 class="py-0">Total User (<span id="user-total"><small>...</small></span>) | Aktif
                                        (<span id="active-total"><small>...</small></span>) | Non Aktif
                                        (<span id="inactive-total"><small>...</small></span>)</h6>
                                </div>
                                <div class="col-auto"><a href="javascript:void(0)"><button
                                            class="btn btn-secondary btn-sm reload"><i class="bi bi-arrow-clockwise"></i>
                                            Refresh</button></a></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    @include('generals._validation')
                    <div class="card">
                        <div class="card-header">
                            <div class="row justify-content-between g-2">
                                @if (Auth::user()->is_default == true && Auth::user()->is_super_admin == true)
                                    <div class="col-auto">
                                        <a href="{{ route('admin.user.create') }}" class="btn btn-primary btn-sm icon-left">
                                            <i class="bi bi-send-plus"></i> Tambah
                                            Data</a>
                                    </div>
                                    <div class="col-auto">
                                    </div>
                                @endif

                            </div>
                            <div class="d-flex d-inline-block ">

                            </div>

                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Aksi</th>
                                            <th>Username</th>
                                            <th>Nama User</th>
                                            <th>Email</th>
                                            <th>Role Access</th>
                                            <th>Status</th>
                                            <th>Sinkronasi</th>
                                            <th>Diubah terakhir</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection
