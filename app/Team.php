<?php

namespace App;


use Illuminate\Database\Eloquent\Model;



class Team extends Model {
    

    protected $table = 'teams';

    protected $fillable = [
        'name',
        'role',
        'auth',
        'user_id',
        'team_name'
    ];

    protected $hidden = [ 'auth' ];
}
