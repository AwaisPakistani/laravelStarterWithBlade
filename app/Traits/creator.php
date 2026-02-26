<?php

namespace App\Traits;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
trait creator
{
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class,'created_by','id');
    }
}
