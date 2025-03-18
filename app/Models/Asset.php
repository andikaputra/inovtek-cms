<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Asset extends Model
{
    use HasFactory, HasUlids;

    protected $guarded = [
        'assetable_id',
        'assetable_type',
        'id',
    ];

    protected $hidden = [
        'assetable_id',
        'assetable_type',
        'asset_key',
        'is_image',
        'created_at',
        'updated_at',
    ];

    public function assetable(): MorphTo
    {
        return $this->morphTo();
    }
}
