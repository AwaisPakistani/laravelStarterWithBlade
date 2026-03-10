<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\admin\{
 DashboardController,
 AuthController,
 UserController,
 RoleController,
 PermissionController,
 ModuleController,
 SocialiteController,
};

// Route::get('/', function () {
//     return view('admin.auth.login');
// });
Route::get('/',[AuthController::class,'login'])->name('admin.login');
Route::get('/test', function () {
    return view('admin.auth.test');
});
//Authentication Routes
Route::get('admin/login',[AuthController::class,'login'])->name('admin.login');
Route::post('admin/login',[AuthController::class,'authenticate'])->name('admin.authenticate');

// Socialite Routes
// GitHub routes
Route::get('/auth/github/redirect', [SocialiteController::class, 'redirectToGithub']);
Route::get('/auth/github/callback', [SocialiteController::class, 'handleGithubCallback']);

// Google routes
Route::get('/auth/google/redirect', [SocialiteController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [SocialiteController::class, 'handleGoogleCallback']);
// Generic redirect (optional)
Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect']);
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback']);
Route::prefix('admin')->name('admin.')->group(function(){
    // Dashboard Routes
    Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');
    Route::post('/logout',[AuthController::class,'logout'])->name('logout');
    Route::get('unauthorized',function(){
        return view('admin.errors.forbidden');
    })->name('unauthorized');
    Route::middleware(['AuthMiddleware'])->group(function () {
            Route::middleware(['PermissionMiddleware'])->group(function () {
                Route::resource('users', UserController::class);
                Route::resource('roles', RoleController::class);
                Route::resource('permissions', PermissionController::class);
                Route::resource('modules', ModuleController::class);

                // Export routes
                Route::get('/exports', [ExportController::class, 'index'])->name('exports.index');
                // Export users
                Route::post('/exports/users', [ExportController::class, 'exportUsers'])->name('exports.users');

                Route::get('/exports/{export}/status', [ExportController::class, 'status'])->name('exports.status');

                // Alternative: Direct export with filters
                Route::get('/admin/users/export', [ExportController::class, 'exportUsers'])->name('admin.users.export');
            });
    });
});

// NOt Found Route
Route::fallback(function(){
   return view('admin.errors.not-found');
});



