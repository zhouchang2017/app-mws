<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::table('users')->truncate();
        factory(\App\Models\User::class,25)->create();
        $user = \App\Models\User::first();
        $user->email = '290621352@qq.com';
        $user->save();
    }
}
