<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed $review_method_id
 */
class Event extends Model
{
    protected $fillable = [
        'company_id',
        'review_method_id',
        'name',
        'start_at',
        'end_at',
        'report',
        'team_report',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function reviewMethod(): BelongsTo
    {
        return $this->belongsTo(ReviewMethod::class);
    }
}
