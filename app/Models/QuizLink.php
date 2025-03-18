<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizLink extends Model
{
    use HasFactory, HasUlids;

    protected $guarded = ['id'];

    protected $hidden = ['region_id'];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id', 'id');
    }

    public function quizRegistration(): HasMany
    {
        return $this->hasMany(QuizRegistration::class, 'quiz_link_id', 'id');
    }
}
