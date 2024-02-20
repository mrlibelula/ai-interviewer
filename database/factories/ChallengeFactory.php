<?php

namespace Database\Factories;

use App\Models\Status;
use App\Models\Difficulty;
use App\Models\Visibility;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Challenge>
 */
class ChallengeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $script_extensions = [
            'php', 'html', 'css', 'js', 'json', 'sql', 'py', 
        ];

        shuffle($script_extensions);

        return [
            'title' => $this->faker->text(50), 
            'descr_blade_filename' => $this->faker->word() . '.blade.php', 
            'difficulty_id' => Difficulty::inRandomOrder()->first()->id, 
            'initial_code_script_filename' => $this->faker->word() . '' . $script_extensions[0], 
            'solution_script_filename' => $this->faker->word() . '' . $script_extensions[0], 
            'time_limit' => \Carbon\CarbonInterval::minutes(rand(5, 90))->cascade()->format('%H:%I:%S'), 
            'status_id' => Status::inRandomOrder()->first()->id, 
            'visibility_id' => Visibility::inRandomOrder()->first()->id, 
        ];
    }
}
