<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Shopex\LubanAdmin\Traits\AuthenticatesUsers;
use Shopex\Luban\Facades\Luban;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    protected $redirectTo = '/home';
    protected $sso_app_id;
    protected $sso_app_secret;
    protected $sso_url;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->sso_app_id = Luban::config()->get("sso_app_id");
        $this->sso_app_secret = Luban::config()->get("sso_app_secret");
        $this->sso_url = Luban::config()->get("sso_url");

        $this->middleware('guest')->except('logout');
    }
}


// $this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
// $this->post('login', 'Auth\LoginController@login');
// $this->post('logout', 'Auth\LoginController@logout')->name('logout');
