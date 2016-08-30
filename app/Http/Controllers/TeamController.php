<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Team;
use App\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{

    public function create(Request $request)
    {
        $team = new Team;
        $requestParams= array();
        $user_id = $request['user_id'];
        $team_name = $request['team_name'];
        $validateAdminUser = $this->validateAdminUser($user_id);
        $teamDetail = Team::where('name', '=', $team_name)->get()->count();
        if($teamDetail == 0) {
            $team['user_id'] = $user_id;$team['name'] = $team_name; 
            $team->save();            
            $success['status'] = '2000';
            $success['id'] = $user_id;
            $success['message'] = 'successfully team name has been created';

            return $this->errorHandler($success);
         } else {
            $message['status']= 2003;
            $message['data']=$user_id;
            $message['message']= 'Team name alredy exist';

       return $this->errorHandler($message);
         }
    }     

    
}