<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Response Action Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the Controller class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'success' => [
        'view' => 'Data :data berhasil dimuat',
        'store' => 'Data :data berhasil ditambahkan',
        'update' => 'Data :data berhasil diperbaharui',
        'delete' => 'Data :data berhasil dihapus',
        'import' => 'Data :data berhasil di impor',
        'export' => 'Data :data berhasil di ekspor',
        'switch' => ':data berhasil dilakukan',
    ],
    'error' => [
        'view' => 'Data :data gagal dimuat, :error',
        'store' => 'Data :data gagal ditambahkan, :error',
        'update' => 'Data :data gagal diperbaharui, :error',
        'delete' => 'Data :data gagal dihapus, :error',
        'import' => 'Data :data gagal di impor, :error',
        'export' => 'Data :data gagal di ekspor, :error',
        'switch' => ':data gagal dilakukan, :error',
    ],
    'server' => [
        'integration' => 'Proses integraasi :integration gagal, :error',
        'action' => 'Permintaan aksi :action gagal, :error',
    ],
];
