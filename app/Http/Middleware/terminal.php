<?php

namespace App\Http\Middleware;

use Closure;
use Cookie;
use Request;
use App\config;


class terminal
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)     {
        $the_cookie=Request::cookie('terminal_id');
        if ($the_cookie != null) {
            $config = new config;

            if(in_array($the_cookie,$config->registeredTerminalIds())){

                return $next($request);                

            }else{
                return redirect('/login');
            }

        }else{
            return redirect('/login');
        }

        
    }
}
