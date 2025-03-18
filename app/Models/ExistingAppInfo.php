<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExistingAppInfo extends Model
{
    use HasFactory, HasUlids;

    protected $guarded = ['id'];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function existingApp(): BelongsTo
    {
        return $this->belongsTo(ExistingApp::class, 'existing_app_id', 'id');
    }
}
