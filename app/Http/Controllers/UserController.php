<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserController extends Controller
{

    /**
     * Create new user instance
     *
     * @param  int  $id
     * @return Response
     */

    public function create(Request $request)
    {
        $user = new User;
        $requestParams= array();
        
        $UserFormFields = array('name', 'email', 'password', 'role');
        $userRole = $this->authenticateUser($request->user_name, $request->auth);
        $message = array();

        if (empty($userRole)) {
            $message['message'] = 'Invalid User';
            $message['code'] = '1002';
            return $this->errorHandler($message);
        }
        if ($userRole[0]['role'] != "admin") {
            $message['code'] = '1010';
            $message['message'] = 'You are not authorized to create new user';           
            return $this->errorHandler($message);
        }
        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            $message['message'] = 'Invalid Email Id';
            $message['code'] = '1002';
            return $this->errorHandler($message);
        }
        $user_exists = User::where('email', '=', $request->email)->get()->count();
        foreach ($request->all() as $key => $value) {
            if (in_array($key, $UserFormFields)) {                
                $requestParams[$key] = $value; 
            } 
        }
        $user['attributes']= $requestParams;
        $auth = $this->generatePassword(15);
        $request->merge(array('auth' => $auth));
        $requestParams['role'] == 'admin' ? 'admin' : 'user';
        $requestParams['auth'] =$auth;
        $request->request->replace($requestParams);
        $user['attributes'] = $requestParams;

        if ($user_exists > 0) {
            $message['status'] = '1001';
            $message['message'] = 'User account exists with given email id';

            return $this->errorHandler($message);
        } else {
            $user->save();            
            $success['status'] = '1000';
            $success['id'] = $user->id;
            $success['auth'] = $auth;
            $success['message'] = 'successfully user has been created';
           
			return $this->errorHandler($success);
        }
    }

    public function viewProfile(Request $request, $id)
    {
       $userRole = $this->authenticateUser($request->user_name, $request->auth);
       $user = User::find($id)->toArray(); 
       $UserFormFields = array('name', 'email', 'role');
      
       $userData = array();
       $message['status']= '1000';
       $message['message']= 'success';
       
       if($userRole[0]['id'] != $id || $user['is_deleted'] == 1)  {
           $message['code']= 1001;           
           $message['message']= 'Invalid user data';
           
           return $this->errorHandler($message);
       } else if(empty($user)  || empty($userRole) ) { 
           $message['status']= 1001;
           $message['data']=$id;
           $message['message']= 'user profile doesn`t not exists';
           
           return $this->errorHandler($message);
        
        } else if( $userRole[0]['role'] == 'admin' && $request->view != 'all' ) {            
            $data= User::select('id','name','email','role')->find(explode(',',$request->view));
            foreach($data as $userProfile) {
            
              array_push($userData, $userProfile['attributes']);
            }
            $message['data']= $userData;
            
        } else {           
            $userData =  User::select('id','name','email','role')->find($id); 
            $message['data'] = $userData['attributes'];
        }   

        return $this->errorHandler($message);
    }
    
    public function editProfile(Request $request, $id)
    {       
       $user = User::where('id', '=', $id)->get()->toArray();
        if(empty($user) ) {
           $message['code']= 1001;
           $message['message']= 'user profile doesn`t not exists';
           $message['data']=$id;
           return $this->errorHandler($message);
        
        } else if( $user[0]['is_deleted'] == 1) {
           $message['code']= 1001;
           $message['data']=$id;
           $message['message']= 'user profile is deleted or not exists';
           return $this->errorHandler($message);
        }
        $UserFormFields = array('name', 'email', 'password', 'role','authkey');
        $requestParams= array();
        
        $message['code']= 1000;
        $message['message']= 'user profile updated successs';
        
        foreach($request->all() as $key=>$value ){
           if (in_array($key, $UserFormFields)) {
                 if($key == 'password') {
                   $requestParams[$key] = md5($value); 
                 } else if($key == 'authkey') {
                    $auth= $this->generatePassword(15);
                    $message['auth'] = $auth;
                    $requestParams['auth'] = $auth;
                 } else{
                    $requestParams[$key] = $value; 
                 }
            }
        }

       if($user[0]['role'] == 'admin' && isset($request->user_id)) { 
          $updateUser  = User::where('id', '=', $request->user_id)->get();
          if(! empty($updateUser) &&  $updateUser[0]['is_deleted'] != 1) {
            User::where('id',$request->user_id)->update($requestParams);  
          
          } else {
           $message['code']= 1001;
           $message['data']=$id;
           $message['message']= 'user profile is deleted or not exists';
          }
       }
       User::where('id',$id)->update($requestParams);
         
       return $this->errorHandler($message);
    }
    
    public function deleteProfile(Request $request, $id)
    {
       $userRole = $this->authenticateUser($request->user_name, $request->auth);
       
       if ($userRole[0]['role'] != 'admin') {
            $message['code'] = '1010';
            $message['message'] = 'Not Authorized to perform create action';
            
            return $this->errorHandler($message);
       } 
       $user = User::find($id)->toArray();
       if( $user['is_deleted'] == 1) {
            $message['code'] = '1002';
            $message['message'] = 'Given Userid '.$id.' has been deleted already.';
                        
            return $this->errorHandler($message);
       }
       $user['is_deleted'] = 1;
       User::where('id', $id)->update(['is_deleted' => 1]);
                        
       $message['code']= 1001;
       $message['data']= 'User Id '.$id.'has been deleted successfully';
       $message['message']= 'successs';

       return $this->errorHandler($message);
    }

    public function generatePassword($_len)
    {
		$_alphaSmall = 'abcdefghijklmnopqrstuvwxyz';
        $_alphaCaps = strtoupper($_alphaSmall);
        $_numerics = '1234567890';
        $_container = $_alphaSmall . $_alphaCaps . $_numerics;
        $password = '';
        for ($i = 0; $i < $_len; $i++) {
            $_rand = rand(0, strlen($_container) - 1);
            $password .= substr($_container, $_rand, 1);
        }

        return $password;
    }
}
