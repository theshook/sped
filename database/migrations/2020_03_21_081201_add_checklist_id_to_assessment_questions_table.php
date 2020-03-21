<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChecklistIdToAssessmentQuestionsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('assessment_questions', function (Blueprint $table) {
      $table->unsignedBigInteger('checklist_id');

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
    Schema::table('assessment_questions', function (Blueprint $table) {
      //
    });
  }
}
