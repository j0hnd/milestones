<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Webpatser\Uuid\Uuid;


class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uid',
        'user_role_id',
        'name',
        'email',
        'password',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    public $timestamps = true;

    protected $guarded = ['uid'];

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "users";


    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->uid = Uuid::generate()->string;
        });
    }

    public static function get_user_info($uid)
    {
        $data = self::where([
                    'users.uid' => $uid,
                    'users.is_active' => 1
                ])
                ->join('user_roles', 'user_roles.id', '=', 'users.user_role_id')
                ->select(['users.uid', 'users.name', 'users.email', 'user_roles.id', 'user_roles.role_name']);

        return $data->count() ? $data->first() : null;
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = \Hash::make($value);
    }

    public static function get_user_list($me)
    {
        $data = self::where(['is_active'  => 1, 'is_deleted' => 0])->where('uid', '<>', $me);

        return $data->count() ? $data->get() : null;
    }

    public static function get_team_members()
    {
        $data = self::where(['users.is_active' => 1])
                    ->select([
                        'users.uid',
                        'users.name',
                        'users.email',
                        'user_roles.id',
                        'user_roles.role_name AS role'
                    ])
                    ->join('user_roles', 'user_roles.id', '=', 'users.user_role_id')
                    ->orderBy('users.created_at', 'desc');

        return $data->count() ? $data->get() : null;
    }

    public static function get_users_to_notify()
    {
        $user_role_info = UserRoles::where([
                'is_active' => 1,
                'is_notify_email' => 1
            ])
            ->select('id');

        $user_roles = null;
        $user_info  = null;

        if ($user_role_info->count()) {
            foreach ($user_role_info->get() as $role) {
                $user_roles[] = $role->id;
            }

            if (!is_null($user_roles)) {
                $user_info = self::where(['is_active' => 1, 'is_deleted' => 0])
                                ->whereIn('user_role_id', $user_roles)
                                ->select('email')->get();
            }
        }

        return $user_info;
    }

    public static function get_project_manager($name, $is_project_manager = 3)
    {
        try {
            $user_info_data = null;

            $user_info = self::where([
                                'users.is_active' => 1,
                                'users.name'      => $name,
                                'user_role_id'    => $is_project_manager
                            ])
                            ->join('user_roles', 'user_roles.id', '=', 'users.user_role_id')
                            ->select([
                                'users.uid',
                                'users.name',
                                'users.email',
                                'user_roles.id',
                                'user_roles.role_name AS role'
                            ]);

            if ($user_info->count()) {
                $user_info_data = $user_info->first();
            }

        } catch (\Exception $e) {
            throw $e;
        }

        return $user_info_data;
    }
}
