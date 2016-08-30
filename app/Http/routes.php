<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->get('foo/', function () {
    return 'Hello World';
    
});
$app->post('v1/user', 'UserController@create');
$app->get('v1/user/{id}', 'UserController@viewProfile');
$app->put('v1/user/{id}', 'UserController@editProfile');
$app->delete('v1/user/{id}', 'UserController@deleteProfile');

$app->get('v1/team', 'TeamController@create');

$app->get('v1/team_member/{id}', 'Team_MemberController@create');


