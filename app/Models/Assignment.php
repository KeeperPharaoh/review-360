<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assignment extends Model
{
    protected $fillable = [
        'from_user_id',
        'review_methods',
        'to_user_id',
    ];

    public function reviewMethod(): BelongsTo
    {
        return $this->belongsTo(ReviewMethod::class);
    }

    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function toUser(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
