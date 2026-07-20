<?php

namespace App\Http\Middleware;

use App\Services\TopkarDatabase;
use Closure;
use Illuminate\Http\Request;

class UseTopkarDatabase
{
    private $topkarDatabase;
    
    public function __construct(TopkarDatabase $topkarDatabase) {
        $this->topkarDatabase = $topkarDatabase;
    }
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $this->topkarDatabase->use();
        return $next($request);
    }
}
