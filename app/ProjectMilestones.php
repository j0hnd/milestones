<?php

namespace App;

use App\AppModel;
use Webpatser\Uuid\Uuid;

class ProjectMilestones extends AppModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pid',
        'announcement',
        'scoping_design',
        'advertising',
        'award',
        'commencement',
        '20_percent_complete',
        '40_percent_complete',
        '60_percent_complete',
        '80_percent_complete',
        'practical_completion',
        'created_at',
        'updated_at',
        'is_active'
    ];

    protected $guarded = ['pid', 'mid'];

    protected $dates = ['created_at', 'updated_at'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "project_milestones";


    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->mid = Uuid::generate()->string;
        });
    }

    public static function get_milestones($pid)
    {
        if (is_null($pid)) {
            return null;
        }

        $data = self::where(['pid' => $pid, 'is_active' => 1]);

        return $data->count() ? $data->first()->toArray() : null;
    }
}
