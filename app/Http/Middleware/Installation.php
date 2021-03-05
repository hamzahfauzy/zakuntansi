<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Installation as Install;
use Illuminate\Http\Request;

class Installation
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
        $installation = Install::exists();
        if($installation) 
            return redirect()->route('login');
        return $next($request);
    }
}
