<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParentIdToPupilsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pupils', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->after('id');
            
            $table->foreign('parent_id')->references('id')->on('parents');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pupils', function (Blueprint $table) {
            //
        });
    }
}
