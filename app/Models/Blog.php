<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Blog extends Model
{
    use HasFactory, HasUlids, Sluggable;

    protected $guarded = ['id'];

    protected $hidden = [];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
            ],
        ];
    }

    public function regions(): BelongsToMany
    {
        return $this->belongsToMany(Region::class, 'blog_tags', 'blog_id', 'region_id');
    }

    public function assets(): MorphMany
    {
        return $this->morphMany(Asset::class, 'assetable');
    }

    public function seos(): MorphMany
    {
        return $this->morphMany(Seo::class, 'seotable');
    }
}
