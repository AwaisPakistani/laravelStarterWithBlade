<?php

namespace App\Traits;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
trait updator
{
    public function updator(): BelongsTo
    {
        return $this->belongsTo(User::class,'updated_by','id');
    }
}
