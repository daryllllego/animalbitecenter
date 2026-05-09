<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureNurseOnDuty
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        // If the user is a branch account, they MUST have an active nurse in session to perform write operations
        if ($user && $user->is_branch_account) {
            // Allow all GET requests (reading data)
            if ($request->isMethod('GET')) {
                return $next($request);
            }

            // Always allow nurse login, logout, and set-date actions
            $allowedRoutes = [
                'nurse.login',
                'nurse.logout',
                'logout',
                'animal-bite.set-date',
                'profile.update-password' // Allow changing branch account password if nurse is not logged in
            ];

            if ($request->routeIs($allowedRoutes)) {
                return $next($request);
            }

            // Check for active nurse session
            if (!session()->has('active_nurse_id')) {
                return redirect()->back()->with('error', 'Action blocked: Please sign in a "Nurse on Duty" first.');
            }
        }

        return $next($request);
    }
}
