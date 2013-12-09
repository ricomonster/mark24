<?php

class UserTableSeeder extends Seeder
{
    public function run()
    {
        $user = new User;
        $user->account_type = 0;
        $user->name = 'Super User Administrator';
        $user->firstname = 'Super User';
        $user->lastname = 'Administrator';
        $user->username = 'administrator';
        $user->email = 'admin@elinet.net';
        $user->password = Hash::make('adminpassword');
        $user->save();
    }
}