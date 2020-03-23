<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePupilsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pupils', function (Blueprint $table) {
					$table->bigIncrements('id');
					$table->unsignedBigInteger('school_id');
					$table->string('prof_pic');
					$table->string('first_name');
					$table->string('last_name');
					$table->string('middle_name');
					$table->date('birth_date');
					$table->softDeletes();
					$table->timestamps();
		
					$table->foreign('school_id')->references('id')->on('schools');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pupils');
    }
}
