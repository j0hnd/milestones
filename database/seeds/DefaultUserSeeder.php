<?php

use Illuminate\Database\Seeder;
use App\User;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'name'         => 'Admin',
            'email'        => 'ad@min.com',
            'password'     => 'admin',
            'user_role_id' => 1,
            'is_active'    => 1,
            'is_deleted'   => 0
        ];

        User::create($data);
    }
}
