<?php

use Illuminate\Database\Seeder;

class RefreshSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $default_timezone = date_default_timezone_get();

        $local_refresh_time = ['06:00', '12:00'];

        $current_date = date('Y-m-d');

        foreach ($local_refresh_time as $time) {
            $date_obj = new DateTime("{$current_date} {$time}:00");

            // local time
            $local_time = $date_obj->format('H:i');

            // get UTC
            $date_obj->setTimezone(new DateTimeZone('UTC'));
            $utc_time = $date_obj->format('H:i');

            $refresh = [
                'local_time'  => $local_time,
                'utc_time'    => $utc_time,
                'timezone'    => $default_timezone,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s')
            ];

            DB::table('refresh_settings')->insert($refresh);
        }
    }
}
