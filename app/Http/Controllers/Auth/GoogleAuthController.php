<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    /**
     * Redirect to Google OAuth page
     */
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')
            ->stateless()
            ->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            // Get user info from Google - using stateless mode
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Log the OAuth attempt for debugging
            Log::info('Google OAuth callback received', [
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId()
            ]);

            // Find user by email or Google ID - eager load customer and role relationships
            $user = User::with(['customer', 'role'])
                        ->where('email', $googleUser->getEmail())
                        ->orWhere('google_id', $googleUser->getId())
                        ->first();

            if ($user) {
                // User exists - ensure they have customer role and profile
                $customerRole = Role::where('name', 'customer')->first();
                
                if (!$customerRole) {
                    return redirect()->route('login')->withErrors([
                        'email' => 'Customer role not found. Please contact support.',
                    ]);
                }
                
                // Update Google ID if not set
                if (!$user->google_id) {
                    $user->google_id = $googleUser->getId();
                }
                
                // Update role_id to customer role
                $user->role_id = $customerRole->id;
                $user->save();
                
                // Ensure customer profile exists
                if (!$user->customer) {
                    Customer::create([
                        'user_id' => $user->id,
                        'name' => $user->name ?? $googleUser->getName(),
                        'email' => $user->email,
                        'phone' => $user->phone ?? null,
                    ]);
                    
                    // Reload user with customer relationship
                    $user = User::with(['customer', 'role'])->find($user->id);
                }
                
                // Final safety check
                if (!$user->customer) {
                    Log::error('Google OAuth: Customer profile creation failed', [
                        'user_id' => $user->id
                    ]);
                    return redirect()->route('login')->withErrors([
                        'email' => 'Unable to create customer profile. Please contact support.',
                    ]);
                }
                
                // Reload relationships to ensure they're fresh
                $user->load(['customer', 'role']);
                
                Log::info('Google OAuth: Existing user logged in', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'role' => $user->role->name,
                    'has_customer' => !is_null($user->customer)
                ]);
                
                // Log in the user (this automatically regenerates the session)
                Auth::login($user, true); // Remember user
                
                // Save session to ensure auth data persists
                request()->session()->save();
                
                // Verify auth worked
                Log::info('Google OAuth: Auth check after login', [
                    'is_authenticated' => Auth::check(),
                    'auth_user_id' => Auth::id(),
                    'session_id' => request()->session()->getId()
                ]);
                
                // Use absolute URL to avoid any route middleware issues
                $dashboardUrl = url('/customer/dashboard');
                Log::info('Google OAuth: Redirecting to', ['url' => $dashboardUrl]);
                
                return redirect($dashboardUrl);
            }

            // Create new customer user
            $customerRole = Role::where('name', 'customer')->first();

            if (!$customerRole) {
                return redirect()->route('login')->withErrors([
                    'email' => 'Customer role not found. Please contact support.',
                ]);
            }

            // Create new user
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => Hash::make(Str::random(32)), // Random password
                'email_verified_at' => now(), // Auto-verify email for Google users
                'role_id' => $customerRole->id,
                'google_id' => $googleUser->getId(),
            ]);

            // Create customer profile
            Customer::create([
                'user_id' => $user->id,
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
            ]);

            // Reload user with all relationships to ensure customer profile is loaded
            $user = User::with(['customer', 'role'])->find($user->id);
            
            // Final safety check
            if (!$user->customer) {
                Log::error('Google OAuth: New customer profile creation failed', [
                    'user_id' => $user->id
                ]);
                return redirect()->route('login')->withErrors([
                    'email' => 'Unable to create customer profile. Please contact support.',
                ]);
            }

            Log::info('Google OAuth: New user created and logged in', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->role->name,
                'has_customer' => !is_null($user->customer)
            ]);

            // Log in the user (this automatically regenerates the session)
            Auth::login($user, true); // Remember user
            
            // Save session to ensure auth data persists
            request()->session()->save();
            
            // Verify auth worked
            Log::info('Google OAuth: Auth check after new user login', [
                'is_authenticated' => Auth::check(),
                'auth_user_id' => Auth::id(),
                'session_id' => request()->session()->getId()
            ]);
            
            // Use absolute URL to avoid any route middleware issues
            $dashboardUrl = url('/customer/dashboard');
            Log::info('Google OAuth: Redirecting new user to', ['url' => $dashboardUrl]);

            return redirect($dashboardUrl);

        } catch (\Exception $e) {
            Log::error('Google OAuth Error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('login')->withErrors([
                'email' => 'Unable to login with Google. Please try again or use email/password.',
            ]);
        }
    }
}
