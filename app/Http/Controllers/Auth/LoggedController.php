<?php
/**
 * Created by PhpStorm.
 * User: ado
 * Date: 30.1.2016
 * Time: 19:22
 */
namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoggedController extends Controller {

    public $user;
    public function __construct()
    {
        $this->middleware("check_token");

        $request = Request::capture();
        $token = $request->header("UT");
        $user = User::where('user_token', $token)->first();

        $this->user = $user;
    }
}