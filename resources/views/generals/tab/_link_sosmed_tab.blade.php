<div class="row mb-4">
    <div class="col-md-6">
        <a href="{{ route('admin.home.detail.link.index', ['id_provinsi' => $findRegion->slug]) }}"
            class="btn {{ Route::is('admin.home.detail.link.*') ? 'btn-primary' : 'btn-outline-primary' }} icon icon-left col-12"><i
                class="bi bi-link-45deg"></i> Tautan Wilayah</a>
    </div>
    <div class="col-md-6">
        <a href="{{ route('admin.home.detail.sosial-media.index', ['id_provinsi' => $findRegion->slug]) }}"
            class="btn icon icon-left col-12 {{ Route::is('admin.home.detail.sosial-media.*') ? 'btn-primary' : 'btn-outline-primary' }}"><i
                class="bi bi-broadcast"></i> Sosial Media Wilayah</a>
    </div>
</div>
