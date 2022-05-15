<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserReactionsTable extends Migration
{
    public function up()
    {
        Schema::create('user_reactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('from_user_id')->comment('User who reacted');
            $table->unsignedBigInteger('to_user_id')->comment('User you responded to');
            $table->enum('type', ['swipe']); // allows to add other reaction types
            $table->string('reaction', 30);

            // in some cases foreign indices are not wanted (due to performance markup)
            $table->foreign('from_user_id')->references('id')->on('users');
            $table->foreign('to_user_id')->references('id')->on('users');
            $table->index(['type', 'reaction']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_reactions');
    }
}
