<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Prompt extends Component
{
    public string $build_01_intro_prompt_text = 'A code challenge commonly assessed in technical interviews. Give me your response in JSON format, example output format:';
    public string $build_02_json_solution_code_separator = '%%%%%';
    public string $build_03_difficulty_level = 'The difficulty level must be " ??difficulty_level ".';
    public string $build_04_solution_code = 'The "solution_code" area must contain the code with the latest standard recommendations (es6, psr7, pep8, etc.) and must be after "¿¿¿¿¿" characters.';
    public string $build_05_arrays = 'The "frameworks", "packages", "test_cases" and "languages" keys can be empty arrays.';
    public string $build_06_languages = 'Append at least one language to languages array.';
    public string $build_07_random_topic = 'The topic of the challenge must be contained in this topics list " ??topics "';
    public string $build_07_selected_topic = 'The topic of the challenge is " ??Topic "';
    public string $build_08_tags = 'The selected "tags" must be contained in this tags list: " ??tags ".';
    public string $build_09_end_prompt = 'No line break between JSON and solution_code. Double check the solution_code';

    public function render()
    {
        return view('livewire.admin.prompt')
            ->layout('layouts.app');
    }
}
