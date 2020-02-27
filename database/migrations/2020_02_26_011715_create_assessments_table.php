<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssessmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assessments', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('teacher_id');
			$table->unsignedBigInteger('pupil_id');
			$table->unsignedBigInteger('test_id');
			$table->integer('assessment_no');
			$table->dateTime('ass_date');
			$table->timestamps();
			
			
			$table->foreign('teacher_id')->references('id')->on('teachers');
			$table->foreign('pupil_id')->references('id')->on('pupils');
			$table->foreign('test_id')->references('id')->on('tests');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assessments');
    }
}
