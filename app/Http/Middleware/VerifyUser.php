<?php

namespace App\Http\Middleware;

use App\Models\VerifyToken;
use Closure;
use Illuminate\Http\Request;

class VerifyUser
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
       $token=$request->header('Authorization');
       
        if($token){
           if( VerifyToken::where('token',$token)->first()){
                return $next($request);
           }         
        }
            return response()->json(['error'=>"token invalid",'status'=>false], 422);     
        
        
    }
}
