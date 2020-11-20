<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostCommentFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_comment_file', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('comment_id')->constrained('post_comment')->onUpdate('cascade')->onDelete('cascade');
            $table->string('name');
            $table->string('old_name');
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
        Schema::dropIfExists('post_comment_file');
    }
}
