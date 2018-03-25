<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectLogs extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pid',
        'log_id',
        'field_name',
        'old_value',
        'new_value',
        'comment',
        'created_at',
        'updated_at',
        'updated_by'
    ];

    protected $guarded = ['pid', 'updated_by'];

    protected $dates = ['created_at', 'updated_at'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "project_logs";


    public static function get_changes($pid, $log_id)
    {
        $logs = self::where(['project_logs.pid' => $pid, 'log_id' => $log_id])
                  ->join('users', 'users.uid', '=', 'project_logs.updated_by')
                  ->select(['project_logs.comment', 'project_logs.created_at', 'users.name'])
                  ->orderBy('created_at', 'desc');

        return $logs->count() ? $logs->get() : null;
    }
}
