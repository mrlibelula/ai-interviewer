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
        $options = new stdClass;
        $options->metrics = new stdClass;
        $options->metrics->performance = [
            'ai_problem_specific_feedback_history' => [
                // [
                //     'id' => 1,
                //     'nb_solved_challenges' => 0,
                //     'prompt' => '',
                //     'ai_feedback' => '',
                //     'created_at' => '',
                // ],
            ],
            'ai_optimization_feedback_history' => [
                // [
                //     'id' => 1,
                //     'nb_solved_challenges' => 0,
                //     'prompt' => '',
                //     'ai_feedback' => '',
                //     'created_at' => '',
                // ],
            ],
            'ai_best_practices_feedback_history' => [
                // [
                //     'id' => 1,
                //     'nb_solved_challenges' => 0,
                //     'prompt' => '',
                //     'ai_feedback' => '',
                //     'created_at' => '',
                // ],
            ],

        ];
        Schema::table('users', function (Blueprint $table) use ($options) {
            $table->longText('options')->default(json_encode($options))->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('options');
        });
    }
};
