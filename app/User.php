<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class User extends Model {
    

    protected $table = 'users';

    protected $fillable = [
        'name',
        'role',
        'auth',
        'password',
        'email',
        'deleted_at'
    ];

    protected $hidden = [ 'auth','password','deleted_at' ];
}
