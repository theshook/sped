<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssessmentPupilsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('assessment_pupils', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->unsignedBigInteger('assessment_id');
      $table->unsignedBigInteger('pupil_id');
      $table->string('is_locked');
      $table->bigInteger('total_score');
      $table->string('status');
      $table->text('recommendation');
      $table->string('validated_by');
      $table->date('validated_date');
      $table->string('closed_by');
      $table->date('closed_date');
      $table->softDeletes();
      $table->timestamps();

      $table->foreign('assessment_id')->references('id')->on('assessments');
      $table->foreign('pupil_id')->references('id')->on('pupils');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('assessment_pupils');
  }
}
