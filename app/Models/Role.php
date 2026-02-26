<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Guard;

class Role extends SpatieRole
{
    use HasFactory;
    //  SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];
    public $table = 'roles';
    // protected $fillable= ['name'];

    public  function modelHasRole()
    {
        return $this->hasMany(ModelHasRole::class, 'role_id', 'id');
    }
    public function scopeSearch($query, $search)
    {
        return $query->whereAny(
            ['name'],
            'like',
            "%{$search}%"
        );
    }
}
