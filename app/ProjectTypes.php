<?php

namespace App;

use App\AppModel;

class ProjectTypes extends AppModel
{
    protected $fillable = [
        'project_type_name'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "project_types";


    public static function get_project_types()
    {
        $data = self::where(['is_active' => 1]);

        return $data->count() ? $data->get() : null;
    }


    public static function get_project_type_name($id)
    {
        $project_type = self::where(['id' => $id, 'is_active' => 1]);

        return $project_type->count() ? $project_type->first()->project_type_name : null;
    }

    public static function get_project_type_id($project_type_name)
    {
        $project_type = self::where(['project_type_name' => $project_type_name, 'is_active' => 1]);

        return $project_type->count() ? $project_type->first()->id : null;
    }

    public static function get_project_type($id)
    {
        if (is_null($id)) {
            return null;
        }

        $project_type = self::where(['id' => $id, 'is_active' => 1]);

        return $project_type->count() ? [
                    'project_type_id'   => $project_type->first()->id,
                    'project_type_name' => $project_type->first()->project_type_name
                ] : null;
    }
}
