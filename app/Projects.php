<?php

namespace App;

use App\AppModel;
use App\ProjectTypes;
use App\ProjectMilestones;
use App\ProjectMembers;
use App\ProjectLogs;
use App\MilestoneLogs;
use Webpatser\Uuid\Uuid;

use DB, DateTime;


class Projects extends AppModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pid',
        'project_type_id',
        'project_name',
        'project_code',
        'description',
        'is_visible_dashboard',
        'is_active',
        'is_deleted',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    protected $guarded = ['pid', 'project_code'];

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "projects";


    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->pid = Uuid::generate()->string;
        });
    }


    public static function get_changes($pid)
    {
        $changes           = null;
        $project_changes   = [];
        $milestone_changes = [];

        $project_logs_obj = self::where([
            'projects.pid'        => $pid,
            'projects.is_active'  => 1,
            'projects.is_deleted' => 0
        ])
        ->select(["project_logs.id", "project_logs.updated_by", "project_logs.comment", "comments.comment as reason", "project_logs.created_at"])
        ->leftjoin("project_logs", "project_logs.pid", "=", "projects.pid")
        ->leftjoin("comments", "comments.log_id", "=", "project_logs.log_id")
        ->orderBy("project_logs.created_at", "desc");

        if ($project_logs_obj->count()) {
            foreach ($project_logs_obj->get() as $change) {
                $i = (int) strtotime($change->created_at) + (int) $change->id;

                if (!empty($change->comment)) {
                    $user_info = User::where('uid', $change->updated_by);

                    $project_changes[$i]['modified_by'] = $user_info->count() ? $user_info->first()->name : "N/A";
                    $project_changes[$i]['created_at']  = $change->created_at;
                    $project_changes[$i]['comment']     = str_replace('_', ' ', $change->comment);
                    $project_changes[$i]['reason']      = str_replace('_', ' ', $change->reason);
                }
            }
        }

        $milestone_logs_obj = self::where([
            'projects.pid'        => $pid,
            'projects.is_active'  => 1,
            'projects.is_deleted' => 0
        ])
        ->select(["milestone_logs.id", "milestone_logs.updated_by", "milestone_logs.comment", "comments.comment as reason", "milestone_logs.created_at"])
        ->join("project_milestones", "project_milestones.pid", "=", "projects.pid")
        ->leftjoin("milestone_logs", "milestone_logs.mid", "=", "project_milestones.mid")
        ->leftjoin("comments", "comments.log_id", "=", "milestone_logs.log_id")
        ->orderBy("milestone_logs.created_at", "desc");

        if ($milestone_logs_obj->count()) {
            foreach ($milestone_logs_obj->get() as $change) {
                if (!empty($change->comment)) {
                    $i = (int) strtotime($change->created_at) + (int) $change->id;

                    $user_info = User::where('uid', $change->updated_by);

                    $milestone_changes[$i]['modified_by'] = $user_info->count() ? $user_info->first()->name : "N/A";
                    $milestone_changes[$i]['created_at']  = $change->created_at;
                    $milestone_changes[$i]['comment']     = str_replace('_', ' ', $change->comment);
                    $milestone_changes[$i]['reason']      = empty($change->reason) ? 'N/A' : str_replace('_', ' ', $change->reason);
                }
            }
        }

        $changes = array_merge($project_changes, $milestone_changes);

        rsort($changes);

        return $changes;
    }

    public static function get_project_summary($search_str = null)
    {
        $summary = self::where([
                        'projects.is_visible_dashboard' => 1,
                        'projects.is_active'            => 1,
                        'projects.is_deleted'           => 0
                    ])
                    ->select([
                        DB::raw("DISTINCT projects.pid"),
                        "projects.project_name",
                        "projects.project_code",
                        "projects.created_at",
                        "project_types.project_type_name",
                        // "project_members.uid",
                        "project_milestones.announcement AS announcement_date",
                        "project_milestones.scoping_design AS scoping_design_date",
                        "project_milestones.advertising AS advertising_date",
                        "project_milestones.award AS award_date",
                        "project_milestones.commencement AS commencement_date",
                        "project_milestones.20_percent_complete AS 20_percent_complete_date",
                        "project_milestones.40_percent_complete AS 40_percent_complete_date",
                        "project_milestones.60_percent_complete AS 60_percent_complete_date",
                        "project_milestones.80_percent_complete AS 80_percent_complete_date",
                        "project_milestones.practical_completion AS practical_completion_date",
                        "project_milestones.announcement_completed_at",
                        "project_milestones.scoping_design_completed_at",
                        "project_milestones.advertising_completed_at",
                        "project_milestones.award_completed_at",
                        "project_milestones.commencement_completed_at",
                        "project_milestones.20_percent_completed_at",
                        "project_milestones.40_percent_completed_at",
                        "project_milestones.60_percent_completed_at",
                        "project_milestones.80_percent_completed_at",
                        "project_milestones.practical_completion_completed_at",
                        // DB::raw("((SELECT count(*) FROM comments WHERE object_name = 'details' AND object_id = projects.pid) + (SELECT count(*) FROM comments WHERE object_name = 'milestones' AND object_id = project_milestones.mid)) AS changes_count"),
                        DB::raw("((SELECT COUNT(*) FROM project_logs WHERE pid = projects.pid) + (SELECT COUNT(*) FROM milestone_logs WHERE mid = project_milestones.mid)) AS changes_count"),
                        DB::raw("(CASE WHEN project_milestones.is_announcement = 1
                          THEN 'completed' ELSE
                           FLOOR(DATEDIFF(project_milestones.announcement, CURDATE()) / 7)
                          END) announcement"),
                        DB::raw("(CASE WHEN project_milestones.is_scoping_design = 1
                          THEN 'completed' ELSE
                            FLOOR(DATEDIFF(project_milestones.scoping_design, CURDATE()) / 7)
                          END) scoping_design"),
                        DB::raw("(CASE WHEN project_milestones.is_advertising = 1
                          THEN 'completed' ELSE
                           FLOOR(DATEDIFF(project_milestones.advertising, CURDATE()) / 7)
                          END) advertising"),
                        DB::raw("(CASE WHEN project_milestones.is_award = 1
                          THEN 'completed' ELSE
                           FLOOR(DATEDIFF(project_milestones.award, CURDATE()) / 7)
                          END) award"),
                        DB::raw("(CASE WHEN project_milestones.is_commencement = 1
                          THEN 'completed' ELSE
                           FLOOR(DATEDIFF(project_milestones.commencement, CURDATE()) / 7)
                          END) commencement"),
                        DB::raw("(CASE WHEN project_milestones.is_20_percent_complete = 1
                          THEN 'completed' ELSE
                           FLOOR(DATEDIFF(project_milestones.20_percent_complete, CURDATE()) / 7)
                          END) 20_percent_complete"),
                        DB::raw("(CASE WHEN project_milestones.is_40_percent_complete = 1
                          THEN 'completed' ELSE
                           FLOOR(DATEDIFF(project_milestones.40_percent_complete, CURDATE()) / 7)
                          END) 40_percent_complete"),
                        DB::raw("(CASE WHEN project_milestones.is_60_percent_complete = 1
                          THEN 'completed' ELSE
                           FLOOR(DATEDIFF(project_milestones.60_percent_complete, CURDATE()) / 7)
                          END) 60_percent_complete"),
                        DB::raw("(CASE WHEN project_milestones.is_80_percent_complete = 1
                          THEN 'completed' ELSE
                           FLOOR(DATEDIFF(project_milestones.80_percent_complete, CURDATE()) / 7)
                          END) 80_percent_complete"),
                        DB::raw("(CASE WHEN project_milestones.is_practical_completion = 1
                          THEN 'completed' ELSE
                           FLOOR(DATEDIFF(project_milestones.practical_completion, CURDATE()) / 7)
                          END) practical_completion")
                    ])
                    ->join('project_milestones', 'project_milestones.pid', '=', 'projects.pid')
                    ->join('project_types', 'project_types.id', '=', 'projects.project_type_id')
                    ->orderBy('projects.created_at', 'desc');
                    // ->leftjoin('project_members', 'project_members.pid', '=', 'projects.pid');

        if (!is_null($search_str) or !empty($search_str)) {
            $search_str = filter_var($search_str, FILTER_SANITIZE_STRING);
            $summary->where('projects.project_name', 'like', "%{$search_str}%");
            $summary->orWhere('projects.project_code', 'like', "%{$search_str}%");
            $summary->orWhere('project_types.project_type_name', 'like', "%{$search_str}%");
        }

        // DB::enableQueryLog();
        // $x = $summary->get();
        // $query = DB::getQueryLog();
        // echo '<pre>'; print_r(end($query)); exit;

        $project_summary = null;
        $pids = null;

        if ($summary->count()) {
            // foreach ($summary->get()->toArray() as $i => $row) {
            foreach ($summary->paginate(15) as $i => $row) {
                // filter he projects that belongs to the logged user
                if (!session('_view')) {
                    $member_where = ['uid' => \Auth::user()->uid, 'is_active' => 1];
                    $members_info = ProjectMembers::where($member_where);

                    if ($members_info->count()) {
                        foreach ($members_info->get() as $info) {
                            $pids[] = $info->pid;
                        }
                    }
                } else {
                    $pids[] = $row['pid'];
                }
            }

            $pids = array_unique($pids);

            foreach ($summary->paginate(15) as $i => $row) {
            // foreach ($summary->get()->toArray() as $i => $row) {

                if (in_array($row['pid'], $pids)) {
                    // get project manager
                    $members_info = ProjectMembers::where(['pid' => $row['pid'], 'is_active' => 1]);

                    if ($members_info->count()) {
                        foreach ($members_info->get() as $member) {
                            // get project manager
                            $user_info = User::where(['uid' => $member->uid, 'user_role_id' => 3]);

                            if ($user_info->count()) {
                                $project_summary[$i]['project_manager'] = $user_info->first()->name;
                            } else {
                                $project_summary[$i]['project_manager'] = "";
                            }
                        }
                    } else {
                        $project_summary[$i]['project_manager'] = "";
                    }

                    $project_summary[$i]['pid']                               = $row['pid'];
                    $project_summary[$i]['project_name']                      = $row['project_name'];
                    $project_summary[$i]['project_code']                      = $row['project_code'];
                    $project_summary[$i]['project_type_name']                 = $row['project_type_name'];
                    $project_summary[$i]['changes_count']                     = $row['changes_count'];

                    $project_summary[$i]['announcement']                      = $row['announcement'];
                    $project_summary[$i]['announcement_date']                 = $row['announcement_date'];
                    $project_summary[$i]['announcement_completed_at']         = $row['announcement_completed_at'];

                    $project_summary[$i]['scoping_design']                    = $row['scoping_design'];
                    $project_summary[$i]['scoping_design_date']               = $row['scoping_design_date'];
                    $project_summary[$i]['scoping_design_completed_at']       = $row['scoping_design_completed_at'];

                    $project_summary[$i]['advertising']                       = $row['advertising'];
                    $project_summary[$i]['advertising_date']                  = $row['advertising_date'];
                    $project_summary[$i]['advertising_completed_at']          = $row['advertising_completed_at'];

                    $project_summary[$i]['award']                             = $row['award'];
                    $project_summary[$i]['award_date']                        = $row['award_date'];
                    $project_summary[$i]['award_completed_at']                = $row['award_completed_at'];

                    $project_summary[$i]['commencement']                      = $row['commencement'];
                    $project_summary[$i]['commencement_date']                 = $row['commencement_date'];
                    $project_summary[$i]['commencement_completed_at']         = $row['commencement_completed_at'];

                    $project_summary[$i]['20_percent_complete']               = $row['20_percent_complete'];
                    $project_summary[$i]['20_percent_complete_date']          = $row['20_percent_complete_date'];
                    $project_summary[$i]['20_percent_completed_at']           = $row['20_percent_completed_at'];

                    $project_summary[$i]['40_percent_complete']               = $row['40_percent_complete'];
                    $project_summary[$i]['40_percent_complete_date']          = $row['40_percent_complete_date'];
                    $project_summary[$i]['40_percent_completed_at']           = $row['40_percent_completed_at'];

                    $project_summary[$i]['60_percent_complete']               = $row['60_percent_complete'];
                    $project_summary[$i]['60_percent_complete_date']          = $row['60_percent_complete_date'];
                    $project_summary[$i]['60_percent_completed_at']           = $row['60_percent_completed_at'];

                    $project_summary[$i]['80_percent_complete']               = $row['80_percent_complete'];
                    $project_summary[$i]['80_percent_complete_date']          = $row['80_percent_complete_date'];
                    $project_summary[$i]['80_percent_completed_at']           = $row['80_percent_completed_at'];

                    $project_summary[$i]['practical_completion']              = $row['practical_completion'];
                    $project_summary[$i]['practical_completion_date']         = $row['practical_completion_date'];
                    $project_summary[$i]['practical_completion_completed_at'] = $row['practical_completion_completed_at'];
                }

            }

        }

        // return !is_null($project_summary) ? $project_summary : null;
        $project_summary = !is_null($project_summary) ? $project_summary : null;

        return ['project_summary' => $project_summary, 'raw_summary' => $summary->paginate(15)];
    }

    public static function get_project_details($pid)
    {
        $project_info = self::where([
            'pid'        => $pid,
            'is_active'  => 1,
            'is_deleted' => 0
        ]);

        $data = null;

        if ($project_info->count()) {
            $data['pid']                  = $project_info->first()->pid;
            $data['project_name']         = $project_info->first()->project_name;
            $data['project_code']         = $project_info->first()->project_code;
            $data['description']          = $project_info->first()->description;
            $data['is_visible_dashboard'] = $project_info->first()->is_visible_dashboard;
            $data['project_type']         = ProjectTypes::get_project_type($project_info->first()->project_type_id);

            // get project milestones
            $milestones = ProjectMilestones::get_milestones($project_info->first()->pid);
            $data['milestones']['mid']                     = $milestones['mid'];
            $data['milestones']['announcement']            = is_null($milestones['announcement']) ? null : date('d/m/Y', strtotime($milestones['announcement']));
            $data['milestones']['scoping_design']          = is_null($milestones['scoping_design']) ? null : date('d/m/Y', strtotime($milestones['scoping_design']));
            $data['milestones']['advertising']             = is_null($milestones['advertising']) ? null : date('d/m/Y', strtotime($milestones['advertising']));
            $data['milestones']['award']                   = is_null($milestones['award']) ? null : date('d/m/Y', strtotime($milestones['award']));
            $data['milestones']['commencement']            = is_null($milestones['commencement']) ? null : date('d/m/Y', strtotime($milestones['commencement']));
            $data['milestones']['20_percent_complete']     = is_null($milestones['20_percent_complete']) ? null : date('d/m/Y', strtotime($milestones['20_percent_complete']));
            $data['milestones']['40_percent_complete']     = is_null($milestones['40_percent_complete']) ? null : date('d/m/Y', strtotime($milestones['40_percent_complete']));
            $data['milestones']['60_percent_complete']     = is_null($milestones['60_percent_complete']) ? null : date('d/m/Y', strtotime($milestones['60_percent_complete']));
            $data['milestones']['80_percent_complete']     = is_null($milestones['80_percent_complete']) ? null : date('d/m/Y', strtotime($milestones['80_percent_complete']));
            $data['milestones']['practical_completion']    = is_null($milestones['practical_completion']) ? null : date('d/m/Y', strtotime($milestones['practical_completion']));
            $data['milestones']['is_announcement']         = $milestones['is_announcement'];
            $data['milestones']['is_scoping_design']       = $milestones['is_scoping_design'];
            $data['milestones']['is_advertising']          = $milestones['is_advertising'];
            $data['milestones']['is_award']                = $milestones['is_award'];
            $data['milestones']['is_commencement']         = $milestones['is_commencement'];
            $data['milestones']['is_20_percent_complete']  = $milestones['is_20_percent_complete'];
            $data['milestones']['is_40_percent_complete']  = $milestones['is_40_percent_complete'];
            $data['milestones']['is_60_percent_complete']  = $milestones['is_60_percent_complete'];
            $data['milestones']['is_80_percent_complete']  = $milestones['is_80_percent_complete'];
            $data['milestones']['is_practical_completion'] = $milestones['is_practical_completion'];

            // get project members
            $data['members'] = ProjectMembers::get_members($project_info->first()->pid);
        }

        return $data;
    }

    public static function get_active_projects($search_term = null)
    {
        $data = self::where([
                    'projects.is_active'  => 1,
                    'projects.is_deleted' => 0,
                ])
                ->select(['projects.id', 'projects.pid', 'projects.project_name', 'projects.project_code', 'projects.description', 'projects.project_type_id', 'project_types.project_type_name'])
                ->join('project_types', 'project_types.id', '=', 'projects.project_type_id')
                ->orderBy('projects.created_at', 'desc');

        // filter only the projects belongs to the logged user
        if (!session('_view')) {
            $data->join('project_members', 'project_members.pid', '=', 'projects.pid');
            $data->where(['project_members.uid' => \Auth::user()->uid]);
        }

        if (!is_null($search_term)) {
            $search_term = filter_var($search_term, FILTER_SANITIZE_STRING);
            $data->where('projects.project_name', 'like', "%{$search_term}%");
            $data->orWhere('projects.project_code', 'like', "%{$search_term}%");
            $data->orWhere('project_types.project_type_name', 'like', "%{$search_term}%");
        }

        // if (!is_null($page)) {
        //     $page = (int) $page;
        //     return $data->count() ? $data->paginate(15, $page) : null;
        // } else {
        //     return $data->count() ? $data->paginate(15) : null;
        // }

        return $data->count() ? $data->paginate(15) : null;
    }

    public static function get_team($uid)
    {
        $my_projects = self::where([
                    'project_members.uid' => $uid,
                    'projects.is_active'  => 1,
                    'projects.is_deleted' => 0
                ])
                ->select('projects.pid')
                ->join('project_members', 'project_members.pid', '=', 'projects.pid')
                ->groupBy('projects.pid');

        $project_ids = null;
        $my_team     = null;

        if ($my_projects->count()) {
            foreach ($my_projects->get() as $project) {
                $project_ids[] = $project->pid;
            }

            if (!is_null($project_ids)) {
                $teams = self::whereIn('projects.pid', $project_ids)
                    ->where([
                        ['users.is_active',    '=',  1],
                        ['users.is_deleted',   '=',  0],
                        ['project_members.uid', '!=', $uid]
                    ])
                    ->join('project_members', 'project_members.pid', '=', 'projects.pid')
                    ->join('users', 'users.uid', '=', 'project_members.uid')
                    ->join('user_roles', 'user_roles.id', '=', 'users.user_role_id')
                    ->select(['projects.pid', 'users.name', 'user_roles.role_name']);

                if ($teams->count()) {
                    foreach ($teams->get() as $team) {
                        $my_team[] = ['pid' => $team->pid, 'name' => $team->name, 'role' => $team->role_name];
                    }
                }
            }
        }

        return $my_team;
    }

    public static function is_project_code_unique($project_code)
    {
        $data = self::where([
                'project_code' => $project_code,
                'is_active'    => 1,
                'is_deleted'   => 0
            ]);

        return $data->count() ? false : true;
    }

    public static function validate_date($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    public static function convert_to_valid_date($date)
    {
        $original_date_value = trim($date);
        $date = trim($date);

        if (strpos($date, '/') === false) {
            if (self::validate_date($date)) {
                $date = date('d/m/Y', strtotime($date));
            } else {
                return $date;
            }
        }

        list($day, $month, $year) = explode('/', $original_date_value);

        // if year is 2 digit, force year to be 2000's
        if (strlen($year) == 2) {
            $year  = "20{$year}";
        }

        $month = str_pad($month, 2, '0', STR_PAD_LEFT);
        $day   = str_pad($day, 2, '0', STR_PAD_LEFT);

        $date = date('d/m/Y', strtotime("{$year}-{$month}-{$day}"));

        // $date = date('Y-m-d', strtotime($date));
        // $date = date('d/m/Y', strtotime($date));

        // if ($date == '01/01/1970') {
        //     list($day, $month, $year) = explode('/', $original_date_value);
        //
        //     // if year is 2 digit, force year to be 2000's
        //     if (strlen($year) == 2) {
        //         $year  = "20{$year}";
        //     }
        //
        //     $month = str_pad($month, 2, '0', STR_PAD_LEFT);
        //     $day   = str_pad($day, 2, '0', STR_PAD_LEFT);
        //
        //     $date = date('d/m/Y', strtotime("{$year}-{$month}-{$day}"));
        // }


        // if year is 2 digit, force year to be 2000's
        // if (strlen($year) == 2) {
        //     $year  = "20{$year}";
        // }
        //
        // $month = str_pad($month, 2, '0', STR_PAD_LEFT);
        // $day   = str_pad($day, 2, '0', STR_PAD_LEFT);
        //
        // $date = date('d/m/Y', strtotime("{$year}-{$month}-{$day}"));
        //
        // if ($date == '01/01/1970') {
        //
        // }

        return (self::validate_date($date, 'd/m/Y') and $date != '01/01/1970') ? $date : null;
    }

    public static function get_project_name($pid)
    {
        return self::where(['pid' => $pid, 'is_active' => 1, 'is_deleted' => 0])->first()->project_name;
    }
}
