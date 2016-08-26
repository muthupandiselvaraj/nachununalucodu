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
    
    
    
    public function hello() 
    {
        return 'Hello Bruce'; exit;
    }
    
    
}
