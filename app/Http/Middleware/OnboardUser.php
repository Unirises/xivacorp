<?php

namespace App\Http\Middleware;

use App\Enums\TypeIdent;
use App\Models\Type;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardUser
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

        if(Auth::check()) {
            $user = auth()->user();
            if(!$user->is_onboarded) {
                $types = Type::where('type', TypeIdent::HCP)->get();
                return response(view('users.onboard', compact('types')));
            }
        }
        return $next($request);
    }
}
