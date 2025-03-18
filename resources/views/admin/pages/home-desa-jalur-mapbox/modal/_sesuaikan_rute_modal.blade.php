<div class="modal fade text-left" id="modalSesuaikanRute" tabindex="-1" role="dialog" aria-labelledby="modalSesuaikanRute"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <form class="form form-horizontal"
                action="{{ route('admin.home.detail.desa.segmentasi-mapbox.jalur.order-update', ['id_provinsi' => $findRegion->slug, 'id_desa' => $findDetailRegion->slug, 'id_mapbox' => $findDetailRegionMapbox->id]) }}"
                method="POST">
                @csrf
                <input type="hidden" name="route_order" id="route_order" value="">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSesuaikanRute">
                        Sesuaikan Urutan Rute Aktif - Jalur {{ $findDetailRegionMapbox->name }}
                    </h5>
                    <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body overflow-auto rute-modal">
                    <!-- Spinner untuk Loading -->
                    <div id="loading-spinner" class="text-center my-3 d-none">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <!-- Daftar Item -->
                    <ul class="widget-todo-list-wrapper" id="widget-todo-list"></ul>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-success me-1 mb-1 d-none" id="simpan-rute">Simpan
                        Rute</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Tutup</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
