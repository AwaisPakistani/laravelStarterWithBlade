<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\{Auth, Gate};
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Events\SystemNotificationEvent;
use Carbon\Carbon;
class AuthController extends Controller
{
    public function login(){
        if (Auth::check()) {
            return view('admin.landing');
        }
        return view('admin.auth.login');
    }
    public function authenticate(LoginRequest $request): RedirectResponse
    {
        // dd($credentials);
        // if (Auth::attempt(['email' => $email, 'password' => $password, 'active' => 1])) {
        //     // Authentication was successful...
        // }

        // if (Auth::attempt([
        //     'email' => $email,
        //     'password' => $password,
        //     fn (Builder $query) => $query->has('activeSubscription'),
        // ])) {
        //     // Authentication was successful...
        // }

        // if (Auth::guard('admin')->attempt($credentials)) {
        //     // ...
        // }
        $credentials = $request->validated();
        // dd($credentials);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('admin/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
    public function profile(int $id){
        Gate::authorize('view-my-profile',$id);
        return view('admin.auth.profile');
    }
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }

}
