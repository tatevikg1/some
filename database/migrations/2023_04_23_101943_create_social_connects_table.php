<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('social_connects', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('user_id')->unsigned();
            $table->unsignedTinyInteger('type');
            $table->string('token_id');
            $table->json('params')->nullable();
            $table->boolean('is_blocked')->default(false)->index('is_blocked');
            $table->string('blocked_reason', 100)->default('');
            $table->timestamps();

            $table->unique(['user_id', 'token_id', 'type'], 'user_id_token_id_unique');
            $table->index(['user_id', 'type'], 'user_id_type_index');
            $table->index(['user_id', 'type', 'token_id'], 'user_id_type_token_index');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_connects');
    }
};
