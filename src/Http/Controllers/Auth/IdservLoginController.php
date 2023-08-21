<?php

namespace BasePack\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class IdservLoginController extends Controller
{
    public $apiUrl;
    public $accessToken;

    private $idservUri;

    public function __construct()
    {
        $this->idservUri = env("AUTH_SERVER");
    }

    function logout(){
        if(session('socialLogin') == false){
            $this->apiUrl = $this->idservUri .'api/logout';
            $authToken = session('authToken');
            $response  = Http::withHeaders([
                'Authorization'=> 'Bearer '.$authToken,
                 ])->get("{$this->apiUrl}",[
            ]);
            session()->flush();
            return redirect('/');
        }else{
            $user = session('user');
            session()->flush();
            return redirect($this->idservUri . 'logoutAuth/'.$user->userId.'/'.$user->clientId);
        }
    }
}
