<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tests', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('teacher_id');
			$table->string('question');
			$table->string('choice1');
			$table->string('choice2');
			$table->string('choice3');
			$table->string('choice4');
			$table->string('answer');
			$table->timestamps();
			
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
        Schema::dropIfExists('tests');
    }
}
