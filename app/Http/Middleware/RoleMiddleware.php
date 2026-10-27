<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401)
                : redirect('login');
        }

        $user = Auth::user();

        // Check if user has no role
        if (!$user->role) {
            abort(403, 'No role assigned to user');
        }

        // Check if user's role matches the required role (several can be given as "a|b")
        if (! in_array($user->role->name, explode('|', $role), true)) {
            // API clients get a plain 403 instead of being redirected to an HTML page
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'You do not have permission to do that.'], 403);
            }

            // Redirect to appropriate dashboard based on user's role
            $dashboardRoute = match($user->role->name) {
                'super_admin' => 'admin.dashboard',
                'business_owner' => 'owner.dashboard',
                'customer' => 'customer.dashboard',
                default => 'customer.dashboard', // Default to customer dashboard for safety
            };
            
            return redirect()->route($dashboardRoute)->with('error', 'You do not have permission to access that page.');
        }

        return $next($request);
    }
}
