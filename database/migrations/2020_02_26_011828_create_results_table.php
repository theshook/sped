<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('results', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('assessment_id');
			$table->unsignedBigInteger('checklist_id');
			$table->timestamps();
			
			
			$table->foreign('assessment_id')->references('id')->on('assessments');
			$table->foreign('checklist_id')->references('id')->on('checklists');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('results');
    }
}