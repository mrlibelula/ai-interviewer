<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Tag;
use App\Models\Status;
use App\Models\Package;
use App\Models\Language;
use App\Models\Challenge;
use App\Models\Framework;
use App\Models\Difficulty;
use App\Models\Visibility;
use App\Tool;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // seed difficulties
        $difficulty_easy = Difficulty::firstOrCreate([
            'title' => 'easy', 
        ]);
        $difficulty_medium = Difficulty::firstOrCreate([
            'title' => 'medium', 
        ]);
        $difficulty_hard = Difficulty::firstOrCreate([
            'title' => 'hard', 
        ]);

        $difficulties = [$difficulty_easy, $difficulty_medium, $difficulty_hard, ];

        // seed statuses
        $status_active = Status::firstOrCreate([
            'name' => 'active', 
        ]);
        $status_inactive = Status::firstOrCreate([
            'name' => 'inactive', 
        ]);
        $status_archived = Status::firstOrCreate([
            'name' => 'archived', 
        ]);

        $statuses = [$status_active, $status_inactive, $status_archived, ];

        // seed visibilities
        $visibility_private = Visibility::firstOrCreate([
            'name' => 'private', 
        ]);
        $visibility_public = Visibility::firstOrCreate([
            'name' => 'public', 
        ]);

        $visibilities = [$visibility_private, $visibility_public, ];

        // seed tags

        $tag_front_end = Tag::firstOrCreate(['title' => 'Front-End']);
        $tag_back_end = Tag::firstOrCreate(['title' => 'Back-End']);
        $tag_full_stack = Tag::firstOrCreate(['title' => 'Full-Stack']);
        $tag_dev_ops = Tag::firstOrCreate(['title' => 'Dev-Ops']);
        $tag_db = Tag::firstOrCreate(['title' => 'Database']);
        $tag_storage = Tag::firstOrCreate(['title' => 'Storage']);
        $tag_ecommerce = Tag::firstOrCreate(['title' => 'eCommerce']);
        $tag_design_patterns = Tag::firstOrCreate(['title' => 'Design Patterns']);
        $tag_setup = Tag::firstOrCreate(['title' => 'Setup']);
        $tag_machine_learning = Tag::firstOrCreate(['title' => 'Machine Learning']);
        $tag_block_chain = Tag::firstOrCreate(['title' => 'Block Chain']);
        $tag_bootcamp = Tag::firstOrCreate(['title' => 'Bootcamp']);
        $tag_learning = Tag::firstOrCreate(['title' => 'Learning']);
        $tag_docs = Tag::firstOrCreate(['title' => 'Docs']);
        $tag_portfolio = Tag::firstOrCreate(['title' => 'Portfolio']);
        $tag_data_structures = Tag::firstOrCreate(['title' => 'Data Structures']);
        $tag_interview = Tag::firstOrCreate(['title' => 'Interview']);
        $tag_ui = Tag::firstOrCreate(['title' => 'UI']);
        $tag_ux = Tag::firstOrCreate(['title' => 'UX']);
        $tag_dx = Tag::firstOrCreate(['title' => 'DX']);
        $tag_hosting = Tag::firstOrCreate(['title' => 'Hosting']);
        $tag_debug = Tag::firstOrCreate(['title' => 'Debug']);
        $tag_cpanel = Tag::firstOrCreate(['title' => 'cPanel']);
        $tag_ssh = Tag::firstOrCreate(['title' => 'SSH']);
        $tag_git = Tag::firstOrCreate(['title' => 'Git']);
        $tag_terminal = Tag::firstOrCreate(['title' => 'Terminal']);
        $tag_i18n = Tag::firstOrCreate(['title' => 'i18n']);
        $tag_marketing = Tag::firstOrCreate(['title' => 'Marketing']);
        $tag_recruiting = Tag::firstOrCreate(['title' => 'Recruiting']);
        $tag_linkedin = Tag::firstOrCreate(['title' => 'LinkedIn']);
        $tag_ffmpeg = Tag::firstOrCreate(['title' => 'FFMpeg']);
        $tag_icons = Tag::firstOrCreate(['title' => 'Icons']);
        $tag_auth = Tag::firstOrCreate(['title' => 'Auth']);
        $tag_sanctum = Tag::firstOrCreate(['title' => 'Sanctum']);
        $tag_web_console = Tag::firstOrCreate(['title' => 'Web Console']);
        $tag_toastr = Tag::firstOrCreate(['title' => 'Toastr']);
        $tag_software_engineering = Tag::firstOrCreate(['title' => 'Software Engineering']);
        $tag_ai = Tag::firstOrCreate(['title' => 'AI']);
        $tag_game_dev = Tag::firstOrCreate(['title' => 'Game Development']);
        $tag_math = Tag::firstOrCreate(['title' => 'Math']);
        $tag_crypto = Tag::firstOrCreate(['title' => 'Crypto Currency']);
        $tag_backup = Tag::firstOrCreate(['title' => 'Backup']);
        $tag_deploy = Tag::firstOrCreate(['title' => 'Deployment']);
        $tag_slack = Tag::firstOrCreate(['title' => 'Slack']);
        $tag_package = Tag::firstOrCreate(['title' => 'Package']);
        $tag_middleware = Tag::firstOrCreate(['title' => 'Middleware']);
        $tag_localization = Tag::firstOrCreate(['title' => 'Localization']);
        $tag_tall_stack = Tag::firstOrCreate(['title' => 'TALL Stack']);
        $tag_package = Tag::firstOrCreate(['title' => 'Package']);
        $tag_dependency = Tag::firstOrCreate(['title' => 'Dependency']);

        $tags = [$tag_front_end, $tag_back_end, $tag_full_stack, $tag_dev_ops, $tag_db, $tag_storage, $tag_ecommerce, $tag_design_patterns, $tag_setup, $tag_machine_learning, $tag_block_chain, $tag_bootcamp, $tag_learning, $tag_docs, $tag_portfolio, $tag_data_structures, $tag_interview, $tag_ui, $tag_ux, $tag_dx, $tag_hosting, $tag_debug, $tag_cpanel, $tag_ssh, $tag_git, $tag_terminal, $tag_i18n, $tag_marketing, $tag_recruiting, $tag_linkedin, $tag_ffmpeg, $tag_icons, $tag_auth, $tag_sanctum, $tag_web_console, $tag_toastr, $tag_software_engineering, $tag_ai, $tag_game_dev, $tag_math, $tag_crypto, $tag_backup, $tag_deploy, $tag_slack, $tag_package, $tag_middleware, $tag_localization, $tag_tall_stack, $tag_package, $tag_dependency, ];

        // seed languages

        $lang_js = Language::firstOrCreate(['name' => 'Javascript']);
        $lang_php = Language::firstOrCreate(['name' => 'PHP']);
        $lang_csharp = Language::firstOrCreate(['name' => 'C#']);
        $lang_sql = Language::firstOrCreate(['name' => 'SQL']);
        $lang_dart = Language::firstOrCreate(['name' => 'Dart']);
        $lang_py = Language::firstOrCreate(['name' => 'Python']);
        $lang_html = Language::firstOrCreate(['name' => 'HTML']);
        $lang_css = Language::firstOrCreate(['name' => 'CSS']);

        $langs = [$lang_js, $lang_php, $lang_csharp, $lang_sql, $lang_dart, $lang_py, $lang_html, $lang_css, ];

        // seed frameworks

        $frame_laravel = Framework::firstOrCreate(['name' => 'Laravel']);
        $frame_vue = Framework::firstOrCreate(['name' => 'Vue']);
        $frame_nuxt = Framework::firstOrCreate(['name' => 'Nuxt']);
        $frame_react = Framework::firstOrCreate(['name' => 'React']);
        $frame_express = Framework::firstOrCreate(['name' => 'Express']);
        $frame_node = Framework::firstOrCreate(['name' => 'Node']);
        $frame_flutter = Framework::firstOrCreate(['name' => 'Flutter']);
        $frame_firebase = Framework::firstOrCreate(['name' => 'Firebase']);
        $frame_unity = Framework::firstOrCreate(['name' => 'Unity']);
        $frame_unreal = Framework::firstOrCreate(['name' => 'Unreal']);
        $frame_docker = Framework::firstOrCreate(['name' => 'Docker']);
        $frame_django = Framework::firstOrCreate(['name' => 'Django']);

        $frameworks = [$frame_laravel, $frame_vue, $frame_nuxt, $frame_react, $frame_express, $frame_node, $frame_flutter, $frame_firebase, $frame_unity, $frame_unreal, $frame_docker, $frame_django,];

        // seed packages

        $pack_livewire = Package::firstOrCreate(['name' => 'Livewire']);
        $pack_alpine = Package::firstOrCreate(['name' => 'Alpine']);
        $pack_tailwind = Package::firstOrCreate(['name' => 'Tailwind']);
        $pack_vite = Package::firstOrCreate(['name' => 'Vite']);

        $packages = [$pack_livewire, $pack_alpine, $pack_tailwind, $pack_vite, ];

        // seed faker challenges

        Challenge::factory(10)->create();
        $challenges = Challenge::select('id')->get();

        // add random difficulty to challenges

        $challenges->each(function ($challenge) use ($difficulties) {
            $diff = Tool::randomItem($difficulties);
            $challenge->difficulty_id = $diff->id;
            $challenge->save();
        });

        // add random status to challenges

        $challenges->each(function ($challenge) use ($statuses) {
            $status = Tool::randomItem($statuses);
            $challenge->status_id = $status->id;
            $challenge->save();
        });

        // add random visibility to challenges

        $challenges->each(function ($challenge) use ($visibilities) {
            $vis = Tool::randomItem($visibilities);
            $challenge->visibility_id = $vis->id;
            $challenge->save();
        });
        
        // add random tags to all challenges
        
        $challenges->each(function ($challenge) use ($tags) {
            $copy_tags = $tags;
            shuffle($copy_tags);
            for ($i = 0; $i <= rand(1, count($copy_tags)); $i++) {
                $tag = array_shift($copy_tags);
                $challenge->addTag($tag);
            }
        });

        // add random languages to all challenges
        
        $challenges->each(function ($challenge) use ($langs) {
            $copy_langs = $langs;
            shuffle($copy_langs);
            for ($i = 0; $i <= rand(1, count($copy_langs)); $i++) {
                $lang = array_shift($copy_langs);
                $challenge->addLanguage($lang);
            }
        });

        // add random frameworks to all challenges
        
        $challenges->each(function ($challenge) use ($frameworks) {
            $copy_frames = $frameworks;
            shuffle($copy_frames);
            for ($i = 0; $i <= rand(1, count($copy_frames)); $i++) {
                $frame = array_shift($copy_frames);
                $challenge->addFramework($frame);
            }
        });

        // add random packages to all challenges
        
        $challenges->each(function ($challenge) use ($packages) {
            $copy_packages = $packages;
            shuffle($copy_packages);
            for ($i = 0; $i <= rand(1, count($copy_packages)); $i++) {
                $package = array_shift($copy_packages);
                $challenge->addPackage($package);
            }
        });


    }
}
