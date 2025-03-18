<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Seo extends Model
{
    use HasFactory, HasUlids;

    protected $guarded = [
        'seotable_id',
        'seotable_type',
        'id',
    ];

    protected $hidden = [
        'seotable_id',
        'seotable_type',
        'seo_key',
    ];

    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }
}
