<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\LoggedController;
use App\User;
use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class SettingsController extends LoggedController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return [
            'preffered_hours' => $this->user->preffered_working_hours_per_day,
            'success' => 'ok'
        ];
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'perday'   => 'required|integer',
        ]);

        if ($validator->fails()) {
            return [
                'success' => 'false',
                'message' => $validator->messages()
            ];
        }

        $userSetting = User::find($this->user->id);
        $userSetting->preffered_working_hours_per_day = $request->perday;
        $userSetting->save();

        return [
            'success' => 'ok',
            'message' => [0 => 'Preffered working hours successfuly updated']
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
