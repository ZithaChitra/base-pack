<?php

namespace BasePack\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class IdservAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public $appUri;

    public function __construct(){
        // $this->appUri = env('APP_URL');
    }
    
    public function handle(Request $request, Closure $next)
    {
        $time = time();
        dd('Middleware from base app');
        
        // validate the access token jwt issued by laravel passport
        $msbToken = session('userToken'); // authorization token generated by laravel passport
        if(isset($msbToken)){

            $token          = session('userToken')['token'];
            $token_decoded  = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $token)[1]))));
            
            $hasNoAppContexts = empty($token_decoded->contexts);
            if($hasNoAppContexts){
                abort(404);
            }
            
            $audience       = $token_decoded->aud;
            $validAudience  = str_starts_with($this->appUri, $audience);
            $validTime      = $this->isTokenValid();
            
            if($validAudience && $validTime){
                return $next($request);
            }else{
                return redirect('/login');
            }

        }else{
             return redirect('login');
        }
    }


    /**
     * Checks if token is still valid
     */ 
    public function isTokenValid(){
        $token = session('userToken')['token'];
        $token_decoded  = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $token)[1]))));

        $issued_at  = Carbon::createFromTimestamp($token_decoded->iat)->format('Y-m-d H:i:s'); 
        $expires_at = Carbon::createFromTimestamp($token_decoded->exp)->format('Y-m-d H:i:s');
        $issued_at  = Carbon::parse($issued_at);
        $expires_at = Carbon::parse($expires_at);
        
        $diff       = $expires_at->diffInMinutes($issued_at); 
        $halfTime   = Carbon::parse($issued_at->addMinutes($diff / 2)->format('Y-m-d H:i:s')); 
        $now        = Carbon::now();
        
        $equalOrPastHalfTime = $halfTime->lessThanOrEqualTo($now);

        if($equalOrPastHalfTime){
            return false;
        }

        return true;
    }
}
