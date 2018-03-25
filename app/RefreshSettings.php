<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RefreshSettings extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "refresh_settings";

    protected $fillable = ['local_time', 'utc_time', 'timezone'];
}
