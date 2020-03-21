<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChecklistCategoryIdToAssessmentQuestionsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('assessment_questions', function (Blueprint $table) {
      $table->unsignedBigInteger('checklist_category_id');

      $table->foreign('checklist_category_id')->references('id')->on('checklist_categories');
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
