<?php

namespace App\Http\Middleware;

use Closure;
use Cookie;
use Request;

class terminal
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
        $the_cookie=Request::cookie('terminal_id');

        if ($the_cookie != null) {

            if(in_array($the_cookie,TERMINAL_IDS_ARRAY)){

                return $next($request);                

            }else{
                return redirect('/login');
            }

        }else{
            return redirect('/login');
        }

        
    }
}
