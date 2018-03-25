<?php

namespace App\Http\Controllers;

use App\RefreshSettings;

use Illuminate\Http\Request;

class RefreshDashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Check refresh timings
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = ['success' => false];

        try {

            $refresh_settings = RefreshSettings::get();

            $current_time = date('H:i:00');

            if (count($refresh_settings)) {
                foreach ($refresh_settings as $refresh) {

                    if ($refresh->timezone == env('APP_Timezone')) {

                        if ($refresh->local_time == $current_time) {
                            $response = ['success' => true];
                        }

                    } else {
                        if ($refresh->utc_time == $current_time) {
                            $response = ['success' => true];
                        }
                    }

                }
            }

        } catch (\Exception $e) {
            throw $e;
        }


        return response()->json($response);
    }
}
