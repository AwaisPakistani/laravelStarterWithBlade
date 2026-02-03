<?php

namespace App\Models;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Module extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function permissions()
    {
        return $this->hasMany(Permission::class, 'module_id', 'id');
    }

    public function subModules()
    {
        return $this->hasMany(Module::class, 'parent_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(Module::class, 'parent_id', 'id');
    }
}
