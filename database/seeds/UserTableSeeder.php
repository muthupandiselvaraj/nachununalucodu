<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;

class UserTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function run()
     {
         User::create([
             'name' => 'Admin',
             'email' => 'purushoth.krishna@aspiresys.com',
             'auth' => 'KN1gjMYsDSiVFpC',
             'password'=> 'password'
         ]);

        

    }

}
