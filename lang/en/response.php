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
        'view' => ':data data render successfully',
        'store' => ':data data added successfully',
        'update' => ':data data has been successfully updated',
        'delete' => ':data data has been successfully deleted',
        'import' => ':data data imported successfully',
        'export' => ':data data successfully exported',
        'switch' => ':data action successfully',
    ],
    'error' => [
        'view' => ':data data failed to render, :error',
        'store' => ':data data failed to added, :error',
        'update' => ':data data failed to updated, :error',
        'delete' => ':data data failed to deleted, :error',
        'import' => ':data data failed to imported, :error',
        'export' => ':data data failed to exported, :error',
        'switch' => ':data action failed with error, :error',
    ],
    'server' => [
        'integration' => 'Integration :integration failed, :error',
        'action' => 'Action :action failed, :error',
    ],
];
