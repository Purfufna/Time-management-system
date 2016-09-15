<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function work () {
        return $this->hasMany('work_hours', 'user_id');
    }

    public function roleName()
    {
        $roles = array (1 => 'Admin', 2 => 'User manager', 3 => 'Regular user');

        return $roles[$this->role_id];
    }
}
