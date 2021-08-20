<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;

class EnsureProviderOnSchedule
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $user = auth()->user();
        if ($user->role == UserRole::HCP()) {
            if ($user->in_schedule && ($user->hcp_data->signature ?? null) != null) {
                return $next($request);
            } else {
                return redirect('home');
            }
        } else if ($user->role == UserRole::Employee()) {
            if($user->workspace_id == null) {
                return redirect('home');
            }
        }

        return $next($request);
    }
}
