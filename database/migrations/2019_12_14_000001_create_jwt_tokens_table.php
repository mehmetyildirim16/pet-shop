<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJwtTokensTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up():void
    {
        Schema::create('jwt_tokens', function (Blueprint $table) {
            $table->id();
            $table->text('unique_id')->nullable();
            $table->uuid('user_id');
            $table->string('token_title');
            $table->json('restrictions')->nullable();
            $table->json('permissions')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('refreshed_at')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down():void
    {
        Schema::dropIfExists('jwt_tokens');
    }
}
