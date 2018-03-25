<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\CreateUserFormRequest;
use App\Http\Requests\CreateForgotPasswordLogFormRequest;

use App\User;
use App\UserRoles;
use App\Projects;
use App\ForgotPasswordLogs;

use App\Mail\AddMember;
use App\Mail\ResetPassword;
use App\Mail\TemporaryPassword;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

use DB;


class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['forgot_password', 'reset_password']);
    }

    /**
     * check user's first login
     *
     * @param Request $request
     * @return boolean
     */
    public function check_first_login(Request $request)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {

                $user_info = User::where('uid', \Auth::user()->uid);

                if ($user_info->count()) {
                    $first_login = is_null($user_info->first()->last_login) ? true : false;

                    $response = ['success' => $first_login];
                }

            }

        } catch (Exception $e) {
            throw $e;
        }

        return response()->json($response);
    }

    /**
     * Password reset handler
     *
     * @param Request $request
     * @param string  $hash
     */
    public function reset_password(Request $request, $hash)
    {
        if ($request->isMethod('get')) {
            // decode hash
            $hash = base64_decode($hash);

            // chech hash if valid
            $pwd_request_obj = ForgotPasswordLogs::where(['hash' => $hash, 'is_expired' => 0]);

            if ($pwd_request_obj->count()) {
                $pwd_request = $pwd_request_obj->first();

                // check if hash is already expired
                if (strtotime('now') >= strtotime($pwd_request->log_expiration)) {
                    $user_obj = User::where(['email' => $pwd_request->email, 'is_active' => 1]);

                    if ($user_obj->count()) {
                        $user_info = $user_obj->first();

                        // authenticate hash
                        if (\Hash::check($user_info->hash, $pwd_request->hash)) {
                            DB::beginTransaction();

                            // generate temporary password
                            $temporary_password  = str_random(8);
                            $user_info->password = $temporary_password;

                            if ($user_info->save()) {
                                $pwd_request->is_expired = 1;

                                if ($pwd_request->save()) {
                                    DB::commit();

                                    // send temporary password to user
                                    Mail::to($user_info->email)
                                        ->send(new TemporaryPassword([
                                            'temporary_password' => $temporary_password,
                                        ])
                                    );

                                    return view('Users.temporary_password', ['success' => true]);
                                } else {
                                    DB::rollback();

                                    return view('Users.temporary_password', [
                                        'success' => false,
                                        'message' => 'Error updating password request'
                                    ]);
                                }
                            } else {
                                DB:rollback();

                                return view('Users.temporary_password', [
                                    'success' => false,
                                    'message' => 'Error updating user'
                                ]);
                            }

                        } else {
                            return view('Users.temporary_password', [
                                'success' => false,
                                'message' => 'Forgot password request cannot authenticate.'
                            ]);
                        }

                    }

                } else {
                    return view('Users.temporary_password', [
                        'success' => false,
                        'message' => 'Forgot password request is already expired!'
                    ]);
                }
            } else {
                return view('Users.temporary_password', [
                    'success' => false,
                    'message' => 'Invalid forgot password request'
                ]);
            }
        } else {
            return view('Users.temporary_password', [
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }
    }

    /**
     * Send email link to user to reset their password
     *
     * @param CreateForgotPasswordLogFormRequest $request
     * @return \Illuminate\Http\Response
     */
    public function forgot_password(CreateForgotPasswordLogFormRequest $request)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {

                if ($request->isMethod('post')) {
                    $form = $request->all();

                    // check if email address is existing in database
                    $user_obj = User::where([
                        'email'     => $form['email'],
                        'is_active' => 1
                    ]);

                    if ($user_obj->count()) {
                        // generate hash string for reset password authentication
                        $hash_string = sprintf("##%s##%s##", $form['email'], strtotime('now'));
                        $hash = \Hash::make($hash_string);

                        // expire request in 15mins
                        $insert_data = [
                            'email' => $form['email'],
                            'hash'  => $hash,
                            'log_expiration' => strtotime('+15 minutes')
                        ];

                        DB::beginTransaction();

                        if (ForgotPasswordLogs::create($insert_data)) {
                            // save the hash string to users table
                            $user_info = $user_obj->first();
                            $user_info->hash = $hash_string;

                            if ($user_info->save()) {
                                Mail::to($form['email'])
                                    ->send(new ResetPassword([
                                        'reset_link' => url('/reset/'.base64_encode($hash)),
                                    ])
                                );

                                DB::commit();

                                $response = ['success' => true];
                            } else {
                                DB::rollback();
                            }
                        } else {
                            DB::rollback();
                        }
                    } else {
                        $response['message'] = "Invalid email address";
                    }
                }

            }
        } catch (Exception $e) {
            throw $e;
        }


        return response()->json($response);
    }

    /**
     * Member profile
     *
     * @param CreateUserFormRequest $request
     * @return \Illuminate\Http\Response
     */
    public function user_profile(CreateUserFormRequest $request)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {

                $user_info_obj = User::where('id', \Auth::user()->id);

                if ($request->isMethod('put')) {

                    $form = $request->all();

                    if (count($user_info_obj)) {
                        $user_profile = $user_info_obj->first();

                        $user_profile->name = $form['name'];

                        if (!empty($form['password'])) {
                            $user_profile->password = $form['password'];
                        }

                        if ($user_profile->save()) {
                            $response = ['success' => true];
                        }
                    }

                } else {
                    if (count($user_info_obj)) {
                        $user_profile = $user_info_obj->first();

                        $form = view('partials.Users._user_profile_form', compact('user_profile'))->render();

                        $response = ['success' => true, 'form' => $form];
                    }
                }

            }

        } catch (Exception $e) {
            throw $e;
        }


        return response()->json($response);
    }

    /**
     * Member profile
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {

    }

    /**
     * Update member info
     *
     * @param CreateUserFormRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(CreateUserFormRequest $request)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {

                if ($request->isMethod('put')) {
                    $form = $request->all();

                    $user_info = User::where([
                        'uid'        => $form['uid'],
                        'is_active'  => 1,
                        'is_deleted' => 0
                    ]);

                    if ($user_info->count()) {
                        $user = $user_info->first();

                        $user->name         = $form['name'];
                        $user->email        = $form['email'];
                        $user->user_role_id = $form['user_role_id'];

                        if ($user->save()) {
                            $response = ['success' => true];
                        }
                    }
                }

            }

        } catch (Exception $e) {
            throw $e;
        }


        return response()->json($response);
    }

    /**
     * Get member info
     *
     * @param \Illuminate\Http\Request $request
     * @param uuid $uid
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $uid)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {

                $user_info = User::get_user_info($uid);

                if (count($user_info)) {
                    $user_roles = UserRoles::get_user_roles();

                    $html = view('partials.Users._edit', compact('user_info', 'user_roles'))->render();

                    $response = ['success' => true, 'html' => $html];
                }

            }

        } catch (Exception $e) {
            throw $e;
        }


        return response()->json($response);
    }

    /**
     * Delete member
     *
     * @param \Illuminate\Http\Request $request
     * @param uuid $uid
     * @return \Illuminate\Http\Response
     */
    public function delete_user(Request $request, $uid)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {

                $user_info = User::where([
                    'uid'       => $uid,
                    'is_active' => 1
                ]);

                if ($user_info->count()) {
                    $user = $user_info->first();
                    $user->is_active = 0;
                    $user->is_deleted = 1;
                    $user->deleted_at = date('Y-m-d H:i:s');

                    if ($user->save()) {
                        $response = ['success' => true];
                    }
                }
            }

        } catch (Exception $e) {
            throw $e;
        }


        return response()->json($response);
    }

    /**
     * Get my team members via ajax
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function load_members(Request $request)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {
                $team_members = User::get_team_members();

                $html = view('partials.Users._list', compact('team_members'))->render();

                $response = ['success' => true, 'html' => $html];
            }

        } catch (Exception $e) {
            throw $e;
        }


        return response()->json($response);
    }

    /**
     * Get my team members
     *
     * @return \Illuminate\Http\Response
     */
    public function team_roster()
    {
        $team_members = User::get_team_members();

        return view('Users.my_team', compact('team_members'));
    }

    /**
     * Get details of selected team member
     *
     * @param \Illuminate\Http\Request $request
     * @param uuid $uid
     * @return \Illuminate\Http\Response
     */
    public function select_team_member(Request $request, $uid)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {
                $user_obj = User::where([
                                'users.uid'        => $uid,
                                'users.is_active'  => 1,
                                'users.is_deleted' => 0
                            ])
                            ->join('user_roles', 'user_roles.id', '=', 'users.user_role_id')
                            ->select(['users.uid', 'users.name', 'user_roles.role_name']);

                if ($user_obj->count()) {
                    $user_info = $user_obj->get();
                    $row = view('partials.Users._row_selected_member', compact('user_info'))->render();

                    $response = ['success' => true, 'row' => $row];
                }
            }

        } catch (Exception $e) {
            throw $e;
        }


        return response()->json($response);
    }

    /**
     * Add member.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {
                $user_roles = UserRoles::get_user_roles();

                $html = view('partials.Users._member_form', compact('user_roles'))->render();

                $response = ['success' => true, 'html' => $html];
            }

        } catch (Exception $e) {
            throw $e;
        }


        return response()->json($response);
    }

    /**
     * Save the users information.
     *
     * @param CreateUserFormRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserFormRequest $request)
    {
        try {

            if ($request->ajax()) {

                if ($request->isMethod('post')) {
                    $form = $request->all();

                    $temporary_password = str_random(8);

                    $data['user_role_id'] = $form['user_role_id'];
                    $data['name']         = $form['name'];
                    $data['email']        = $form['email'];
                    $data['password']     = $temporary_password;

                    DB::beginTransaction();

                    if (User::create($data)) {
                        Mail::to($form['email'])
                            ->send(new AddMember([
                                'email'              => $form['email'],
                                'temporary_password' => $temporary_password,
                                'redirect_to'        => URL::to('/')
                            ]));

                        DB::commit();

                        $response = ['success' => true];
                    } else {
                        DB::rollback();
                    }
                }

            }

        } catch (Exception $e) {
            throw $e;
        }

        return response()->json($response);

    }

    public function register(CreateUserFormRequest $request)
    {
        try {
            if ($request->isMethod('post')) {
                $input = $request->all();

                if ($request->validated()) {
                    if (User::create($input)) {
                        return redirect('login')->with('success', true);
                    }
                }
            }
        } catch (Exception $e) {
            throw $e;
        }
    }
}
