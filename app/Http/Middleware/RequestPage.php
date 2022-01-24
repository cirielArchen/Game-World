<?php

namespace App\Http\Middleware;

use Closure;

class RequestPage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $requestParams = $request->input();
        if($request->has('page') && $requestParams['page'] <= 0)
        {
            $request->request->set('page', 1);
        }

        return $next($request);
    }
}
