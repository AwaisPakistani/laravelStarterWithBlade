<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\Builder;
trait ScopeTrait
{
    public function scopeActive(Builder $builder): Builder
    {
       $user = auth()->user();
       if($user->hasRole('Super Admin') || $user->hasRole('admin')){
           return $builder;
       }
       return  $builder->where('created_by',auth()->user()->id)->orWhere('updated_by',auth()->user()->id);
    }
}
