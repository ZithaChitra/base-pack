<?php

namespace BasePack\Http\Livewire\Auth;

use Livewire\Component;

class Authenticate extends Component
{
    protected $listeners = ['authenticated' => 'authHandler'];

    public function authHandler(){
        $app_token = null;
        if(isset($_COOKIE['msb_cstm_jwt'])){
            $app_token = $_COOKIE['msb_cstm_jwt'];
        }
        if($app_token){
            $token_parts = explode('.', $app_token);
            if(count($token_parts) == 3){
                session()->put('userToken', ['token' => $app_token]);
                $decoded  = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $app_token)[1]))));
                session()->put('user', (object)['userName' => $decoded->userName, 'appRole' => $decoded->appRole ?? '']);
                return redirect('/dashboard');
            }else{ // could not authenticate
                return redirect('/login');
            }
        }
    }

    public function render()
    {
        return view('basepack::livewire.auth.authenticate');
    }
}
