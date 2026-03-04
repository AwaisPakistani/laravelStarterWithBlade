<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Export extends Model
{
    protected $guarded = [];
    protected $casts = [
        'filters' => 'array', // Automatically casts to/from JSON
    ];

}
