<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Projects;
use App\ProjectLogs;
use App\MilestoneLogs;


class Comments extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "comments";

    protected $guards = ['object_id', 'uid', 'log_id'];

    protected $dates = ['deleted_at', 'created_at', 'modified_at'];

    protected $fillable = [
        'object_name',
        'object_id',
        'uid',
        'log_id',
        'comment'
    ];


    public static function get_comments($pid)
    {
        $comments           = null;
        $project_comments   = [];
        $milestone_comments = [];

        try {

            $project_info = Projects::select('projects.pid', 'project_milestones.mid')
                                ->where(['projects.pid' => $pid, 'projects.is_active' => 1, 'projects.is_deleted' => 0])
                                ->join('project_milestones', 'project_milestones.pid', '=', 'projects.pid');

            if ($project_info->count()) {
                // get project logs
                $project_logs_info = ProjectLogs::where(['pid' => $project_info->first()->pid])
                                        ->join('users', 'users.uid', '=', 'project_logs.updated_by')
                                        ->orderBy('project_logs.created_at', 'desc')
                                        ->select([
                                                'project_logs.id',
                                                'project_logs.log_id',
                                                'project_logs.comment',
                                                'project_logs.created_at',
                                                'users.name'
                                            ]);

                if ($project_logs_info->count()) {
                    foreach ($project_logs_info->get() as $logs) {
                        $i = (int) strtotime($logs->created_at) + (int) $logs->id;

                        $project_comments[$i] = [
                            'object_id'   => $logs->object_id,
                            'object_name' => 'details',
                            'log_id'      => $logs->log_id,
                            'comment'     => $logs->comment,
                            'updated_by'  => $logs->name,
                            'created_at'  => $logs->created_at,
                            'comments'    => self::get_comment_logs($logs->log_id)
                        ];
                    }
                }

                // get milestone logs
                $milestone_logs_info = MilestoneLogs::where(['mid' => $project_info->first()->mid])
                                        ->join('users', 'users.uid', '=', 'milestone_logs.updated_by')
                                        ->orderBy('milestone_logs.created_at', 'desc')
                                        ->select([
                                                'milestone_logs.id',
                                                'milestone_logs.log_id',
                                                'milestone_logs.comment',
                                                'milestone_logs.created_at',
                                                'users.name'
                                            ]);

                if ($milestone_logs_info->count()) {
                    foreach ($milestone_logs_info->get() as $logs) {
                        $i = (int) strtotime($logs->created_at) + (int) $logs->id;

                        $milestone_comments[$i] = [
                            'object_id'   => $logs->object_id,
                            'object_name' => 'milestones',
                            'log_id'      => $logs->log_id,
                            'comment'     => $logs->comment,
                            'updated_by'  => $logs->name,
                            'created_at'  => $logs->created_at,
                            'comments'    => self::get_comment_logs($logs->log_id)
                        ];
                    }
                }

            }

            $comments = array_merge($project_comments, $milestone_comments);

            rsort($comments);

        } catch (Exception $e) {
            throw $e;
        }

        return $comments;
    }

    public static function get_comment_logs($log_id)
    {
        $comments = null;

        try {

            $comment_details = self::where([
                                        'comments.log_id'     => $log_id,
                                        'comments.is_active'  => 1,
                                        'comments.is_deleted' => 0
                                    ])
                                 ->select(['comments.object_name', 'comments.object_id', 'comments.comment', 'comments.created_at', 'users.name'])
                                 ->join('users', 'users.uid', '=', 'comments.uid')
                                 ->orderBy('comments.created_at', 'desc');

            if ($comment_details->count()) {
                foreach ($comment_details->get() as $comment) {
                    $comments[] = [
                        'object_name' => $comment->object_name,
                        'object_id'   => $comment->object_id,
                        'comment'     => $comment->comment,
                        'comment_by'  => $comment->name,
                        'created_at'  => $comment->created_at
                    ];
                }
            }

        } catch (Exception $e) {
            throw $e;
        }

        return $comments;
    }
}
