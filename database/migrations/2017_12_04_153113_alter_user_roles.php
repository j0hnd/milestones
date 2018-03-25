<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUserRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_roles', function($table) {
            $table->tinyInteger('_create')->after('role_name')->default(0);
            $table->tinyInteger('_edit')->after('_create')->default(0);
            $table->tinyInteger('_view')->after('_edit')->default(0);
            $table->tinyInteger('_delete')->after('_view')->default(0);
            $table->tinyInteger('is_notify_email')->after('_delete')->default(0);
            $table->tinyInteger('is_admin')->after('is_notify_email')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_roles', function($table) {
            $table->dropColumn('_create');
            $table->dropColumn('_edit');
            $table->dropColumn('_view');
            $table->dropColumn('_delete');
            $table->dropColumn('is_notify_email');
            $table->dropColumn('is_admin');
        });
    }
}
