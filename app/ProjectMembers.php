<?php

namespace App;

use App\AppModel;

class ProjectMembers extends AppModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pid',
        'uid',
        'is_owner',
        'created_at',
        'updated_at'
    ];

    protected $guarded = ['pid', 'uid'];

    protected $dates = ['created_at', 'updated_at'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "project_members";


    public static function get_members($pid)
    {
        if (is_null($pid)) {
            return null;
        }

        $data = self::where([
                        'project_members.pid' => $pid,
                        'project_members.is_active' => 1
                    ])
                    ->join('users', 'users.uid', '=', 'project_members.uid')
                    ->join('user_roles', 'user_roles.id', '=', 'users.user_role_id')
                    ->select([
                        'project_members.pid',
                        'users.uid',
                        'users.name',
                        'users.user_role_id',
                        'user_roles.role_name',
                        'project_members.is_owner'
                    ]);

        return $data->count() ? $data->get() : null;
    }
}
