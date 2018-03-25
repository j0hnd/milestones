<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForgotPasswordLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forgot_password_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email', 100);
            $table->string('hash', 100);
            $table->integer('log_expiration');
            $table->tinyInteger('is_expired')->default(0);
            $table->timestamps();

            $table->index(['email', 'hash']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forgot_password_logs');
    }
}
