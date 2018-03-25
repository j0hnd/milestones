<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

use App\User;
use App\UserRoles;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers {
        attemptLogin as attemptLoginAtAuthenticatesUsers;
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('adminlte::auth.login_withoutvue');
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Login.
     *
     * @return void
     */
    public function login(Request $request)
    {
        try {

            $form = $request->all();

            $attempt = ['email' => $form['email'], 'password' => $form['password'], 'is_active' => 1, 'is_deleted' => 0];

            if (\Auth::attempt($attempt)) {
                $user_role_info = UserRoles::get_user_role(\Auth::user()->user_role_id);

                if (count($user_role_info)) {
                    $request->session()->put('_create', $user_role_info->_create);
                    $request->session()->put('_edit', $user_role_info->_edit);
                    $request->session()->put('_view', $user_role_info->_view);
                    $request->session()->put('_delete', $user_role_info->_delete);
                    $request->session()->put('is_admin', $user_role_info->is_admin);
                } else {
                    $request->session()->put('_create', 0);
                    $request->session()->put('_edit', 0);
                    $request->session()->put('_view', 0);
                    $request->session()->put('_delete', 0);
                    $request->session()->put('is_admin', 0);
                }

                // Authentication passed...
               return redirect()->intended($this->redirectTo);
            } else {
                $request->session()->flash('errors', 'Invalid username/password');
            }

        } catch (Exception $e) {
            throw $e;
        }

        return view('adminlte::auth.login_withoutvue');
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $user_info = User::where(['uid' => \Auth::user()->uid]);

        if ($user_info->count()) {
            $user_info->update(['last_login' => date('Y-m-d H:i:s')]);
        }

        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect('/');
    }

    /**
     * Returns field name to use at login.
     *
     * @return string
     */
    // public function username()
    // {
    //     return config('auth.providers.users.field','email');
    // }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    // protected function attemptLogin(Request $request)
    // {
    //    if ($this->username() === 'email') return ($this->attemptLoginAtAuthenticatesUsers($request));
    //    if ( ! $this->attemptLoginAtAuthenticatesUsers($request)) {
    //        return $this->attempLoginUsingUsernameAsAnEmail($request);
    //    }
    //    return false;
    // }

    /**
     * Attempt to log the user into application using username as an email.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    // protected function attempLoginUsingUsernameAsAnEmail(Request $request)
    // {
    //     return $this->guard()->attempt(
    //         ['email' => $request->input('username'), 'password' => $request->input('password')],
    //         $request->has('remember'));
    // }
}
