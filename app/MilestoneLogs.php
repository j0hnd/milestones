<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MilestoneLogs extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var; array
     */
    protected $fillable = [
        'mid',
        'log_id',
        'field_name',
        'old_value',
        'new_value',
        'comment',
        'created_at',
        'updated_at',
        'updated_by'
    ];

    protected $guarded = ['mid', 'updated_by'];

    protected $dates = ['created_at', 'updated_at'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "milestone_logs";


    public static function get_changes($mid, $log_id)
    {
        $logs = self::where(['milestone_logs.mid' => $mid, 'log_id' => $log_id])
                  ->join('users', 'users.uid', '=', 'milestone_logs.updated_by')
                  ->select(['milestone_logs.comment', 'milestone_logs.created_at', 'users.name'])
                  ->orderBy('created_at', 'desc');

        return $logs->count() ? $logs->get() : null;
    }

    public static function get_project_id($mid)
    {
        return self::where(['milestone_logs.mid' => $mid])->join('project_milestones', 'project_milestones.mid', '=', 'milestone_logs.mid')->first()->pid;
    }
}
