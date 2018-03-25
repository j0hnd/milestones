<?php

namespace App;

use App\AppModel;

class ProjectStatus extends AppModel
{
    protected $fillable = [
        'status_name'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "project_status";
}
