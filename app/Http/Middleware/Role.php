<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Role
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
        if(Auth::check()){
            if(!empty($guards)){
                $logged_in = true;
                $roles = json_decode(Auth::user()->roles);
                if(!empty($roles)){
                    foreach($guards as $value){
                        if(in_array($value, $roles)){
                            $logged_in = true;
                            break;
                        }else{
                            $logged_in = false;
                        }
                    }
                    if($logged_in == false){
                        return redirect()->back();
                    }
                }else{
                    Auth::logout();
                    return redirect()->guest('login');
                }
            }
        }else{
            Auth::logout();
            return redirect()->guest('login');
        }
        return $next($request);
    }
}
