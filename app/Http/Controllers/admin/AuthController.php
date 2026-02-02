<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;
use App\Events\SystemNotificationEvent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
class AuthController extends Controller
{
    public function login(){
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

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }

}
