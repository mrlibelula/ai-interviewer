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
        $prompt_parts_json = new stdClass;
        $prompt_parts_json->parts = [];
        $tokens = new stdClass;

        Schema::create('enviros', function (Blueprint $table) use ($prompt_parts_json, $tokens) {
            $table->id();
            $table->mediumText('prompt')->default(json_encode($prompt_parts_json));
            $table->text('openai')->default(json_encode($tokens->tokens = [
                'usage' => new stdClass, 
                'request_limit' => new stdClass, 
                'token_limit' => new stdClass, 
            ]));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enviros');
    }
};
