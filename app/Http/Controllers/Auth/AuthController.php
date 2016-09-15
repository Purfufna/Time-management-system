<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }


    public function postRegister(Request $request) {


        $validator = Validator::make($request->all(), [
            'username'    => 'required|unique:users',
            'password'  => 'required|min:6',
            'hours'   => 'required|integer',
        ]);

        if ($validator->fails()) {
            return [
                'success' => 'false',
                'message' => $validator->messages()
            ];
        }

        else if($request->hours > 24 || $request->hours < 1) {
            return [
                'success' => 'failed',
                'message' => [0 => 'You work more than 24 hours a day?']
            ];

        } else {
            $user = new User();
            $user->username = $request->username;
            $user->password = bcrypt($request->password);
            $user->preffered_working_hours_per_day = $request->hours;
            $user->role_id = 3;
            $user->save();

            return [
                'success' => 'ok',
                'message' => [0 => 'Registration successful, please proceed to login']
            ];
        }

    }

    public function postLogin(Request $request) {

        if (Auth::validate(array('username' => $request->username, 'password' => $request->password)))
        {
            $token = md5(time().$request->username.''.User::select('updated_at')->where('username', $request->username)->first());

            $user = User::where('username', $request->username)->first();
            $user_update_token = User::find($user->id);
            $user_update_token->user_token = $token;
            $user_update_token->save();

            return response()->json([

                'success' => 'ok', 'user_token' => $token, 'role' => $user->role_id, 'username' => $user->username
            ]);
        }
        else
            return response()->json([
                'success' => 'false',
            ]);
    }
}
