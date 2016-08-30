<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\User;



class Controller extends BaseController
{

    public function errorHandler($error) 
    {
        return json_encode($error);
    }
    
    public function authenticateUser( $user,$auth)
    { 
       
       return User::select('role','id')
                        ->where('auth', '=', $auth)
                        ->where('name', '=', $user)
                        ->get()->toArray();
       
       
    }
    public function validateAdminUser ($userId) {
        $userData =  User::select('id','name','email','role')->find($userId); 
        if(empty($userData)) { 
           $message['status']= 2001;
           $message['data']=$userId;
           $message['message']= 'unauthorized access';
           
            return $this->errorHandler($message);       
        }
        if($userData['role']!="Admin") { 
           $message['status']= 2002;
           $message['data']=$userI;
           $message['message']= 'You are not having the permission to create team';
           
           return $this->errorHandler($message);
        
        } else {
            return $userData;
        }
    }
    
    
    
    
    public function hello() 
    {
        return 'Hello Bruce'; exit;
    }
    
    
}
