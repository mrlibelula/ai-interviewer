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
use App\Models\Topic;
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
            'name' => 'easy', 
            'description' => 'Coding challenges categorized as "easy" typically involve straightforward problems that require basic programming concepts and minimal problem-solving skills. These challenges are suitable for beginners and serve as a gentle introduction to coding exercises', 
        ]);
        $difficulty_medium = Difficulty::firstOrCreate([
            'name' => 'medium', 
            'description' => 'Challenges classified as "medium" difficulty present more complex problems that require a deeper understanding of programming principles and problem-solving techniques. They may involve multiple steps or require applying several concepts in combination', 
        ]);
        $difficulty_hard = Difficulty::firstOrCreate([
            'name' => 'hard', 
            'description' => '"Hard" difficulty challenges pose significant challenges that demand advanced problem-solving skills, algorithmic thinking, and proficiency in the chosen programming language. These challenges often involve intricate logic, optimization, or creative solutions and are suitable for experienced developers or those seeking a rigorous test of their abilities', 
        ]);

        $difficulties = [$difficulty_easy, $difficulty_medium, $difficulty_hard, ];

        // seed statuses

        $status_active = Status::firstOrCreate(['name' => 'active']);
        $status_inactive = Status::firstOrCreate(['name' => 'inactive']);
        $status_archived = Status::firstOrCreate(['name' => 'archived']);

        $statuses = [$status_active, $status_inactive, $status_archived, ];

        // seed visibilities

        $visibility_private = Visibility::firstOrCreate(['name' => 'private']);
        $visibility_public = Visibility::firstOrCreate(['name' => 'public']);

        $visibilities = [$visibility_private, $visibility_public, ];

        // seed tags

        $tag_front_end = Tag::firstOrCreate(['name' => 'Front-End']);
        $tag_back_end = Tag::firstOrCreate(['name' => 'Back-End']);
        $tag_full_stack = Tag::firstOrCreate(['name' => 'Full-Stack']);
        $tag_dev_ops = Tag::firstOrCreate(['name' => 'Dev-Ops']);
        $tag_db = Tag::firstOrCreate(['name' => 'Database']);
        $tag_storage = Tag::firstOrCreate(['name' => 'Storage']);
        $tag_ecommerce = Tag::firstOrCreate(['name' => 'eCommerce']);
        $tag_design_patterns = Tag::firstOrCreate(['name' => 'Design Patterns']);
        $tag_setup = Tag::firstOrCreate(['name' => 'Setup']);
        $tag_machine_learning = Tag::firstOrCreate(['name' => 'Machine Learning']);
        $tag_block_chain = Tag::firstOrCreate(['name' => 'Block Chain']);
        $tag_bootcamp = Tag::firstOrCreate(['name' => 'Bootcamp']);
        $tag_learning = Tag::firstOrCreate(['name' => 'Learning']);
        $tag_docs = Tag::firstOrCreate(['name' => 'Docs']);
        $tag_portfolio = Tag::firstOrCreate(['name' => 'Portfolio']);
        $tag_data_structures = Tag::firstOrCreate(['name' => 'Data Structures']);
        $tag_algorithms = Tag::firstOrCreate(['name' => 'Algorithms']);
        $tag_interview = Tag::firstOrCreate(['name' => 'Interview']);
        $tag_ui = Tag::firstOrCreate(['name' => 'UI']);
        $tag_ux = Tag::firstOrCreate(['name' => 'UX']);
        $tag_dx = Tag::firstOrCreate(['name' => 'DX']);
        $tag_hosting = Tag::firstOrCreate(['name' => 'Hosting']);
        $tag_debug = Tag::firstOrCreate(['name' => 'Debug']);
        $tag_cpanel = Tag::firstOrCreate(['name' => 'cPanel']);
        $tag_ssh = Tag::firstOrCreate(['name' => 'SSH']);
        $tag_git = Tag::firstOrCreate(['name' => 'Git']);
        $tag_terminal = Tag::firstOrCreate(['name' => 'Terminal']);
        $tag_i18n = Tag::firstOrCreate(['name' => 'i18n']);
        $tag_marketing = Tag::firstOrCreate(['name' => 'Marketing']);
        $tag_recruiting = Tag::firstOrCreate(['name' => 'Recruiting']);
        $tag_linkedin = Tag::firstOrCreate(['name' => 'LinkedIn']);
        $tag_ffmpeg = Tag::firstOrCreate(['name' => 'FFMpeg']);
        $tag_icons = Tag::firstOrCreate(['name' => 'Icons']);
        $tag_auth = Tag::firstOrCreate(['name' => 'Auth']);
        $tag_sanctum = Tag::firstOrCreate(['name' => 'Sanctum']);
        $tag_web_console = Tag::firstOrCreate(['name' => 'Web Console']);
        $tag_toastr = Tag::firstOrCreate(['name' => 'Toastr']);
        $tag_software_engineering = Tag::firstOrCreate(['name' => 'Software Engineering']);
        $tag_ai = Tag::firstOrCreate(['name' => 'AI']);
        $tag_game_dev = Tag::firstOrCreate(['name' => 'Game Development']);
        $tag_math = Tag::firstOrCreate(['name' => 'Math']);
        $tag_crypto = Tag::firstOrCreate(['name' => 'Crypto Currency']);
        $tag_backup = Tag::firstOrCreate(['name' => 'Backup']);
        $tag_deploy = Tag::firstOrCreate(['name' => 'Deployment']);
        $tag_slack = Tag::firstOrCreate(['name' => 'Slack']);
        $tag_package = Tag::firstOrCreate(['name' => 'Package']);
        $tag_middleware = Tag::firstOrCreate(['name' => 'Middleware']);
        $tag_localization = Tag::firstOrCreate(['name' => 'Localization']);
        $tag_tall_stack = Tag::firstOrCreate(['name' => 'TALL Stack']);
        $tag_dependency = Tag::firstOrCreate(['name' => 'Dependency']);
        $tag_sociability = Tag::firstOrCreate(['name' => 'Sociability']);
        $tag_professionalism = Tag::firstOrCreate(['name' => 'Professionalism']);
        $tag_energy = Tag::firstOrCreate(['name' => 'Energy']);
        $tag_communication = Tag::firstOrCreate(['name' => 'Communication']);

        $tags = [$tag_front_end, $tag_back_end, $tag_full_stack, $tag_dev_ops, $tag_db, $tag_storage, $tag_ecommerce, $tag_design_patterns, $tag_setup, $tag_machine_learning, $tag_block_chain, $tag_bootcamp, $tag_learning, $tag_docs, $tag_portfolio, $tag_data_structures, $tag_interview, $tag_ui, $tag_ux, $tag_dx, $tag_hosting, $tag_debug, $tag_cpanel, $tag_ssh, $tag_git, $tag_terminal, $tag_i18n, $tag_marketing, $tag_recruiting, $tag_linkedin, $tag_ffmpeg, $tag_icons, $tag_auth, $tag_sanctum, $tag_web_console, $tag_toastr, $tag_software_engineering, $tag_ai, $tag_game_dev, $tag_math, $tag_crypto, $tag_backup, $tag_deploy, $tag_slack, $tag_package, $tag_middleware, $tag_localization, $tag_tall_stack, $tag_dependency, $tag_sociability, $tag_professionalism, $tag_energy, $tag_communication, $tag_algorithms, ];

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

        // seed topics (tree)

        $topic_data_structures = Topic::firstOrCreate([
            'name' => 'Data Structures', 
            'description' => '', 
            'parent_id' => null, 
        ]);

        $topic_algorithms = Topic::firstOrCreate([
            'name' => 'Algorithms', 
            'description' => '', 
            'parent_id' => null, 
        ]);

        $topic_algorithms_search = Topic::firstOrCreate([
            'name' => 'Search algorithms', 
            'description' => '', 
            'parent_id' => 2, 
        ]);

        $topic_algorithms_sort = Topic::firstOrCreate([
            'name' => 'Sort algorithms', 
            'description' => '', 
            'parent_id' => 2, 
        ]);

        $topic_algorithms_sort_bubble = Topic::firstOrCreate([
            'name' => 'Bubble sort', 
            'description' => '', 
            'parent_id' => 4, 
        ]);

        $topic_data_structures = Topic::firstOrCreate([
            'name' => 'Linked list', 
            'description' => '', 
            'parent_id' => 1, 
        ]);

        $topics = [$topic_data_structures, $topic_algorithms, $topic_algorithms_search, $topic_algorithms_sort, $topic_algorithms_sort_bubble, ];

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

        // assign a random topic to all challenges
        
        $challenges->each(function ($challenge) use ($topics) {
            $copy_topics = $topics;
            shuffle($copy_topics);
            $topic = array_shift($copy_topics);
            $challenge->addTopic($topic);
        });


    }
}
