<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMilestoneLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('milestone_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('mid');
            $table->string('field_name', 30);
            $table->string('old_value', 255);
            $table->string('new_value', 255);
            $table->text('comment')->nullable();
            $table->uuid('updated_by');
            $table->timestamps();

            $table->index('mid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('milestone_logs');
    }
}
