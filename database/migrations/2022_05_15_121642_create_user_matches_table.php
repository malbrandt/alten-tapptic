<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserMatchesTable extends Migration
{
    public function up()
    {
        Schema::create('user_matches', function (Blueprint $table) {
            $table->unsignedBigInteger('first_user_id');
            $table->unsignedBigInteger('second_user_id');

            $table->foreign('first_user_id')->references('id')->on('users');
            $table->foreign('second_user_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_matches');
    }
}
