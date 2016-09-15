<?php
/**
 * Created by PhpStorm.
 * User: ado
 * Date: 30.1.2016
 * Time: 23:34
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkHours extends Model {

    protected $table='work_hours';

    public function user() {
        return $this->belongsTo('user', 'user_id');
    }
}