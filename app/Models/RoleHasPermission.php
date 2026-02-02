<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Database\Factories\RoleHasPermissionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class RoleHasPermission extends Model
{
     use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];


    public $timestamps = false;
    protected $table = 'role_has_permissions';
    protected static function newFactory()
    {
        return RoleHasPermissionFactory::new();
    }

    public $fillable = [
        'role_id',
        'permission_id'
    ];
}
