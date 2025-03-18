<div class="modal fade text-left" id="formConfirmDeleteWilayah" tabindex="-1" role="dialog"
    aria-labelledby="formConfirmDeleteWilayah" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <form class="form form-horizontal"
                action="{{ route('admin.home.detail.setting-wilayah.delete', $findRegion->slug) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title" id="formConfirmDeleteWilayah">
                        Konfirmasi Hapus Wilayah
                    </h5>
                    <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 col-12">
                            <div class="form-body">
                                <div class="col-12 col-md-12 mt-2">
                                    <small>Untuk mengkonfirmasi, silahkan ketik ulang
                                        <span
                                            class="badge text-bg-secondary">provinsi-{{ Str::slug(strtolower($findRegion->province)) }}-wilayah-{{ Str::slug(strtolower($findRegion->regency)) }}
                                        </span>
                                        tindakan ini tidak dapat dibatalkan
                                    </small>
                                    <div class="form-group has-icon-left mt-2">
                                        <div class="position-relative">
                                            <input type="text" required onkeyup="checkConfirmDelete(this)"
                                                data-validation="provinsi-{{ Str::slug(strtolower($findRegion->province)) }}-wilayah-{{ Str::slug(strtolower($findRegion->regency)) }}"
                                                class="form-control @error('confirm') is-invalid @enderror"
                                                name="confirm">
                                            <div class="form-control-icon">
                                                <i class="bi bi-check-square"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-danger me-1 mb-1" id="buttonHapusData" disabled>Hapus
                        Data</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Tutup</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
