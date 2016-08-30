<?php

namespace App;


use Illuminate\Database\Eloquent\Model;



class Team_Member extends Model {
    

    protected $table = 'team_memebers';

    protected $fillable = [
        'name',
        'role',
        'auth',
        'user_id',
        'team_name'
    ];

    protected $hidden = [ 'auth' ];
}
