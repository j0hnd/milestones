<?php

use Illuminate\Database\Seeder;

class ProjectTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('project_types')->delete();

        $now = date('Y-m-d H:i:s');
        
        DB::table('project_types')->insert([
            ['project_type_name' => '3XM',   'created_at' => $now, 'updated_at' => $now],
            ['project_type_name' => '3XIM',  'created_at' => $now, 'updated_at' => $now],
            ['project_type_name' => 'SSRIP', 'created_at' => $now, 'updated_at' => $now]
        ]);
    }
}
