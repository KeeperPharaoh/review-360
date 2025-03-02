<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assignment extends Model
{
    public function reviewMethod(): BelongsTo
    {
        return $this->belongsTo(ReviewMethod::class);
    }
}
