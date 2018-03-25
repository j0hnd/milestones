<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMilestoneAssignees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('milestone_assignees', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('mid');
            $table->uuid('uid');
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();

            $table->index(['mid', 'uid', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('milestone_assignees');
    }
}
