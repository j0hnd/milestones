<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForgotPasswordLogs extends Model
{
    protected $fillable = [
        'email',
        'hash',
        'log_expiration'
    ];

    protected $guarded = ['hash'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "forgot_password_logs";

    protected $primary_key = 'email';
}
