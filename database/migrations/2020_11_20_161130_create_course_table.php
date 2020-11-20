<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('stores_id')->constrained('stores')->onUpdate('cascade')->onDelete('cascade');
            $table->string('name_th');
            $table->string('name_en');
            $table->string('professor');
            $table->string('cover')->nullable();
            $table->string('full_cost');
            $table->string('discount_cost')->nullable();
            $table->integer('num_course');
            $table->integer('num_hour');
            $table->integer('num');
            $table->enum('type_course',['live','live-online','online']);
            $table->string('description');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course');
    }
}
