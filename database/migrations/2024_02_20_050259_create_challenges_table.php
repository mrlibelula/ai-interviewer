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
        Schema::create('challenges', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->default('');
            $table->string('challenge_slug');
            $table->unsignedBigInteger('difficulty_id'); // easy, medium, hard
            $table->mediumText('test_cases')->default(json_encode([]));
            $table->string('hints')->default('');
            $table->string('time_limit')->default('00:00:00');
            $table->unsignedBigInteger('status_id'); // active, inactive, archived
            $table->unsignedBigInteger('visibility_id'); // private, public
            $table->mediumText('options')->default(json_encode(new stdClass));
            $table->mediumText('solution_code')->default('// found no solution code');
            $table->text('chatgpt_prompt')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['difficulty_id', 'status_id', 'visibility_id']);
            
            $table->foreign('difficulty_id')
                ->references('id')
                ->on('difficulties');

            $table->foreign('status_id')
                ->references('id')
                ->on('statuses');

            $table->foreign('visibility_id')
                ->references('id')
                ->on('visibilities');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenges');
    }
};
