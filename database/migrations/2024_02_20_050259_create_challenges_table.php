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
            $table->string('descr_blade_filename');
            $table->unsignedBigInteger('difficulty_id'); // easy, medium, hard
            $table->string('initial_code_script_filename')->nullable();
            $table->string('test_cases_json')->default(json_encode([]));
            $table->string('hints_json')->default(json_encode([]));
            $table->string('solution_script_filename');
            $table->string('time_limit')->default('00:00:00');
            $table->unsignedBigInteger('status_id'); // active, inactive, archived
            $table->unsignedBigInteger('visibility_id'); // private, public
            $table->timestamps();

            $table->index(['difficulty_id', 'status_id']);
            
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
