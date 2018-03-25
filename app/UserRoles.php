<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRoles extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "user_roles";

    protected $fillable = ['role_name', '_create', '_edit', '_view', '_delete', 'is_notify_email', 'is_admin'];


    public static function get_user_roles()
    {
        $data = self::where(['is_active' => 1]);

        return $data->count() ? $data->get() : null;
    }

    public static function get_user_role($id)
    {
        $data = self::where(['is_active' => 1, 'id' => $id]);

        return $data->count() ? $data->first() : null;
    }
}
