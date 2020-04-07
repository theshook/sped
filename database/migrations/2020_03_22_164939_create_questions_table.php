<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('questions', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->unsignedBigInteger('checklist_category_id');
      $table->unsignedBigInteger('teacher_id');
      $table->integer('question_type');
      $table->string('question');
      $table->string('choice1')->nullable();
      $table->string('choice2')->nullable();
      $table->string('choice3')->nullable();
      $table->string('choice4')->nullable();
      $table->string('answer');
      $table->text('explanation')->nullable();
      $table->softDeletes();
      $table->timestamps();

      $table->foreign('checklist_category_id')->references('id')->on('checklist_categories');
      $table->foreign('teacher_id')->references('id')->on('teachers');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('questions');
  }
}
