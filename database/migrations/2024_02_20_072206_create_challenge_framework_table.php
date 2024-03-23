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
        Schema::create('challenge_framework', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('challenge_id');
            $table->unsignedBigInteger('framework_id');
            $table->timestamps();

            $table->unique(['challenge_id', 'framework_id']);

            $table->foreign('challenge_id')
                ->references('id')
                ->on('challenges')
                ->onDelete('cascade');

            $table->foreign('framework_id')
                ->references('id')
                ->on('frameworks')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenge_framework');
    }
};
