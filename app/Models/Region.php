<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Region extends Model
{
    use HasFactory, HasUlids, Sluggable, SoftDeletes;

    protected $guarded = ['id'];

    protected $hidden = [];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => ['province', 'regency'],
            ],
        ];
    }

    public function regionDetail(): HasMany
    {
        return $this->hasMany(RegionDetail::class, 'region_id', 'id');
    }

    public function regionDetailInfo(): HasOne
    {
        return $this->hasOne(RegionDetailInfo::class, 'region_id', 'id');
    }

    public function announcementLink(): HasMany
    {
        return $this->hasMany(AnnouncementLink::class, 'region_id', 'id');
    }

    public function quizLink(): HasMany
    {
        return $this->hasMany(QuizLink::class, 'region_id', 'id');
    }

    public function regionGallery(): HasOne
    {
        return $this->hasOne(RegionGallery::class, 'region_id', 'id');
    }

    public function existingApps(): BelongsToMany
    {
        return $this->belongsToMany(ExistingApp::class, 'region_existing_apps', 'region_id', 'existing_app_id');
    }

    public function blogs(): BelongsToMany
    {
        return $this->belongsToMany(Blog::class, 'blog_tags', 'region_id', 'blog_id');
    }

    public function linkCollection(): HasMany
    {
        return $this->hasMany(LinkCollection::class, 'region_id', 'id');
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
