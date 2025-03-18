<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegionDetailMapboxList extends Model
{
    use HasFactory, HasUlids;

    protected $guarded = ['id'];

    protected $hidden = ['region_detail_mapbox_id'];

    public function regionDetailMapbox(): BelongsTo
    {
        return $this->belongsTo(RegionDetailMapbox::class, 'region_detail_mapbox_id', 'id');
    }
}
