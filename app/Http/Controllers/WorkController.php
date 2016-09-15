<?php

namespace App\Http\Controllers;

use App\User;
use App\WorkHours;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\LoggedController;
use App\Http\Requests;
use Validator;

class WorkController extends LoggedController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $allData = [];
        $fromDate = $request->query('fromDate');
        $toDate = $request->query('toDate');


        if ($request->query('uid') && $this->user->role_id == 1) {
            $user = User::where('id', $request->query('uid'))->first();
            $workList = WorkHours::where('user_id', $user->id)->get();

            foreach ($workList as $key => $work) {
                $sum = WorkHours::where('user_id', $user->id)->where('date_time', $work->date_time)->sum('hours');
                $work->sum = $sum;
                $work->color = ($sum >= $user->preffered_working_hours_per_day) ? 'green' : 'red';

                $allData[$key] = $work;
            }
            return ['workList' => $allData, 'limit' => $user->preffered_working_hours_per_day];
        } else if ($fromDate && $toDate ) {

            $filtered = WorkHours::where('user_id', $this->user->id)
                ->where('date_time', '>=', $fromDate)
                ->where('date_time', '<=', $toDate)
                ->get();

            foreach ($filtered as $key => $work) {

                $sum = WorkHours::where('user_id', $this->user->id)->where('date_time', $work->date_time)->sum('hours');
                $work->sum = $sum;
                $work->color = ($sum >= $this->user->preffered_working_hours_per_day) ? 'green' : 'red';

                $allData[$key] = $work;
            }

            $exported_data = $filtered->groupBy('date_time');

            return ['workList' => $allData, 'limit' => $this->user->preffered_working_hours_per_day, 'exported' => $exported_data];
        } else {
            $workList = WorkHours::where('user_id', $this->user->id)->get();

            foreach ($workList as $key => $work) {
                $sum = WorkHours::where('user_id', $this->user->id)->where('date_time', $work->date_time)->sum('hours');
                $work->sum = $sum;
                $work->color = ($sum >= $this->user->preffered_working_hours_per_day) ? 'green' : 'red';

                $allData[$key] = $work;
            }

            return ['workList' => $allData, 'limit' => $this->user->preffered_working_hours_per_day];

        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if ($this->user->role_id == 1 && $request->user) {

            $work = new WorkHours();
            $work->user_id = $request->user;
            $work->work_description = $request['data']['description'];
            $work->hours = $request['data']['hours'];
            $work->date_time = $request['data']['date'];
            $work->save();

            return [
                'success' => 'ok'
            ];
        } else {

            $validator = Validator::make($request->all(), [
                'description' => 'required',
                'hours' => 'required|integer',
                'date' => 'required'
            ]);

            if ($validator->fails()) {
                return [
                    'success' => 'false',
                    'message' => $validator->messages()
                ];
            }

            $sum = WorkHours::where('user_id', $this->user->id)->where('date_time', $request->date)->sum('hours');
            $totalhours = $sum + $request->hours;

            if($totalhours > 24){
                return [
                    'success' => 'false',
                    'message' => ['Total amount of time exceeds daily hour limit']
                ];
            }

            $work = new WorkHours();
            $work->user_id = $this->user->id;
            $work->work_description = $request->description;
            $work->hours = $request->hours;
            $work->date_time = $request->date;
            $work->save();

            return [
                'success' => 'ok'
            ];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $editWork = WorkHours::find($id);
        if ($editWork->user_id == $this->user->id || $this->user->role_id == 1) {
            return ['work' => $editWork];
        }
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
        $validator = Validator::make($request->input(), [
            'work_description' => 'required',
            'hours' => 'required|integer',
            'date_time' => 'required'
        ]);

        if ($validator->fails()) {
            return ['success' => 'failed', 'message' => $validator->messages()];
        }

        $sum = WorkHours::where('user_id', $this->user->id)->where('date_time', $request->date_time)->sum('hours');
        $totalhours = $sum + $request->hours;

        if($totalhours > 24){
            return [
                'success' => 'failed',
                'message' => ['Total amount of time exceeds daily hour limit']
            ];
        }



        $work = WorkHours::find($id);

        if ($this->user->id == $work->user_id || $this->user->role_id == 1) {
            $work->work_description = $request->work_description;
            $work->hours = $request->hours;
            $work->date_time = $request->date_time;
            $work->save();

            return ['success' => 'ok'];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $work = WorkHours::find($id);

        if ($work->user_id == $this->user->id || $this->user->role_id == 1) {
            $work->delete();
            return ['success' => 'ok'];
        }
    }
}
