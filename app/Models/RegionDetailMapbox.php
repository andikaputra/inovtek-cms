<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RegionDetailMapbox extends Model
{
    use HasFactory, HasUlids;

    protected $guarded = ['id'];

    protected $hidden = ['region_detail_id'];

    public function regionDetail(): BelongsTo
    {
        return $this->belongsTo(RegionDetail::class, 'region_detail_id', 'id');
    }

    public function regionDetailMapboxList(): HasMany
    {
        return $this->hasMany(RegionDetailMapboxList::class, 'region_detail_mapbox_id', 'id');
    }
}
