<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property Company|null $company
 */
class MoonshineUser extends \MoonShine\Laravel\Models\MoonshineUser
{
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
