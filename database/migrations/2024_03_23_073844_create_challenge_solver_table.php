<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // challenge solver

        $openai_chat_settings = [
            'messages' => [],
        ];

        Schema::create('challenge_solver', function (Blueprint $table) use ($openai_chat_settings) {
            $table->unsignedBigInteger('challenge_id');
            $table->unsignedBigInteger('user_id');
            $table->string('current_time_limit')->default('00:00:00');
            $table->string('solved_time_seconds')->nullable();
            $table->integer('attempts')->default(0);
            $table->integer('bonus_xp')->default(0);
            $table->integer('extra_bonus')->default(0);
            $table->text('solution_code')->default('');
            $table->mediumText('openai_chat_settings')->default(json_encode($openai_chat_settings));
            $table->dateTime('solved_at')->nullable();
            $table->timestamps();
            $table->text('observations')->default(json_encode([]));

            $table->foreign('challenge_id')->references('id')->on('challenges')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->primary(['challenge_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenge_solver');
    }
};
