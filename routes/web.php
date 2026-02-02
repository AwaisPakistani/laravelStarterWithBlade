<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\{
 DashboardController,
 AuthController
};


Route::get('/', function () {
    return view('admin.auth.login');
});
//Authentication Routes
Route::get('admin/login',[AuthController::class,'login'])->name('admin.login');
Route::post('admin/login',[AuthController::class,'authenticate'])->name('admin.authenticate');
Route::middleware('AuthMiddleware')->prefix('admin')->name('admin.')->group(function(){
    // Dashboard Routes
    Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');
    Route::post('/logout',[AuthController::class,'logout'])->name('logout');
});

// NOt Found Route
Route::fallback(function(){
   return view('admin.errors.not-found');
});



