<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use phpDocumentor\Reflection\Project;
use Webpatser\Uuid\Uuid;

use DB, Validator;

// models
use App\Projects;
use App\ProjectTypes;
use App\ProjectMilestones;
use App\ProjectMembers;
use App\User;
use App\Comments;
use App\MilestoneLogs;
use App\ProjectLogs;

// form requests
use App\Http\Requests\CreateProjectFormRequest;
use App\Http\Requests\CreateProjectMilestoneFormRequest;
use App\Http\Requests\CreateCommentFormRequest;

// mail
use App\Mail\NotifyChanges;


class ProjectsController extends Controller
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
     * List all the projects.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get project type list
        $project_types = ProjectTypes::where('is_active', 1)->get();

        // $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        // $parsed_url  = parse_url($actual_link);
        //
        // // get all projects
        // if (isset($parsed_url['query'])) {
        //     list($param, $page) = explode('=', $parsed_url['query']);
        //
        //     $projects = Projects::get_active_projects($page);
        // } else {
        //     $projects = Projects::get_active_projects();
        // }

        // get all projects
        $projects = Projects::get_active_projects();


        return view('Projects.index', compact('projects', 'project_types'));
    }


    /**
     * Load project details to form
     *
     * @param  Request $request
     * @param  uuid  $pid
     * @return \Illuminate\Http\Response
     */
    public function load_project_details(Request $request, $pid)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {
                // project details
                $project_details = Projects::get_project_details($pid);

                // get project type list
                $project_types = ProjectTypes::where('is_active', 1)->get();

                $html = view('partials.Projects._project_details_form', compact('project_details', 'project_types'))->render();

                $response = ['success' => true, 'html' => $html];
            }

        } catch (Exception $e) {
            throw $e;
        }


        return response()->json($response);
    }

    /**
     * Load project milestones in form
     *
     * @param  Request $request
     * @param  uuid  $pid
     * @return \Illuminate\Http\Response
     */
    public function load_project_milestones(Request $request, $pid)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {
                // project details
                $project_details = Projects::get_project_details($pid);

                $html = view('partials.Projects._milestones_form', compact('project_details'))->render();

                $response = ['success' => true, 'html' => $html];
            }

        } catch (Exception $e) {
            throw $e;
        }


        return response()->json($response);
    }

    /**
     * Tag project as deleted
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete_project(Request $request)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {
                if ($request->isMethod('post') and $request->has('id')) {

                    $pid = $request->input('id');

                    DB::beginTransaction();

                    $project_details_obj = Projects::where(['pid' => $pid, 'is_active' => 1, 'is_deleted' => 0]);

                    if ($project_details_obj->count()) {
                        $update_projects_data = [
                            'is_active'  => 0,
                            'is_deleted' => 1,
                            'deleted_at' => date('Y-m-d H:i:s')
                        ];

                        if ($project_details_obj->update($update_projects_data)) {
                            $milestones_obj = ProjectMilestones::where(['pid' => $pid, 'is_active' => 1]);

                            if ($milestones_obj->count()) {

                                if ($milestones_obj->update(['is_active' => 0])) {
                                    // get all projects
                                    $projects = Projects::get_active_projects();

                                    $html = view('partials.Projects._list', compact('projects'))->render();

                                    $response = ['success' => true, 'message' => 'You have successfully deleted a project', 'html' => $html];

                                    DB::commit();
                                } else {
                                    DB::rollback();

                                    $response['message'] = "Something went wrong in deleting milestones";
                                }
                            } else {
                                DB::rollback();

                                $response['message'] = "Something went wrong in deleting milestones";
                            }
                        } else {
                            DB::rollback();

                            $response['message'] = "Something went wrong in deleting project details";
                        }
                    } else {
                        DB::rollback();

                        $response['message'] = "Something went wrong in deleting project details";
                    }

                }
            }

        } catch (Exception $e) {
            throw $e;
        }


        return response()->json($response);
    }

    /**
     * Upload project.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
        $response = ['success' => false];

        try {

            if ($request->hasFile('csv') and $request->file('csv')->isValid()) {

                $flag = true;

                $f = fopen($request->file('csv'), 'r');

                while (($data = fgetcsv($f, 10000, ",")) !== false) {
                    if ($flag) {
                        $flag = false; continue;
                    }

                    // check if project code is existing
                    $project_name_value = trim($data[1]);
                    if (!Projects::is_project_code_unique($project_name_value)) {
                        continue;
                    }

                    // validate project milestone dates
                    $project_type_value = trim($data[3]);
                    if (is_null(ProjectTypes::get_project_type_id($project_type_value))) {
                        $response['message'] = "Invalid Project Type ({$project_type_value})";
                        return response()->json($response);
                    } else {
                        $project_type_id = ProjectTypes::get_project_type_id($project_type_value);
                    }

                    // validate project manager
                    $project_manager_value = trim($data[4]);
                    $project_manager = User::get_project_manager($project_manager_value);

                    $project_data = [
                        'project_type_id'      => $project_type_id,
                        'project_name'         => trim($data[0]),
                        'project_code'         => trim($data[1]),
                        'description'          => trim($data[2]),
                        'is_visible_dashboard' => 1,
                        'is_active'            => 1
                    ];

                    DB::beginTransaction();

                    $project_id = Projects::create($project_data)->id;

                    if ($project_id) {
                        $project_info = Projects::find($project_id);

                        // save project project manager
                        if (!is_null($project_manager)) {
                            ProjectMembers::create([
                                'pid' => $project_info->pid,
                                'uid' => $project_manager->uid,
                                'is_owner'  => 0,
                                'is_active' => 1
                            ]);
                        } else {
                            $response['message'] = "Project Manager not registered ({$project_manager_value})";

                            DB::rollback();

                            return response()->json($response);
                        }

                        // save project milestones
                        $milestones['pid']       = $project_info->pid;
                        $milestones['is_active'] = 1;

                        $announcement_date         = Projects::convert_to_valid_date($data[5]);
                        $scoping_design_date       = Projects::convert_to_valid_date($data[6]);
                        $advertising_date          = Projects::convert_to_valid_date($data[7]);
                        $award_date                = Projects::convert_to_valid_date($data[8]);
                        $commencement_date         = Projects::convert_to_valid_date($data[9]);
                        $_20_percent_complete_date = Projects::convert_to_valid_date($data[10]);
                        $_40_percent_complete_date = Projects::convert_to_valid_date($data[11]);
                        $_60_percent_complete_date = Projects::convert_to_valid_date($data[12]);
                        $_80_percent_complete_date = Projects::convert_to_valid_date($data[13]);
                        $project_completion_date   = Projects::convert_to_valid_date($data[14]);


                        if (!empty($data[5]) and Projects::validate_date($announcement_date, 'd/m/Y')) {
                            $milestones['announcement'] = date('Y-m-d', strtotime(str_replace('/', '-', $announcement_date)));
                        }

                        if (!empty($data[6]) and Projects::validate_date($scoping_design_date, 'd/m/Y')) {
                            $milestones['scoping_design'] = date('Y-m-d', strtotime(str_replace('/', '-', $scoping_design_date)));
                        }

                        if (!empty($data[7]) and Projects::validate_date($advertising_date, 'd/m/Y')) {
                            $milestones['advertising'] = date('Y-m-d', strtotime(str_replace('/', '-', $advertising_date)));
                        }

                        if (!empty($data[8]) and Projects::validate_date($award_date, 'd/m/Y')) {
                            $milestones['award'] = date('Y-m-d', strtotime(str_replace('/', '-', $award_date)));
                        }

                        if (!empty($data[9]) and Projects::validate_date($commencement_date, 'd/m/Y')) {
                            $milestones['commencement'] = date('Y-m-d', strtotime(str_replace('/', '-', $commencement_date)));
                        }

                        if (!empty($data[10]) and Projects::validate_date($_20_percent_complete_date, 'd/m/Y')) {
                            $milestones['20_percent_complete'] = date('Y-m-d', strtotime(str_replace('/', '-', $_20_percent_complete_date)));
                        }

                        if (!empty($data[11]) and Projects::validate_date($_40_percent_complete_date, 'd/m/Y')) {
                            $milestones['40_percent_complete'] = date('Y-m-d', strtotime(str_replace('/', '-', $_40_percent_complete_date)));
                        }

                        if (!empty($data[12]) and Projects::validate_date($_60_percent_complete_date, 'd/m/Y')) {
                            $milestones['60_percent_complete'] = date('Y-m-d', strtotime(str_replace('/', '-', $_60_percent_complete_date)));
                        }

                        if (!empty($data[13]) and Projects::validate_date($_80_percent_complete_date, 'd/m/Y')) {
                            $milestones['80_percent_complete'] = date('Y-m-d', strtotime(str_replace('/', '-', $_80_percent_complete_date)));
                        }

                        if (!empty($data[14]) and Projects::validate_date($project_completion_date, 'd/m/Y')) {
                            $milestones['practical_completion'] = date('Y-m-d', strtotime(str_replace('/', '-', $project_completion_date)));
                        }

                        if (ProjectMilestones::create($milestones)) {
                            DB::commit();

                            unset($milestones);

                            $milestones = null;

                            $response = ['success' => true];
                        } else {
                            DB::rollback();
                        }
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

    /**
     * List comments.
     *
     * @param \Illuminate\Http\Request $request
     * @param uuid $mid
     * @return \Illuminate\Http\Response
     */
    public function load_comments(Request $request, $mid)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {

                $comments = MilestoneComments::get_comments($mid);

                $html = view('partials.Projects._comments', compact('comments'))->render();

                $response = ['success' => true, 'html' => $html];

            }

        } catch (Exception $e) {
            throw $e;
        }


        return response()->json($response);
    }

    public function reply_to_comment(CreateCommentFormRequest $request)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {
                if ($request->isMethod('post')) {

                    $form = $request->all();

                    if (isset($form['pid'])) {
                        $object_name = 'details';
                        $object_id   = $form['pid'];
                    } else {
                        $object_name = 'milestones';
                        $object_id   = $form['mid'];
                    }

                    $data['object_name'] = $object_name;
                    $data['object_id']   = $object_id;
                    $data['uid']         = \Auth::user()->uid;
                    $data['log_id']      = $form['log_id'];
                    $data['comment']     = $form['comment'];

                    if (Comments::create($data)) {
                        $response = ['success' => true];
                    }

                }
            }

        } catch (\Exception $e) {
            throw $e;
        }


        return response()->json($response);
    }

    /**
     * Save comments.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function comment_add(Request $request)
    {
        $response = ['success' => false];

        try {
            if ($request->ajax()) {
                if ($request->isMethod('post')) {
                    $form = $request->all();

                    parse_str($form['comment'], $comment_form);

                    if (isset($form['project'])) {
                        parse_str($form['project'], $project_form);
                    }

                    if (isset($form['milestones'])) {
                        parse_str($form['milestones'], $milestones_form);
                    }

                    // validate comment form
                    $validator = Validator::make($comment_form, [
                        'comment' => 'required'
                    ]);

                    if ($validator->fails()) {
                        $errors = null;
                        foreach ($validator->errors()->all() as $error) {
                            $errors['errors'][] = $error;
                        }

                        return response()->json($errors, 422);
                    } else {

                        if ($comment_form['form'] == 'milestones') {
                            // update milestones
                            $milestones = ProjectMilestones::where(['mid' => $milestones_form['mid']]);

                            if ($milestones->count()) {
                                DB::beginTransaction();

                                $update_response = $milestones->update([
                                    'announcement'            => empty($milestones_form['announcement']) ? null : date('Y-m-d', strtotime(str_replace('/', '-', $milestones_form['announcement']))),
                                    'scoping_design'          => empty($milestones_form['scoping_design']) ? null : date('Y-m-d', strtotime(str_replace('/', '-', $milestones_form['scoping_design']))),
                                    'advertising'             => empty($milestones_form['advertising']) ? null : date('Y-m-d', strtotime(str_replace('/', '-', $milestones_form['advertising']))),
                                    'award'                   => empty($milestones_form['award']) ? null : date('Y-m-d', strtotime(str_replace('/', '-', $milestones_form['award']))),
                                    'commencement'            => empty($milestones_form['commencement']) ? null : date('Y-m-d', strtotime(str_replace('/', '-', $milestones_form['commencement']))),
                                    '20_percent_complete'     => empty($milestones_form['20_percent_complete']) ? null : date('Y-m-d', strtotime(str_replace('/', '-', $milestones_form['20_percent_complete']))),
                                    '40_percent_complete'     => empty($milestones_form['40_percent_complete']) ? null : date('Y-m-d', strtotime(str_replace('/', '-', $milestones_form['40_percent_complete']))),
                                    '60_percent_complete'     => empty($milestones_form['60_percent_complete']) ? null : date('Y-m-d', strtotime(str_replace('/', '-', $milestones_form['60_percent_complete']))),
                                    '80_percent_complete'     => empty($milestones_form['80_percent_complete']) ? null : date('Y-m-d', strtotime(str_replace('/', '-', $milestones_form['80_percent_complete']))),
                                    'practical_completion'    => empty($milestones_form['practical_completion']) ? null : date('Y-m-d', strtotime(str_replace('/', '-', $milestones_form['practical_completion']))),

                                    'is_announcement'         => isset($milestones_form['is_announcement']) ? 1 : 0,
                                    'is_scoping_design'       => isset($milestones_form['is_scoping_design']) ? 1 : 0,
                                    'is_advertising'          => isset($milestones_form['is_advertising']) ? 1 : 0,
                                    'is_award'                => isset($milestones_form['is_award']) ? 1 : 0,
                                    'is_commencement'         => isset($milestones_form['is_commencement']) ? 1 : 0,
                                    'is_20_percent_complete'  => isset($milestones_form['is_20_percent_complete']) ? 1 : 0,
                                    'is_40_percent_complete'  => isset($milestones_form['is_40_percent_complete']) ? 1 : 0,
                                    'is_60_percent_complete'  => isset($milestones_form['is_60_percent_complete']) ? 1 : 0,
                                    'is_80_percent_complete'  => isset($milestones_form['is_80_percent_complete']) ? 1 : 0,
                                    'is_practical_completion' => isset($milestones_form['is_practical_completion']) ? 1 : 0,

                                    'announcement_completed_at'         => isset($milestones_form['is_announcement']) ? date('Y-m-d') : null,
                                    'scoping_design_completed_at'       => isset($milestones_form['is_scoping_design']) ? date('Y-m-d') : null,
                                    'advertising_completed_at'          => isset($milestones_form['is_advertising']) ? date('Y-m-d') : null,
                                    'award_completed_at'                => isset($milestones_form['is_award']) ? date('Y-m-d') : null,
                                    'commencement_completed_at'         => isset($milestones_form['is_commencement']) ? date('Y-m-d') : null,
                                    '20_percent_completed_at'           => isset($milestones_form['is_20_percent_complete']) ? date('Y-m-d') : null,
                                    '40_percent_completed_at'           => isset($milestones_form['is_40_percent_complete']) ? date('Y-m-d') : null,
                                    '60_percent_completed_at'           => isset($milestones_form['is_60_percent_complete']) ? date('Y-m-d') : null,
                                    '80_percent_completed_at'           => isset($milestones_form['is_80_percent_complete']) ? date('Y-m-d') : null,
                                    'practical_completion_completed_at' => isset($milestones_form['is_practical_completion']) ? date('Y-m-d') : null,
                                ]);

                                if ($update_response) {
                                    // add to logs
                                    if (isset($milestones_form['changes'])) {
                                        $changes = json_decode($milestones_form['changes'], true);

                                        if (count($changes)) {
                                            $log_id = Uuid::generate()->string;

                                            foreach ($changes as $i => $change) {
                                                list($field, $value, $original) = explode('##', $change);

                                                if ($original == "") {
                                                    $original = 'empty';
                                                }

                                                if ($field == 'announcement-completed' and $value == 1) {
                                                    $field = "Announcement";
                                                    $value = "completed";
                                                    $original = "not completed";
                                                } else if ($field == 'announcement-completed' and $value == 0) {
                                                    $field = "Announcement";
                                                    $value = "not completed";
                                                    $original = "completed";

                                                } else if ($field == 'scoping-design-completed' and $value == 1) {
                                                    $field = "Scoping & Design";
                                                    $value = "completed";
                                                    $original = "not completed";
                                                } else if ($field == 'scoping-design-completed' and $value == 0) {
                                                    $field = "Scoping & Design";
                                                    $value = "not completed";
                                                    $original = "completed";

                                                } else if ($field == 'advertising-completed' and $value == 1) {
                                                    $field = "Advertising";
                                                    $value = "completed";
                                                    $original = "not completed";
                                                } else if ($field == 'advertising-completed' and $value == 0) {
                                                    $field = "Advertising";
                                                    $value = "not completed";
                                                    $original = "completed";

                                                } else if ($field == 'award-completed' and $value == 1) {
                                                    $field = "Award";
                                                    $value = "completed";
                                                    $original = "not completed";
                                                } else if ($field == 'award-completed' and $value == 0) {
                                                    $field = "Award";
                                                    $value = "not completed";
                                                    $original = "completed";

                                                } else if ($field == 'commencement-completed' and $value == 1) {
                                                    $field = "Commencement";
                                                    $value = "completed";
                                                    $original = "not completed";
                                                } else if ($field == 'commencement-completed' and $value == 0) {
                                                    $field = "Commencement";
                                                    $value = "not completed";
                                                    $original = "completed";

                                                } else if ($field == '20-percent-completed' and $value == 1) {
                                                    $field = "20% Complete";
                                                    $value = "completed";
                                                    $original = "not completed";
                                                } else if ($field == '20-percent-completed' and $value == 0) {
                                                    $field = "20% Complete";
                                                    $value = "not completed";
                                                    $original = "completed";

                                                } else if ($field == '40-percent-completed' and $value == 1) {
                                                    $field = "40% Complete";
                                                    $value = "completed";
                                                    $original = "not completed";
                                                } else if ($field == '40-percent-completed' and $value == 0) {
                                                    $field = "40% Complete";
                                                    $value = "not completed";
                                                    $original = "completed";

                                                } else if ($field == '60-percent-completed' and $value == 1) {
                                                    $field = "60% Complete";
                                                    $value = "completed";
                                                    $original = "not completed";
                                                } else if ($field == '60-percent-completed' and $value == 0) {
                                                    $field = "60% Complete";
                                                    $value = "not completed";
                                                    $original = "completed";

                                                } else if ($field == '80-percent-completed' and $value == 1) {
                                                    $field = "80% Complete";
                                                    $value = "completed";
                                                    $original = "not completed";
                                                } else if ($field == '80-percent-completed' and $value == 0) {
                                                    $field = "80% Ccomplete";
                                                    $value = "not completed";
                                                    $original = "completed";

                                                } else if ($field == 'practical-completion-completed' and $value == 1) {
                                                    $field = "Projcect Completion";
                                                    $value = "completed";
                                                    $original = "not completed";
                                                } else if ($field == 'practical-completion-completed' and $value == 0) {
                                                    $field = "Project Completion";
                                                    $value = "not completed";
                                                    $original = "completed";
                                                } else {
                                                    switch ($field) {
                                                        case "announcement": $field = "Announcement"; break;
                                                        case "scoping_design": $field = "Scoping & Design"; break;
                                                        case "advertising": $field = "Advertising"; break;
                                                        case "award": $field = "Award"; break;
                                                        case "commencement": $field = "Commencement"; break;
                                                        case "20_percent_complete": $field = "20% Complete"; break;
                                                        case "40_percent_complete": $field = "40% Complete"; break;
                                                        case "60_percent_complete": $field = "60% Complete"; break;
                                                        case "80_percent_complete": $field = "80% Complete"; break;
                                                        case "practicalcompletion": $field = "Project Completion"; break;
                                                    }
                                                }

                                                if (true === strtotime($value)) {
                                                    $value = date('Y-m-d', strtotime(str_replace('/', '-', $value)));
                                                }

                                                if (true === strtotime($original)) {
                                                    $original = date('Y-m-d', strtotime(str_replace('/', '-', $original)));
                                                }

                                                if (true === strtotime($value) and true === strtotime($original)) {
                                                    $value    = date('d/m/Y', strtotime($value));
                                                    $original = date('d/m/Y', strtotime($original));
                                                    $comment  = "{$field} changed from ".$original." to {$value}";
                                                } else {
                                                    $comment = "{$field} changed from ".$original." to {$value}";
                                                }

                                                MilestoneLogs::create([
                                                    'mid'        => $milestones_form['mid'],
                                                    'log_id'     => $log_id,
                                                    'field_name' => $field,
                                                    'old_value'  => $original,
                                                    'new_value'  => $value,
                                                    'comment'    => $comment,
                                                    'updated_by' => \Auth::user()->uid
                                                ]);

                                                // save comments
                                                $data['object_name'] = 'milestones';
                                                $data['object_id']   = $comment_form['mid'];;
                                                $data['uid']         = \Auth::user()->uid;
                                                $data['log_id']      = $log_id;
                                                $data['comment']     = $comment_form['comment'];

                                                if (Comments::create($data)) {
                                                    DB::commit();

                                                    // send changes notification
                                                    $changes   = MilestoneLogs::get_changes($milestones_form['mid'], $log_id);
                                                    $to_notify = User::get_users_to_notify();

                                                    if (!is_null($to_notify)) {
                                                        Mail::to($to_notify->toArray())
                                                            ->send(new NotifyChanges([
                                                                'project_name' => Projects::get_project_name(MilestoneLogs::get_project_id($milestones_form['mid'])),
                                                                'changes' => $changes
                                                            ])
                                                        );
                                                    }

                                                    $response = ['success' => true];
                                                }
                                            }
                                        } else {
                                            DB::rollback();
                                        }
                                    } else {
                                        DB::rollback();
                                    }

                                } else {
                                    DB::rollback();
                                }

                            } else {
                                $response['message'] = "Invalid request";
                            }
                        } else {
                            // update project
                            $project_info = Projects::where([
                                'pid'        => $project_form['pid'],
                                'is_active'  => 1,
                                'is_deleted' => 0
                            ]);

                            if ($project_info->count()) {
                                $project = $project_info->first();

                                DB::beginTransaction();

                                $project->project_name         = $project_form['project_name'];
                                $project->project_code         = strtoupper($project_form['project_code']);
                                $project->project_type_id      = $project_form['project_type_id'];
                                $project->description          = $project_form['description'];

                                if (isset($project_form['is_visible_dashboard'])) {
                                    $project->is_visible_dashboard = 1;
                                } else {
                                    $project->is_visible_dashboard = 0;
                                }

                                if ($project->save()) {
                                    // update project members
                                    if (isset($project_form['members'])) {
                                        $members = $project_form['members'];

                                        if (count($members)) {
                                            // clean up current members
                                            DB::table('project_members')->where('pid', $project_form['pid'])->delete();

                                            // set member details
                                            foreach ($members['uid'] as $i => $uid) {
                                                $updated['pid']        = $project_form['pid'];
                                                $updated['uid']        = $uid;
                                                $updated['is_owner']   = $members['is_owner'][$i];
                                                $updated['is_active']  = 1;
                                                $updated['created_at'] = date('Y-m-d H:i:s');
                                                $updated['updated_at'] = date('Y-m-d H:i:s');

                                                if (!ProjectMembers::create($updated)) {
                                                    DB::rollback();
                                                }
                                            }
                                        }
                                    }

                                    // log_changes
                                    if (isset($project_form['changes'])) {
                                        $changes = json_decode($project_form['changes'], true);
                                    } else {
                                        $changes = null;
                                    }

                                    if (count($changes)) {
                                        $log_id = Uuid::generate()->string;

                                        foreach ($changes as $i => $change) {
                                            list($field, $value, $original) = explode('##', $change);

                                            if (empty($original)) {
                                                $original = "empty";
                                            }

                                            if ($field == 'project_type_id') {
                                                $_field = "project type";
                                                $comment = "{$_field} changes from ".ProjectTypes::get_project_type_name($original)." to ".ProjectTypes::get_project_type_name($value);
                                            } elseif ($field == 'is_visible_dashboard') {
                                                $_field = "project visibility";

                                                $original = $original == 1 ? "visible" : "not visible";
                                                $value    = $value == 1 ? "visible" : "not visible";

                                                $comment = "{$_field} changes from {$original} to {$value}";
                                            } else {
                                                $_field = str_replace('_', ' ', $field);
                                                $_field = ucwords($_field);
                                                $comment = "{$_field} changes from ".$original." to {$value}";
                                            }

                                            ProjectLogs::create([
                                                'pid'        => $project_form['pid'],
                                                'log_id'     => $log_id,
                                                'field_name' => $field,
                                                'old_value'  => $original,
                                                'new_value'  => $value,
                                                'comment'    => $comment,
                                                'updated_by' => \Auth::user()->uid
                                            ]);
                                        }

                                        // save comments
                                        $data['object_name'] = 'details';
                                        $data['object_id']   = $comment_form['pid'];
                                        $data['uid']         = \Auth::user()->uid;
                                        $data['log_id']      = $log_id;
                                        $data['comment']     = $comment_form['comment'];

                                        if (Comments::create($data)) {
                                            // send changes notification
                                            $changes   = ProjectLogs::get_changes($comment_form['pid'], $log_id);
                                            $to_notify = User::get_users_to_notify();

                                            if (!is_null($to_notify)) {
                                                Mail::to($to_notify->toArray())
                                                    ->send(new NotifyChanges(['project_name' => Projects::get_project_name($comment_form['pid']), 'changes' => $changes, ])
                                                );
                                            }

                                            DB::commit();

                                            $response = ['success' => true];
                                        } else {
                                            DB::rollback();
                                        }
                                    } else {
                                        DB::rollback();
                                    }
                                } else {
                                    DB::rollback();
                                }
                            }

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
     * Display project details
     *
     * @param \Illuminate\Http\Request $request
     * @param uuid $pid
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $pid)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {
                $project_details = Projects::get_project_details($pid);

                if (!is_null($project_details)) {

                    // get project type list
                    $project_types = ProjectTypes::where('is_active', 1)->get();

                    // get changes
                    $changes = Projects::get_changes($pid);

                    // current user
                    $uid = \Auth::user()->uid;

                    $html = view('partials.Projects._project_details', compact('uid', 'project_details', 'project_types', 'changes'))->render();

                    $response = ['success' => true, 'html' => $html];
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

                $projects = Projects::get_active_projects($search_term);

                $html = view('partials.Projects._list', compact('projects'))->render();

                $response = ['success' => true, 'html' => $html, 'data' => ['count' => count($projects)]];
            }
        } catch (Exception $e) {
            throw $e;
        }


        return response()->json($response);
    }

    /**
     * Fetch project list for ajax call
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function load_member_list(Request $request)
    {
        $response = ['success' => false];

        try {

            if ($request->ajax()) {
                // get users except for the logged in user
                $member_list = User::get_user_list(\Auth::user()->uid);

                $html = view('partials.Projects._member_form', compact('member_list'))->render();

                $response = ['success' => true, 'html' => $html];
            }

        } catch (Exception $e) {
            throw $e;
        }


        return response()->json($response);
    }

    /**
     * Fetch project list for ajax call
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function load_projects(Request $request)
    {
        $resposne = ['success' => false];
        try {

            if ($request->ajax()) {
                $projects = Projects::get_active_projects();

                $html = view('partials.Projects._list', compact('projects'))->render();

                $response = ['success' => true, 'html' => $html];
            }

        } catch (Exception $e) {
            throw $e;
        }


        return response()->json($response);
    }

    /**
     * Update project milestones
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    // public function update_milestones(Request $request)
    // {
    //     $response = ['success' => false];
    //
    //     try {
    //
    //         if ($request->ajax()) {
    //
    //             if ($request->isMethod('post')) {
    //                 $form = $request->all();
    //
    //                 // update milestones
    //                 $milestones = ProjectMilestones::where(['mid' => $form['mid']]);
    //
    //                 if ($milestones->count()) {
    //                     $update_response = $milestones->update([
    //                         'announcement'            => is_null($form['announcement']) ? null : date('Y-m-d', strtotime(str_replace('/', '-', $form['announcement']))),
    //                         'scoping_design'          => is_null($form['scoping_design']) ? null : date('Y-m-d', strtotime(str_replace('/', '-', $form['scoping_design']))),
    //                         'advertising'             => is_null($form['advertising']) ? null : date('Y-m-d', strtotime(str_replace('/', '-', $form['advertising']))),
    //                         'award'                   => is_null($form['award']) ? null : date('Y-m-d', strtotime(str_replace('/', '-', $form['award']))),
    //                         'commencement'            => is_null($form['commencement']) ? null : date('Y-m-d', strtotime(str_replace('/', '-', $form['commencement']))),
    //                         '20_percent_complete'     => is_null($form['20_percent_complete']) ? null : date('Y-m-d', strtotime(str_replace('/', '-', $form['20_percent_complete']))),
    //                         '40_percent_complete'     => is_null($form['40_percent_complete']) ? null : date('Y-m-d', strtotime(str_replace('/', '-', $form['40_percent_complete']))),
    //                         '60_percent_complete'     => is_null($form['60_percent_complete']) ? null : date('Y-m-d', strtotime(str_replace('/', '-', $form['60_percent_complete']))),
    //                         '80_percent_complete'     => is_null($form['80_percent_complete']) ? null : date('Y-m-d', strtotime(str_replace('/', '-', $form['80_percent_complete']))),
    //                         'practical_completion'    => is_null($form['practical_completion']) ? null : date('Y-m-d', strtotime(str_replace('/', '-', $form['practical_completion']))),
    //
    //                         'is_announcement'         => isset($form['is_announcement']) ? 1 : 0,
    //                         'is_scoping_design'       => isset($form['is_scoping_design']) ? 1 : 0,
    //                         'is_advertising'          => isset($form['is_advertising']) ? 1 : 0,
    //                         'is_award'                => isset($form['is_award']) ? 1 : 0,
    //                         'is_commencement'         => isset($form['is_commencement']) ? 1 : 0,
    //                         'is_20_percent_complete'  => isset($form['is_20_percent_complete']) ? 1 : 0,
    //                         'is_40_percent_complete'  => isset($form['is_40_percent_complete']) ? 1 : 0,
    //                         'is_60_percent_complete'  => isset($form['is_60_percent_complete']) ? 1 : 0,
    //                         'is_80_percent_complete'  => isset($form['is_80_percent_complete']) ? 1 : 0,
    //                         'is_practical_completion' => isset($form['is_practical_completion']) ? 1 : 0,
    //
    //                         'announcement_completed_at'         => isset($form['is_announcement']) ? date('Y-m-d') : null,
    //                         'scoping_design_completed_at'       => isset($form['is_scoping_design']) ? date('Y-m-d') : null,
    //                         'advertising_completed_at'          => isset($form['is_advertising']) ? date('Y-m-d') : null,
    //                         'award_completed_at'                => isset($form['is_award']) ? date('Y-m-d') : null,
    //                         'commencement_completed_at'         => isset($form['is_commencement']) ? date('Y-m-d') : null,
    //                         '20_percent_completed_at'           => isset($form['is_20_percent_complete']) ? date('Y-m-d') : null,
    //                         '40_percent_completed_at'           => isset($form['is_40_percent_complete']) ? date('Y-m-d') : null,
    //                         '60_percent_completed_at'           => isset($form['is_60_percent_complete']) ? date('Y-m-d') : null,
    //                         '80_percent_completed_at'           => isset($form['is_80_percent_complete']) ? date('Y-m-d') : null,
    //                         'practical_completion_completed_at' => isset($form['is_practical_completion']) ? date('Y-m-d') : null,
    //                     ]);
    //
    //                     if ($update_response) {
    //                         // add to logs
    //                         if (isset($form['changes'])) {
    //                             $changes = json_decode($form['changes'], true);
    //
    //                             if (count($changes)) {
    //                                 $log_id = Uuid::generate()->string;
    //
    //                                 foreach ($changes as $i => $change) {
    //                                     list($field, $value, $original) = explode('##', $change);
    //
    //                                     if ($original == "") {
    //                                         $original = 'empty';
    //                                     }
    //
    //                                     if ($field == 'announcement-completed' and $value == 1) {
    //                                         $field = "Announcement";
    //                                         $value = "completed";
    //                                         $original = "not completed";
    //                                     } else if ($field == 'announcement-completed' and $value == 0) {
    //                                         $field = "Announcement";
    //                                         $value = "not completed";
    //                                         $original = "completed";
    //
    //                                     } else if ($field == 'scoping-design-completed' and $value == 1) {
    //                                         $field = "Scoping & Design";
    //                                         $value = "completed";
    //                                         $original = "not completed";
    //                                     } else if ($field == 'scoping-design-completed' and $value == 0) {
    //                                         $field = "Scoping & Design";
    //                                         $value = "not completed";
    //                                         $original = "completed";
    //
    //                                     } else if ($field == 'advertising-completed' and $value == 1) {
    //                                         $field = "Advertising";
    //                                         $value = "completed";
    //                                         $original = "not completed";
    //                                     } else if ($field == 'advertising-completed' and $value == 0) {
    //                                         $field = "Advertising";
    //                                         $value = "not completed";
    //                                         $original = "completed";
    //
    //                                     } else if ($field == 'award-completed' and $value == 1) {
    //                                         $field = "Award";
    //                                         $value = "completed";
    //                                         $original = "not completed";
    //                                     } else if ($field == 'award-completed' and $value == 0) {
    //                                         $field = "Award";
    //                                         $value = "not completed";
    //                                         $original = "completed";
    //
    //                                     } else if ($field == 'commencement-completed' and $value == 1) {
    //                                         $field = "Commencement";
    //                                         $value = "completed";
    //                                         $original = "not completed";
    //                                     } else if ($field == 'commencement-completed' and $value == 0) {
    //                                         $field = "Commencement";
    //                                         $value = "not completed";
    //                                         $original = "completed";
    //
    //                                     } else if ($field == '20-percent-completed' and $value == 1) {
    //                                         $field = "20% Complete";
    //                                         $value = "completed";
    //                                         $original = "not completed";
    //                                     } else if ($field == '20-percent-completed' and $value == 0) {
    //                                         $field = "20% Complete";
    //                                         $value = "not completed";
    //                                         $original = "completed";
    //
    //                                     } else if ($field == '40-percent-completed' and $value == 1) {
    //                                         $field = "40% Complete";
    //                                         $value = "completed";
    //                                         $original = "not completed";
    //                                     } else if ($field == '40-percent-completed' and $value == 0) {
    //                                         $field = "40% Complete";
    //                                         $value = "not completed";
    //                                         $original = "completed";
    //
    //                                     } else if ($field == '60-percent-completed' and $value == 1) {
    //                                         $field = "60% Complete";
    //                                         $value = "completed";
    //                                         $original = "not completed";
    //                                     } else if ($field == '60-percent-completed' and $value == 0) {
    //                                         $field = "60% Complete";
    //                                         $value = "not completed";
    //                                         $original = "completed";
    //
    //                                     } else if ($field == '80-percent-completed' and $value == 1) {
    //                                         $field = "80% Complete";
    //                                         $value = "completed";
    //                                         $original = "not completed";
    //                                     } else if ($field == '80-percent-completed' and $value == 0) {
    //                                         $field = "80% Ccomplete";
    //                                         $value = "not completed";
    //                                         $original = "completed";
    //
    //                                     } else if ($field == 'practical-completion-completed' and $value == 1) {
    //                                         $field = "Projcect Completion";
    //                                         $value = "completed";
    //                                         $original = "not completed";
    //                                     } else if ($field == 'practical-completion-completed' and $value == 0) {
    //                                         $field = "Project Completion";
    //                                         $value = "not completed";
    //                                         $original = "completed";
    //                                     } else {
    //                                         switch ($field) {
    //                                             case "announcement": $field = "Announcement"; break;
    //                                             case "scoping_design": $field = "Scoping & Design"; break;
    //                                             case "advertising": $field = "Advertising"; break;
    //                                             case "award": $field = "Award"; break;
    //                                             case "commencement": $field = "Commencement"; break;
    //                                             case "20_percent_complete": $field = "20% Complete"; break;
    //                                             case "40_percent_complete": $field = "40% Complete"; break;
    //                                             case "60_percent_complete": $field = "60% Complete"; break;
    //                                             case "80_percent_complete": $field = "80% Complete"; break;
    //                                             case "practicalcompletion": $field = "Project Completion"; break;
    //                                         }
    //                                     }
    //
    //                                     if (true === strtotime($value)) {
    //                                         $value = date('Y-m-d', strtotime(str_replace('/', '-', $value)));
    //                                     }
    //
    //                                     if (true === strtotime($original)) {
    //                                         $original = date('Y-m-d', strtotime(str_replace('/', '-', $original)));
    //                                     }
    //
    //                                     MilestoneLogs::create([
    //                                         'mid'        => $form['mid'],
    //                                         'log_id'     => $log_id,
    //                                         'field_name' => $field,
    //                                         'old_value'  => $original,
    //                                         'new_value'  => $value,
    //                                         'comment'    => "{$field} changed from ".$original." to {$value}",
    //                                         'updated_by' => \Auth::user()->uid
    //                                     ]);
    //                                 }
    //
    //                                 // send changes notiication
    //                                 $changes   = MilestoneLogs::get_changes($form['mid'], $log_id);
    //                                 $to_notify = User::get_users_to_notify();
    //
    //                                 if (!is_null($to_notify)) {
    //                                     Mail::to($to_notify->toArray())
    //                                         ->send(new NotifyChanges($changes)
    //                                     );
    //                                 }
    //                             }
    //
    //                             $response = ['success' => true, 'data' => ['mid' => $form['mid'], 'log_id' => $log_id]];
    //                         } else {
    //                             $response = ['success' => true, 'data' => ['mid' => $form['mid']]];
    //                         }
    //
    //                     }
    //                 }
    //             }
    //
    //         }
    //
    //     } catch (Exception $e) {
    //         throw $e;
    //     }
    //
    //     return response()->json($response);
    // }

    /**
     * Update project
     *
     * @param CreateProjectFormRequest $request
     * @return \Illuminate\Http\Response
     */
    // public function update(CreateProjectFormRequest $request)
    // {
    //     $response = ['success' => false];
    //
    //     try {
    //
    //         if ($request->ajax()) {
    //             if ($request->isMethod('put')) {
    //                 $form = $request->all();
    //
    //                 $project_info = Projects::where([
    //                     'pid'        => $form['pid'],
    //                     'is_active'  => 1,
    //                     'is_deleted' => 0
    //                 ]);
    //
    //                 if ($project_info->count()) {
    //                     $project = $project_info->first();
    //
    //                     DB::beginTransaction();
    //
    //                     $project->project_name    = $form['project_name'];
    //                     $project->project_code    = strtoupper($form['project_code']);
    //                     $project->project_type_id = $form['project_type_id'];
    //                     $project->description     = $form['description'];
    //
    //                     if ($project->save()) {
    //                         // update project members
    //                         if (isset($form['members'])) {
    //                             $members = $form['members'];
    //
    //                             if (count($members)) {
    //                                 // clean up current members
    //                                 DB::table('project_members')->where('pid', $form['pid'])->delete();
    //
    //                                 // set member details
    //                                 foreach ($members['uid'] as $i => $uid) {
    //                                     $updated['pid']        = $form['pid'];
    //                                     $updated['uid']        = $uid;
    //                                     $updated['is_owner']   = $members['is_owner'][$i];
    //                                     $updated['is_active']  = 1;
    //                                     $updated['created_at'] = date('Y-m-d H:i:s');
    //                                     $updated['updated_at'] = date('Y-m-d H:i:s');
    //
    //                                     if (!ProjectMembers::create($updated)) {
    //                                         DB::rollback();
    //                                     }
    //                                 }
    //                             }
    //                         }
    //
    //                         // log_changes
    //                         if (isset($form['changes'])) {
    //                             $changes = json_decode($form['changes'], true);
    //                         } else {
    //                             $changes = null;
    //                         }
    //
    //                         if (count($changes)) {
    //                             $log_id = Uuid::generate()->string;
    //
    //                             foreach ($changes as $i => $change) {
    //                                 list($field, $value, $original) = explode('##', $change);
    //
    //                                 if ($field == 'project_type_id') {
    //                                     $_field = "project type";
    //                                     $comment = "{$_field} changes from ".ProjectTypes::get_project_type_name($original)." to ".ProjectTypes::get_project_type_name($value);
    //                                 } else {
    //                                     $_field = str_replace('_', ' ', $field);
    //                                     $_field = ucwords($_field);
    //                                     $comment = "{$_field} changes from ".$original." to {$value}";
    //                                 }
    //
    //                                 ProjectLogs::create([
    //                                     'pid'        => $form['pid'],
    //                                     'log_id'     => $log_id,
    //                                     'field_name' => $field,
    //                                     'old_value'  => $original,
    //                                     'new_value'  => $value,
    //                                     'comment'    => $comment,
    //                                     'updated_by' => \Auth::user()->uid
    //                                 ]);
    //                             }
    //
    //                             // send changes notiication
    //                             $changes   = ProjectLogs::get_changes($form['pid'], $log_id);
    //                             $to_notify = User::get_users_to_notify();
    //
    //                             if (!is_null($to_notify)) {
    //                                 Mail::to($to_notify->toArray())
    //                                     ->send(new NotifyChanges($changes)
    //                                 );
    //                             }
    //
    //                             $response = ['success' => true, 'data' => ['pid' => $form['pid'], 'log_id' => $log_id]];
    //                         } else {
    //                             $response = ['success' => true];
    //                         }
    //
    //                         DB::commit();
    //                     } else {
    //                         DB::rollback();
    //                     }
    //                 }
    //             }
    //         }
    //
    //     } catch (Exception $e) {
    //         throw $e;
    //     }
    //
    //
    //     return response()->json($response);
    // }

    /**
     * Save new projects
     *
     * @param CreateProjectFormRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateProjectFormRequest $request)
    {
        $resp = ['success' => false];

        try {

            if ($request->isMethod('post')) {
                $form = $request->all();

                $data['project_name']         = $form['project_name'];
                $data['project_code']         = strtoupper($form['project_code']);
                $data['project_type_id']      = $form['project_type_id'];
                $data['description']          = $form['description'];

                if (isset($form['is_visible_dashboard'])) {
                    $data['is_visible_dashboard'] = 1;
                }

                DB::beginTransaction();

                $project_id = Projects::create($data)->id;

                if ($project_id) {
                    $project_info = Projects::find($project_id);

                    if ($project_info) {
                        // reset $data
                        $data = null;

                        $data['pid'] = $project_info->pid;
                        $data['announcement']         = empty($form['announcement']) ? null : date('Y-m-d', strtotime(str_replace('/', '-', $form['announcement'])));
                        $data['scoping_design']       = empty($form['scoping_design']) ? null : date('Y-m-d', strtotime(str_replace('/', '-', $form['scoping_design'])));
                        $data['adevertising']         = empty($form['advertising']) ? null : date('Y-m-d', strtotime(str_replace('/', '-', $form['advertising'])));
                        $data['award']                = empty($form['award']) ? null : date('Y-m-d', strtotime(str_replace('/', '-', $form['award'])));
                        $data['commencement']         = empty($form['commencement']) ? null : date('Y-m-d', strtotime(str_replace('/', '-', $form['commencement'])));
                        $data['20_percent_complete']  = empty($form['20_percent_complete']) ? null : date('Y-m-d', strtotime(str_replace('/', '-', $form['20_percent_complete'])));
                        $data['40_percent_complete']  = empty($form['40_percent_complete']) ? null : date('Y-m-d', strtotime(str_replace('/', '-', $form['40_percent_complete'])));
                        $data['60_percent_complete']  = empty($form['60_percent_complete']) ? null : date('Y-m-d', strtotime(str_replace('/', '-', $form['60_percent_complete'])));
                        $data['80_percent_complete']  = empty($form['80_percent_complete']) ? null : date('Y-m-d', strtotime(str_replace('/', '-', $form['80_percent_complete'])));
                        $data['practical_completion'] = empty($form['practicalcompletion']) ? null : date('Y-m-d', strtotime(str_replace('/', '-', $form['practicalcompletion'])));

                        if (ProjectMilestones::create($data)) {
                            // make currently logged in user the owner of the project
                            $member_data[] = [
                                'pid'      => $project_info->pid,
                                'uid'      => \Auth::user()->uid,
                                'is_owner' => 1
                            ];

                            // additional team members
                            if (isset($form['members'])) {
                                foreach ($form['members'] as $members) {
                                    foreach ($members as $member) {
                                        array_push($member_data, [
                                            'pid'      => $project_info->pid,
                                            'uid'      => $member,
                                            'is_owner' => 0
                                        ]);
                                    }
                                }
                            }

                            foreach ($member_data as $data) {
                                ProjectMembers::create($data);
                            }

                            // commit data
                            DB::commit();

                            $resp['success'] = true;

                        } else {
                            DB::rollback();
                        }
                    }
                }
            }

        } catch (Exception $e) {
            throw $e;
        }


        return response()->json($resp);
    }
}
