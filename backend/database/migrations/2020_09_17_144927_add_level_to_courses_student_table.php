<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLevelToCoursesStudentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses_student', function (Blueprint $table) {
            $table->integer('level')->nullable();
            $table->integer('credit')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses_student', function (Blueprint $table) {
            $table->dropColumn('level');
            $table->dropColumn('credit');
        });
    }
}
