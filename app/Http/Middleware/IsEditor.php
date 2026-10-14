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
        $route = $request->route();

        $is_history = $route && in_array($route->getName(), ['toponyms.history', 'streets.history'], true);

        $user = Auth::user();

        // Гостю не нужна выборка команды редакторов.
        if (!$user) {
            if ($is_history) {
                return response('Forbidden', 403)
                    ->header('Content-Type', 'text/plain; charset=UTF-8');
            }

            return redirect('/')->withErrors("You don't have admin access.");
        }

        $team = Team::whereName('TopKar editors')->first();

        if ($team && $user->belongsToTeam($team) && ($user->hasTeamRole($team, 'admin') || $user->hasTeamRole($team, 'editor'))) {
            return $next($request);
        }

        // Авторизованному пользователю без нужной роли
        // история также недоступна, но перенаправления нет.
        if ($is_history) {
            return response('Forbidden', 403)
                ->header('Content-Type', 'text/plain; charset=UTF-8');
        }

        return redirect('/')->withErrors("You don't have admin access.");
    }
}
