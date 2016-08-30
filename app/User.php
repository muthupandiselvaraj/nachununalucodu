<?php

namespace App;


use Illuminate\Database\Eloquent\Model;



class User extends Model {
    

    protected $table = 'users';

    protected $fillable = [
        'name',
        'role',
        'auth',
        'password',
        'email',
        'is_deleted'
    ];

    protected $hidden = [ 'auth','password' ];
}
