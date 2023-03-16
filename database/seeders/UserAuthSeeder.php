<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserAuthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_auths')->insert([
            [
                'user_type' => 'admin',
                'username' => 'admin',
                'password' => Hash::make('password123'),
                'created_at' => Carbon::now()
            ]
        ]);
    }
}
