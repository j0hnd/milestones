<?php

/*
 * Taken from
 * https://github.com/laravel/framework/blob/5.3/src/Illuminate/Auth/Console/stubs/make/controllers/HomeController.stub
 */

namespace App\Http\Controllers;

use App\Projects;
use App\UserRoles;
use App\ProjectTypes;

use App\Http\Requests\CreateUserRoleFormRequest;
use App\Http\Requests\CreateProjectTypeFormRequest;

use App\Http\Requests;
use Illuminate\Http\Request;


/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index()
    {
        $dashboard_obj = Projects::get_project_summary();

        $summary     = $dashboard_obj['project_summary'];
        $raw_summary = $dashboard_obj['raw_summary'];

        return view('Home.dashboard', compact('summary', 'raw_summary'));
    }

    /**
     * Refresh dashboard
     *
     * @param Request $request
     * @return json
     */
    public function load_dashboard(Request $request)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {

                $dashboard_obj = Projects::get_project_summary();

                $summary     = $dashboard_obj['project_summary'];
                $raw_summary = $dashboard_obj['raw_summary'];

                $html = view('partials.Home._list', compact('summary', 'raw_summary'))->render();

                $response = ['success' => true, 'html' => $html, 'data' => ['count' => count($summary)]];

            }

        } catch (Exception $e) {
            throw $e;
        }


        return response()->json($response);
    }

    /**
     * Show all project types.
     *
     * @return Response
     */
    public function project_types()
    {
        $project_types = ProjectTypes::get_project_types();

        return view('Home.project_types', compact('project_types'));
    }

    /**
     * Delete Project type.
     *
     * @param CreateProjectTypeFormRequest $request
     * @return \Illuminate\Http\Response
     */
    public function delete_project_type(CreateProjectTypeFormRequest $request, $id)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {
                $project_type_info = ProjectTypes::where('id', $id);

                if (count($project_type_info)) {
                    $project_type = $project_type_info->first();
                    $project_type->is_active = 0;

                    if ($project_type->save()) {
                        $project_types = ProjectTypes::get_project_types();

                        if (count($project_types)) {
                            foreach ($project_types as $ptype) {
                                $data['project_type_id']   = $ptype->id;
                                $data['project_type_name'] = $ptype->project_type_name;
                            }

                            $list =  view('partials.Home._project_type_list', compact('project_types'))->render();

                            $response = ['success' => true, 'list' => $list];
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
     * Edit Project Types.
     *
     * @param CreateProjectTypeFormRequest $request
     * @return \Illuminate\Http\Response
     */
    public function edit_project_type(CreateProjectTypeFormRequest $request, $id)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {

                if ($request->isMethod('put')) {
                    $form = $request->all();

                    $project_type_info = ProjectTypes::where('id', $form['id']);

                    if (count($project_type_info)) {
                        $project_type = $project_type_info->first();
                        $project_type->project_type_name = $form['project_type_name'];

                        if ($project_type->save()) {
                            $project_types = ProjectTypes::get_project_types();

                            if (count($project_types)) {
                                foreach ($project_types as $ptype) {
                                    $data['project_type_id']   = $ptype->id;
                                    $data['project_type_name'] = $ptype->project_type_name;
                                }

                                $list =  view('partials.Home._project_type_list', compact('project_types'))->render();

                                $response = ['success' => true, 'list' => $list];
                            }
                        }
                    }

                } else {
                    $project_type_info = ProjectTypes::get_project_type($id);

                    if (count($project_type_info)) {
                        $form = view('partials.Home._edit_project_type', compact('project_type_info'))->render();

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
     * Save project types.
     *
     * @param CreateProjectTypeFormRequest $request
     * @return \Illuminate\Http\Response
     */
    public function save_project_type(CreateProjectTypeFormRequest $request)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {

                if ($request->isMethod('post')) {
                    if (ProjectTypes::create(['project_type_name' => $request->get('project_type_name')])) {
                        $project_types = ProjectTypes::get_project_types();

                        $list =  view('partials.Home._project_type_list', compact('project_types'))->render();

                        $response = ['success' => true, 'list' => $list];
                    }
                }

            }

        } catch (Exception $e) {
            throw $e;
        }


        return response()->json($response);
    }

    /**
     * Show all user roles.
     *
     * @return Response
     */
    public function user_roles()
    {
        $user_roles = UserRoles::get_user_roles();

        return view('Home.user_roles', compact('user_roles'));
    }

    /**
     * Save User Roles.
     *
     * @param CreateUserRoleFormRequest $request
     * @return \Illuminate\Http\Response
     */
    public function save_user_role(CreateUserRoleFormRequest $request)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {

                if ($request->isMethod('post')) {

                    $form = $request->all();

                    $data = [
                        'role_name'       => $form['role_name'],
                        '_create'         => isset($form['_create']) ? 1 : 0,
                        '_edit'           => isset($form['_edit']) ? 1 : 0,
                        '_view'           => isset($form['_view']) ? 1 : 0,
                        '_delete'         => isset($form['_delete']) ? 1 : 0,
                        'is_notify_email' => isset($form['is_notify_email']) ? 1 : 0,
                        'is_admin'        => isset($form['is_admin']) ? 1 : 0,
                    ];

                    if (UserRoles::create($data)) {
                        $user_roles = UserRoles::get_user_roles();

                        $list =  view('partials.Home._user_role_list', compact('user_roles'))->render();

                        $response = ['success' => true, 'list' => $list];
                    }

                }

            }

        } catch (Exception $e) {
            throw $e;
        }

        return response()->json($response);
    }

    /**
     * Delete User Roles.
     *
     * @param CreateUserRoleFormRequest $request
     * @return \Illuminate\Http\Response
     */
    public function delete_user_role(CreateUserRoleFormRequest $request, $id)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {
                $user_role_info = UserRoles::where('id', $id);

                if (count($user_role_info)) {
                    $user_role = $user_role_info->first();
                    $user_role->is_active = 0;

                    if ($user_role->save()) {
                        $user_roles = UserRoles::get_user_roles();

                        $list =  view('partials.Home._user_role_list', compact('user_roles'))->render();

                        $response = ['success' => true, 'list' => $list];
                    }
                }
            }

        } catch (Exception $e) {
            throw $e;
        }


        return response()->json($response);
    }

    /**
     * Edit User Roles.
     *
     * @param CreateUserRoleFormRequest $request
     * @return \Illuminate\Http\Response
     */
    public function edit_user_role(CreateUserRoleFormRequest $request, $id)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {

                if ($request->isMethod('put')) {
                    $form = $request->all();

                    $user_role_info = UserRoles::where('id', $form['id']);

                    if (count($user_role_info)) {
                        $user_role = $user_role_info->first();
                        $user_role->role_name        = $form['role_name'];
                        $user_role->_create          = isset($form['_create']) ? 1 : 0;
                        $user_role->_edit            = isset($form['_edit']) ? 1 : 0;
                        $user_role->_view            = isset($form['_view']) ? 1 : 0;
                        $user_role->_delete          = isset($form['_delete']) ? 1 : 0;
                        $user_role->is_notify_email  = isset($form['is_notify_email']) ? 1 : 0;
                        $user_role->is_admin         = isset($form['is_admin']) ? 1 : 0;

                        if ($user_role->save()) {
                            $user_roles = UserRoles::get_user_roles();

                            $list =  view('partials.Home._user_role_list', compact('user_roles'))->render();

                            $response = ['success' => true, 'list' => $list];
                        }
                    }

                } else {
                    $user_role_info = UserRoles::get_user_role($id);

                    if (count($user_role_info)) {
                        $form = view('partials.Home._edit_user_role', compact('user_role_info'))->render();

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
     * Searches a project
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax() and $request->isMethod('post')) {
                $search_term = $request->get('search_term');

                $dashboard_obj = Projects::get_project_summary($search_term);

                $summary     = $dashboard_obj['project_summary'];
                $raw_summary = $dashboard_obj['raw_summary'];

                $html = view('partials.Home._list', compact('summary'))->render();

                $response = ['success' => true, 'html' => $html, 'data' => ['count' => count($summary)]];
            }

        } catch (Exception $e) {
            throw $e;
        }

        return response()->json($response);
    }
}
