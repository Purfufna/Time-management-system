<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\LoggedController;
use App\User;
use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserController extends LoggedController
{

    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('role_id','!=', 1)->get();

        foreach ($users as $user)
        {
            $user->roleName = $user->roleName();
        }

        return ['users' => $users];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'preffered_working_hours_per_day'    => 'required|integer',
            'password'  => 'required|min:6',
            'username'  => 'required',
            'role'   => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'success' => 'false',
                'message' => $validator->messages()
            ];
        }

        if($request->preffered_working_hours_per_day > 24 || $request->preffered_working_hours_per_day < 1) {
            return [
                'success' => 'false',
                'message' => [0 => 'Working more than 24 hours a day?']
            ];
        }

        $newUser = new User();
        $newUser->username = $request->username;
        $newUser->preffered_working_hours_per_day = $request->preffered_working_hours_per_day;
        $newUser->password = bcrypt($request->password);
        $newUser->role_id = $request->role;
        $newUser->save();

        return ['success' => 'ok'];
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::where('id',$id)->first();
        return ['user' => $user];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'preffered_working_hours_per_day'    => 'required|integer',
            'username'  => 'required',
            'role'   => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'success' => 'false',
                'message' => $validator->messages()
            ];
        }

        if($request->preffered_working_hours_per_day > 24 || $request->preffered_working_hours_per_day < 1) {
            return [
                'success' => 'false',
                'message' => [0 => 'Working more than 24 hours a day?']
            ];
        }

        $user = User::find($id);
        $user->username = $request->username;
        $user->preffered_working_hours_per_day = $request->preffered_working_hours_per_day;
        $user->role_id = $request->role;
        $user->save();

        return [ 'success' => 'ok' ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return ['success' => 'ok'];
    }
}
