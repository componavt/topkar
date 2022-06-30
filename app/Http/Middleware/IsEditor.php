<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

use App\Models\Team;

class IsEditor
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
        $user = Auth::user();
        $team = Team::whereName('TopKar editors')->first();
        if($user && $user->belongsToTeam($team) && 
            ($user->hasTeamRole($team, 'admin') || $user->hasTeamRole($team, 'editor'))){
            return $next($request);
        }
   
        return redirect('/')->withErrors("You don't have admin access.");
    }
}
