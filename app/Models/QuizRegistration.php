<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizRegistration extends Model
{
    use HasFactory, HasUlids;

    protected $guarded = ['id'];

    protected $hidden = ['quiz_link_id', 'region_detail_id'];

    public function regionDetail(): BelongsTo
    {
        return $this->belongsTo(RegionDetail::class, 'region_detail_id', 'id');
    }

    public function quizLink(): BelongsTo
    {
        return $this->belongsTo(QuizLink::class, 'quiz_link_id', 'id');
    }
}
