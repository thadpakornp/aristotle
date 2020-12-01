<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id_from')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('user_id_to')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('noti_type',['0'])->default('0')->comment('0 = comment notify');
            $table->enum('status',['0','1'])->default('0')->comment('0 = unread,1 = read');
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
        Schema::dropIfExists('notifications');
    }
}
