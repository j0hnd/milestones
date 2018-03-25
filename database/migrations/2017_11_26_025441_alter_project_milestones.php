<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProjectMilestones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_milestones', function($table) {
            $table->date('announcement_completed_at')->after('announcement')->nullable();
            $table->date('scoping_design_completed_at')->after('scoping_design')->nullable();
            $table->date('advertising_completed_at')->after('advertising')->nullable();
            $table->date('award_completed_at')->after('award')->nullable();
            $table->date('commencement_completed_at')->after('commencement')->nullable();
            $table->date('20_percent_completed_at')->after('20_percent_complete')->nullable();
            $table->date('40_percent_completed_at')->after('40_percent_complete')->nullable();
            $table->date('60_percent_completed_at')->after('60_percent_complete')->nullable();
            $table->date('80_percent_completed_at')->after('80_percent_complete')->nullable();
            $table->date('practical_completion_completed_at')->after('practical_completion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_milestones', function($table) {
            $table->dropColumn('announcement_completed_at');
            $table->dropColumn('scoping_desing_completed_at');
            $table->dropColumn('advertising_completed_at');
            $table->dropColumn('award_completed_at');
            $table->dropColumn('commencement_completed_at');
            $table->dropColumn('20_percent_completed_at');
            $table->dropColumn('40_percent_completed_at');
            $table->date('60_percent_completed_at');
            $table->date('80_percent_completed_at');
            $table->date('practical_completion_completed_at');
        });
    }
}
