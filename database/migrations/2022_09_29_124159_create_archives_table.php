<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archives', function (Blueprint $table) {
            $table->id();
            $table->string('archive_id')->unique();
            $table->string('student_id');
            $table->string('department_id');
            $table->string('course_id');
            $table->integer('available_status')->default(1);
            $table->timestamps();

            $table->foreign('student_id')->references('student_id')->on('students');
            $table->foreign('department_id')->references('department_id')->on('departments');
            $table->foreign('course_id')->references('course_id')->on('courses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('archives');
    }
};
