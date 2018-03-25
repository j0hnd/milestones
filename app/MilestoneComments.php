<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MilestoneComments  extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "milestone_comments";

    protected $guards = ['mid', 'uid', 'milestone_log_id'];

    protected $dates = ['deleted_at', 'created_at', 'modified_at'];

    protected $fillable = [
        'mid',
        'uid',
        'milestone_log_id',
        'comment'
    ];


    public static function get_comments($mid)
    {
        $data = self::where([
                    'milestone_comments.mid'        => $mid,
                    'milestone_comments.is_active'  => 1,
                    'milestone_comments.is_deleted' => 0
                ])
                ->select(['users.name', 'milestone_comments.comment', 'milestone_comments.created_at'])
                ->join('users', 'users.uid', '=', 'milestone_comments.uid')
                ->orderBy('created_at', 'desc');

        return $data->count() ? $data->get() : null;
    }
}
