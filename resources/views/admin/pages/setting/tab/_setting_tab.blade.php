<div class="row mb-4">
    <div class="col-md-6">
        <a href="{{ route('admin.setting.profile.index') }}"
            class="btn {{ Route::is('admin.setting.profile.*') ? 'btn-primary' : 'btn-outline-primary' }} icon icon-left col-12"><i
                class="bi bi-person-fill-gear"></i> Profil</a>
    </div>
    <div class="col-md-6">
        <a href="{{ route('admin.setting.security.index') }}"
            class="btn {{ Route::is('admin.setting.security.*') ? 'btn-primary' : 'btn-outline-primary' }} icon icon-left col-12"><i
                class="bi bi-person-fill-lock"></i> Keamanan</a>
    </div>
</div>
