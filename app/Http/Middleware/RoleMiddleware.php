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
            return redirect('login');
        }

        $user = Auth::user();
        
        // Check if user has no role
        if (!$user->role) {
            abort(403, 'No role assigned to user');
        }
        
        // Check if user's role matches the required role
        if ($user->role->name !== $role) {
            // Redirect to appropriate dashboard based on user's role
            $dashboardRoute = match($user->role->name) {
                'it_admin' => 'admin.dashboard',
                'business_owner' => 'owner.dashboard',
                'staff' => 'staff.dashboard',
                default => 'staff.dashboard',
            };
            
            return redirect()->route($dashboardRoute)->with('error', 'You do not have permission to access that page.');
        }

        return $next($request);
    }
}
