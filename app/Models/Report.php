<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
