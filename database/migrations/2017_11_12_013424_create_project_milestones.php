<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectMilestones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_milestones', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('pid');
            $table->uuid('mid')->unique();
            $table->date('announcement')->nullable();
            $table->tinyInteger('is_announcement')->default(0);
            $table->date('scoping_design')->nullable();
            $table->tinyInteger('is_scoping_design')->default(0);
            $table->date('advertising')->nullable();
            $table->tinyInteger('is_advertising')->default(0);
            $table->date('award')->nullable();
            $table->tinyInteger('is_award')->default(0);
            $table->date('commencement')->nullable();
            $table->tinyInteger('is_commencement')->default(0);
            $table->date('20_percent_complete')->nullable();
            $table->tinyInteger('is_20_percent_complete')->default(0);
            $table->date('40_percent_complete')->nullable();
            $table->tinyInteger('is_40_percent_complete')->default(0);
            $table->date('60_percent_complete')->nullable();
            $table->tinyInteger('is_60_percent_complete')->default(0);
            $table->date('80_percent_complete')->nullable();
            $table->tinyInteger('is_80_percent_complete')->default(0);
            $table->date('practical_completion')->nullable();
            $table->tinyInteger('is_practical_completion')->default(0);
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();

            $table->index(['pid', 'mid', 'is_active']);
            $table->index('announcement');
            $table->index('scoping_design');
            $table->index('advertising');
            $table->index('award');
            $table->index('commencement');
            $table->index('20_percent_complete');
            $table->index('40_percent_complete');
            $table->index('60_percent_complete');
            $table->index('80_percent_complete');
            $table->index('practical_completion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_milestones');
    }
}
