<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegionDetail extends Model
{
    use HasFactory, HasUlids, Sluggable, SoftDeletes;

    protected $guarded = ['id'];

    protected $hidden = ['region_id'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'village',
            ],
        ];
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id', 'id');
    }

    public function quizRegistration(): HasMany
    {
        return $this->hasMany(QuizRegistration::class, 'region_detail_id', 'id');
    }

    public function regionDetailMapbox(): HasMany
    {
        return $this->hasMany(RegionDetailMapbox::class, 'region_detail_id', 'id');
    }
}
