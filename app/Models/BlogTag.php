<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogTag extends Model
{
    use HasFactory, HasUlids;

    protected $guarded = ['id'];

    protected $hidden = ['region_id', 'blog_id'];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id', 'id');
    }

    public function blog(): BelongsTo
    {
        return $this->belongsTo(Blog::class, 'blog_id', 'id');
    }
}
