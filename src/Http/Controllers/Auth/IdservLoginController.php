<?php

namespace BasePack\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cookie;

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
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = array();

        session_destroy();
        session()->flush();

        $cookies = request()->cookies->all();
        $deletedCookies = [];

        foreach ($cookies as $name => $value) {
            $deletedCookies[] = Cookie::forget($name);
        }
        return redirect('/');
    }
}
