<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Installation;
use Illuminate\Http\Request;

class Installed
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
        $installation = Installation::exists();
        if(!$installation)
            return redirect()->route('installation');
        return $next($request);
    }
}
