<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ExistingApp extends Model
{
    use HasFactory, HasUlids;

    protected $guarded = ['id'];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function regions(): BelongsToMany
    {
        return $this->belongsToMany(Region::class, 'region_existing_apps', 'existing_app_id', 'region_id');
    }

    public function existingAppInfo(): HasOne
    {
        return $this->hasOne(ExistingAppInfo::class, 'existing_app_id', 'id');
    }
}
