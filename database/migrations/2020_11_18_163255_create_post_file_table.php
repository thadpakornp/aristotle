<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_file', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('post_id')->nullable()->constrained('post')->onUpdate('cascade')->onDelete('cascade');
            $table->string('name');
            $table->string('old_name');
            $table->string('type_file');
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
        Schema::dropIfExists('post_file');
    }
}
