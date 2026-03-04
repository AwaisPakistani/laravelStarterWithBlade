<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class SocialiteController extends Controller
{
     /**
     * Redirect to GitHub for authentication
     */
    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }

    /**
     * Redirect to Google for authentication
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->scopes(['profile', 'email']) // Request additional permissions
            ->with(['hd' => 'gmail.com']) // Restrict to specific domain (optional)
            ->redirect();
    }

    /**
     * Generic redirect for any provider
     */
    public function redirect($provider)
    {
        $validProviders = ['github', 'google', 'facebook', 'twitter'];

        if (!in_array($provider, $validProviders)) {
            abort(404, 'Provider not supported');
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle GitHub callback
     */
    public function handleGithubCallback()
    {
        try {
            $githubUser = Socialite::driver('github')->user();

            // Find or create user
            $user = $this->findOrCreateUser($githubUser, 'github');

            // Log the user in
            Auth::login($user, true);

            // return redirect()->intended('/dashboard');
            return redirect()->intended('admin/dashboard');

        } catch (Exception $e) {
            Log::error('GitHub login failed: ' . $e->getMessage());
            return redirect('admin/login')->with('error', 'GitHub login failed. Please try again.');
        }
    }

    /**
     * Handle Google callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = $this->findOrCreateUser($googleUser, 'google');

            Auth::login($user, true);

            return redirect()->intended('admin/dashboard');

        } catch (Exception $e) {
            Log::error('Google login failed: ' . $e->getMessage());
            return redirect('admin/login')->with('error', 'Google login failed. Please try again.');
        }
    }

    /**
     * Generic callback handler
     */
    public function callback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();

            $user = $this->findOrCreateUser($socialUser, $provider);

            Auth::login($user, true);

            return redirect()->intended('/dashboard');

        } catch (Exception $e) {
            Log::error("{$provider} login failed: " . $e->getMessage());
            return redirect('/login')->with('error', ucfirst($provider) . ' login failed. Please try again.');
        }
    }

    /**
     * Find existing user or create a new one
     */
    private function findOrCreateUser($socialUser, $provider)
    {
        // Set the field names based on provider
        $providerIdField = "{$provider}_id";
        $providerTokenField = "{$provider}_token";
        $providerRefreshTokenField = "{$provider}_refresh_token";

        // Check if user exists with this provider ID
        $user = User::where($providerIdField, $socialUser->getId())->first();

        if ($user) {
            // Update tokens
            $user->update([
                $providerTokenField => $socialUser->token,
                $providerRefreshTokenField => $socialUser->refreshToken,
                'avatar' => $socialUser->getAvatar(),
            ]);

            return $user;
        }

        // Check if user exists with this email
        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            // Link the provider account to existing user
            $user->update([
                $providerIdField => $socialUser->getId(),
                $providerTokenField => $socialUser->token,
                $providerRefreshTokenField => $socialUser->refreshToken,
                'avatar' => $socialUser->getAvatar(),
            ]);

            return $user;
        }

        // Create a new user
        return User::create([
            'name' => $socialUser->getName() ?? $socialUser->getNickname(),
            'email' => $socialUser->getEmail(),
            $providerIdField => $socialUser->getId(),
            $providerTokenField => $socialUser->token,
            $providerRefreshTokenField => $socialUser->refreshToken,
            'avatar' => $socialUser->getAvatar(),
            'password' => bcrypt(random_int(8,8)), // Random password for OAuth users
        ]);
    }
}
