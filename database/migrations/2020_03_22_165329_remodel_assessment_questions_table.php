<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemodelAssessmentQuestionsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::dropIfExists('assessment_questions');

    Schema::create('assessment_questions', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->unsignedBigInteger('assessment_pupil_id');
      $table->unsignedBigInteger('question_id');
      $table->unsignedBigInteger('question_type');
      $table->string('answer');
      $table->string('correct');
      $table->string('wrong');
      $table->timestamps();

      $table->foreign('assessment_pupil_id')->references('id')->on('assessment_pupils');
      $table->foreign('question_id')->references('id')->on('questions');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    //
  }
}
