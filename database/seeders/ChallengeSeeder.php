<?php

namespace Database\Seeders;

use App\Models\Status;
use App\Models\Challenge;
use App\Models\Difficulty;
use App\Models\Framework;
use App\Models\Language;
use App\Models\Package;
use App\Models\Topic;
use App\Models\Visibility;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class ChallengeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
            ========================
            START CHALLENGE CREATION
            ========================
        */

        // create challenge model
        $challenge_name = 'Build a Simple To-Do List Application using Tailwind CSS and Alpine.js';

        $challenge = Challenge::firstOrCreate([
            'title' => $challenge_name, 
            'description' => '', 
            'challenge_slug' => Str::slug($challenge_name), // this name works for description view, initial code, solution code, and folder name
            'difficulty_id' => Difficulty::select('id')->where('name', '=', 'easy')->first()->id, 
            'time_limit' => '00:45:00', 
            'status_id' => Status::select('id')->where('name', '=', 'active')->first()->id, 
            'visibility_id' => Visibility::select('id')->where('name', '=', 'public')->first()->id,
        ]);

        if ($challenge->wasRecentlyCreated) {
            $challenge_id = $challenge->id;
            $challenge_slug = $challenge->challenge_slug;
    
            // assign topic/s
            $topic_front_end = Topic::select('id', 'name')->where('name', 'like', '%frontend%')->first();
            $topics = [ $topic_front_end ];
            
            if (count($topics)) {
                foreach ($topics as $topic) {
                    $challenge->addTopic($topic);
                }
            }
    
            // assign framework/s
            $framework_laravel = Framework::select('id', 'name')->where('name', 'like', '%laravel%')->first();
            $frameworks = [ $framework_laravel ];
    
            if (count($frameworks)) {
                foreach ($frameworks as $framework) {
                    $challenge->addFramework($framework);
                }
            }
    
            // assign language/s
            $language_html = Language::select('id', 'name')->where('name', 'like', '%html%')->first();
            $language_css = Language::select('id', 'name')->where('name', 'like', '%css%')->first();
            $language_javascript = Language::select('id', 'name')->where('name', 'like', '%javascript%')->first();
    
            $languages = [ $language_css, $language_html, $language_javascript ];
    
            if (count($languages)) {
                foreach ($languages as $language) {
                    $challenge->addLanguage($language);
                }
            }
    
            // assign package/s
    
            $package_livewire = Package::select('id', 'name')->where('name', 'like', '%livewire%')->first();
            $package_alpine = Package::select('id', 'name')->where('name', 'like', '%alpine%')->first();
            $package_tailwind = Package::select('id', 'name')->where('name', 'like', '%tailwind%')->first();
    
            $packages = [ $package_alpine, $package_livewire, $package_tailwind ];
    
            if (count($packages)) {
                foreach ($packages as $package) {
                    $challenge->addPackage($package);
                }
            }
    
            // create storage/app/challenges/<challenge_id>.<challenge_slug>/ for initial and solution code (contemplate multiple files)
            $storage_root_path = 'challenges/' . $challenge_id . '.' . $challenge_slug . '/';
            !Storage::exists($storage_root_path) && Storage::makeDirectory($storage_root_path);
            $storage_initial_code = $storage_root_path . 'initial_code/';
            !Storage::exists($storage_initial_code) && Storage::makeDirectory($storage_initial_code);
            $storage_solution_code = $storage_root_path . 'solution_code/';
            !Storage::exists($storage_solution_code) && Storage::makeDirectory($storage_solution_code);
    
            /* 
                create livewire component for description view of the challenge: 
                resources/views/livewire/challenges/<challenge_id>/<component-name>.blade.php
                app/Livewire/Challenges/<challenge_id>/<ComponentName>.php
            */
            $component_name = ucfirst(Str::camel($challenge_slug));
            Artisan::call('make:livewire challenges.' . $challenge_id . '.' . $component_name);
    
            // $challenge = Challenge::with('difficulty', 'status', 'visibility', 'tags:name', 'languages:name', 'frameworks:name', 'packages:name', 'topics:name')->whereId(1)->first()
        }

        /*
            ======================
            END CHALLENGE CREATION
            ======================
        */
    }
}
