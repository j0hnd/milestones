<?php

use Illuminate\Database\Seeder;

class UserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_roles')->delete();

        $now = date('Y-m-d H:i:s');

        DB::table('user_roles')->insert([
            [
                'role_name'       => 'Manager',
                '_create'         => 1,
                '_edit'           => 1,
                '_view'           => 1,
                '_delete'         => 0,
                'is_notify_email' => 1,
                'is_admin'        => 0,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'role_name'       => 'Team Leader',
                '_create'         => 1,
                '_edit'           => 1,
                '_view'           => 1,
                '_delete'         => 0,
                'is_notify_email' => 0,
                'is_admin'        => 0,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'role_name'       => 'Project Manager',
                '_create'         => 1,
                '_edit'           => 1,
                '_view'           => 1,
                '_delete'         => 1,
                'is_notify_email' => 1,
                'is_admin'        => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
        ]);
    }
}
