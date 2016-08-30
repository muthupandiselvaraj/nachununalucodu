<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Team_Member;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team_MemberController extends Controller
{

    

      public function create(Request $request) {
            $team_members = new Team_member;
            $team_members['user_id'] = $request['user_id'];
            $team_name = $request['team_name'];
            $team_members['role'] = $request['role'];
            $validateAdminUser = $this->validateAdminUser($team_members['user_id']);
            if($validateAdminUser['role']== "Admin") {
                $teamDetail = json_decode(Team::where('name', '=', $team_name)->get());
                $team_members['team_id']= $teamDetail[0]->id;
                print_r($team_members);exit;
                $team_members->save();            
               $success['status'] = '2000';
             $success['id'] = $team['user_id'];
                $success['message'] = 'successfully team name has been created';

                return $this->errorHandler($success);
                
            }
            

            
        }
}
    
