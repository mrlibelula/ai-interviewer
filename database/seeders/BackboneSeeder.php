<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Topic;
use App\Models\Status;
use App\Models\Package;
use App\Models\Language;
use App\Models\Framework;
use App\Models\Difficulty;
use App\Models\Visibility;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BackboneSeeder extends Seeder
{
    /**
     * Run the database seeds.
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
        $tag_docs = Tag::firstOrCreate(['name' => 'Documentation']);
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

        $tag_stats_01 = Tag::firstOrCreate(['name' => 'Probability Theory']);
        $tag_stats_02 = Tag::firstOrCreate(['name' => 'Statistical Analysis']);
        $tag_stats_03 = Tag::firstOrCreate(['name' => 'Descriptive Statistics']);
        $tag_stats_04 = Tag::firstOrCreate(['name' => 'Inferential Statistics']);
        $tag_stats_05 = Tag::firstOrCreate(['name' => 'Probability Distributions']);
        $tag_stats_06 = Tag::firstOrCreate(['name' => 'Hypothesis Testing']);
        $tag_stats_07 = Tag::firstOrCreate(['name' => 'Regression Analysis']);

        $tags = [$tag_front_end, $tag_back_end, $tag_full_stack, $tag_dev_ops, $tag_db, $tag_storage, $tag_ecommerce, $tag_design_patterns, $tag_setup, $tag_machine_learning, $tag_block_chain, $tag_bootcamp, $tag_learning, $tag_docs, $tag_portfolio, $tag_data_structures, $tag_interview, $tag_ui, $tag_ux, $tag_dx, $tag_hosting, $tag_debug, $tag_cpanel, $tag_ssh, $tag_git, $tag_terminal, $tag_i18n, $tag_marketing, $tag_recruiting, $tag_linkedin, $tag_ffmpeg, $tag_icons, $tag_auth, $tag_sanctum, $tag_web_console, $tag_toastr, $tag_software_engineering, $tag_ai, $tag_game_dev, $tag_math, $tag_crypto, $tag_backup, $tag_deploy, $tag_slack, $tag_package, $tag_middleware, $tag_localization, $tag_tall_stack, $tag_dependency, $tag_sociability, $tag_professionalism, $tag_energy, $tag_communication, $tag_algorithms, ];

        // seed languages

        $lang_js = Language::firstOrCreate(['name' => 'JavaScript']);
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
        
        // top parent topics 

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

        $topic_string_manipulation = Topic::firstOrCreate([
            'name' => 'String Manipulation', 
            'description' => '', 
            'parent_id' => null, 
        ]);

        $topic_dynamic_programming = Topic::firstOrCreate([
            'name' => 'Dynamic Programming', 
            'description' => '', 
            'parent_id' => null, 
        ]);

        $topic_math = Topic::firstOrCreate([
            'name' => 'Mathematics', 
            'description' => '', 
            'parent_id' => null, 
        ]);

        $topic_probability = Topic::firstOrCreate([
            'name' => 'Probability and Statistics', 
            'description' => '', 
            'parent_id' => null, 
        ]);

        $topic_system_design = Topic::firstOrCreate([
            'name' => 'System Design', 
            'description' => '', 
            'parent_id' => null, 
        ]);

        $topic_design_patterns = Topic::firstOrCreate([
            'name' => 'Design Patterns', 
            'description' => '', 
            'parent_id' => null, 
        ]);

        $topic_os = Topic::firstOrCreate([
            'name' => 'Operating Systems', 
            'description' => '', 
            'parent_id' => null, 
        ]);

        $topic_web_dev = Topic::firstOrCreate([
            'name' => 'Web Development (TALL Stack)', 
            'description' => '', 
            'parent_id' => null, 
        ]);

        $topic_web_deployment = Topic::firstOrCreate([
            'name' => 'Web Development (deployment)', 
            'description' => '', 
            'parent_id' => null, 
        ]);

        $topic_web_server_setup = Topic::firstOrCreate([
            'name' => 'Web Server Setup', 
            'description' => '', 
            'parent_id' => null, 
        ]);

        $topic_databases = Topic::firstOrCreate([
            'name' => 'Databases and DBA', 
            'description' => '', 
            'parent_id' => null, 
        ]);

        $topic_testing = Topic::firstOrCreate([
            'name' => 'Testing', 
            'description' => '', 
            'parent_id' => null, 
        ]);

        $topic_security = Topic::firstOrCreate([
            'name' => 'Security', 
            'description' => '', 
            'parent_id' => null, 
        ]);

        $topic_git = Topic::firstOrCreate([
            'name' => 'Git', 
            'description' => '', 
            'parent_id' => null, 
        ]);

        $topic_github = Topic::firstOrCreate([
            'name' => 'GitHub', 
            'description' => '', 
            'parent_id' => null, 
        ]);

        $topic_oop = Topic::firstOrCreate([
            'name' => 'Object-Oriented Programming (OOP)', 
            'description' => '', 
            'parent_id' => null, 
        ]);

        $topic_interview = Topic::firstOrCreate([
            'name' => 'Interview', 
            'description' => '', 
            'parent_id' => null, 
        ]);

        $topic_linkedin = Topic::firstOrCreate([
            'name' => 'LinkedIn for Professional Development', 
            'description' => '', 
            'parent_id' => null, 
        ]);

        $topic_MVP = Topic::firstOrCreate([
            'name' => 'Minimum Viable Product (MVP) Development', 
            'description' => '', 
            'parent_id' => null, 
        ]);

        $topic_ethical_considerations_in_software_development = Topic::firstOrCreate([
            'name' => 'Ethical Considerations in Software Development', 
            'description' => '', 
            'parent_id' => null, 
        ]);

        $topic_industry_trends = Topic::firstOrCreate([
            'name' => 'Industry Trends and Emerging Technologies', 
            'description' => '', 
            'parent_id' => null, 
        ]);

        $topic_entrepreneurship_and_startup_culture = Topic::firstOrCreate([
            'name' => 'Entrepreneurship and Startup Culture', 
            'description' => '', 
            'parent_id' => null, 
        ]);

        $topic_community_and_networking = Topic::firstOrCreate([
            'name' => 'Community and Networking', 
            'description' => '', 
            'parent_id' => null, 
        ]);

        $topic_remote_work_and_distributed_teams = Topic::firstOrCreate([
            'name' => 'Remote Work and Distributed Teams', 
            'description' => '', 
            'parent_id' => null, 
        ]);

        $topic_misc = Topic::firstOrCreate([
            'name' => 'Miscellaneous', 
            'description' => '', 
            'parent_id' => null, 
        ]);
        
        // child topics
        
        $topic_sorting_algorithms = Topic::firstOrCreate([
            'name' => 'Sorting Algorithms', 
            'description' => '', 
            'parent_id' => $topic_algorithms->id, 
        ]);
        
        $topic_searching_algorithms = Topic::firstOrCreate([
            'name' => 'Searching Algorithms', 
            'description' => '', 
            'parent_id' => $topic_algorithms->id, 
        ]);

        $topic_graph_algorithms = Topic::firstOrCreate([
            'name' => 'Graph Algorithms', 
            'description' => '', 
            'parent_id' => $topic_algorithms->id, 
        ]);

        $topic_bubble_sort = Topic::firstOrCreate([
            'name' => 'Bubble Sort', 
            'description' => '', 
            'parent_id' => $topic_sorting_algorithms->id, 
        ]);

        $topic_selection_sort = Topic::firstOrCreate([
            'name' => 'Selection Sort', 
            'description' => '', 
            'parent_id' => $topic_sorting_algorithms->id, 
        ]);

        $topic_insertion_sort = Topic::firstOrCreate([
            'name' => 'Insertion Sort', 
            'description' => '', 
            'parent_id' => $topic_sorting_algorithms->id, 
        ]);

        $topic_merge_sort = Topic::firstOrCreate([
            'name' => 'Merge Sort', 
            'description' => '', 
            'parent_id' => $topic_sorting_algorithms->id, 
        ]);

        $topic_quick_sort = Topic::firstOrCreate([
            'name' => 'Quick Sort', 
            'description' => '', 
            'parent_id' => $topic_sorting_algorithms->id, 
        ]);

        $topic_linear_search = Topic::firstOrCreate([
            'name' => 'Linear Search', 
            'description' => '', 
            'parent_id' => $topic_searching_algorithms->id, 
        ]);

        $topic_binary_search = Topic::firstOrCreate([
            'name' => 'Binary Search', 
            'description' => '', 
            'parent_id' => $topic_searching_algorithms->id, 
        ]);

        $topic_bfs = Topic::firstOrCreate([
            'name' => 'Breadth-First Search (BFS)', 
            'description' => '', 
            'parent_id' => $topic_graph_algorithms->id, 
        ]);

        $topic_dfs = Topic::firstOrCreate([
            'name' => 'Depth-First Search (DFS)', 
            'description' => '', 
            'parent_id' => $topic_graph_algorithms->id, 
        ]);

        $topic_dijkstras = Topic::firstOrCreate([
            'name' => 'Dijkstra\'s Algorithm', 
            'description' => '', 
            'parent_id' => $topic_graph_algorithms->id, 
        ]);

        $topic_bellman = Topic::firstOrCreate([
            'name' => 'Bellman-Ford Algorithm', 
            'description' => '', 
            'parent_id' => $topic_graph_algorithms->id, 
        ]);

        $topic_kruskals = Topic::firstOrCreate([
            'name' => 'Kruskal\'s Algorithm', 
            'description' => '', 
            'parent_id' => $topic_graph_algorithms->id, 
        ]);

        $topic_prims = Topic::firstOrCreate([
            'name' => 'Prim\'s Algorithm', 
            'description' => '', 
            'parent_id' => $topic_graph_algorithms->id, 
        ]);

        $topic_greedy = Topic::firstOrCreate([
            'name' => 'Greedy Algorithms', 
            'description' => '', 
            'parent_id' => $topic_algorithms->id, 
        ]);

        $topic_time_complexity = Topic::firstOrCreate([
            'name' => 'Time Complexity', 
            'description' => '', 
            'parent_id' => $topic_algorithms->id, 
        ]);

        $topic_space_complexity = Topic::firstOrCreate([
            'name' => 'Space Complexity', 
            'description' => '', 
            'parent_id' => $topic_algorithms->id, 
        ]);

        $topic_big_O_notation = Topic::firstOrCreate([
            'name' => 'Big O Notation', 
            'description' => '', 
            'parent_id' => $topic_algorithms->id, 
        ]);

        $topic_Analyzing_the_time_complexity_of_algorithms_in_different_scenarios = Topic::firstOrCreate([
            'name' => 'Analyzing the time complexity of algorithms in different scenarios', 
            'description' => '', 
            'parent_id' => $topic_algorithms->id, 
        ]);

        $topic_common_time_complexities = Topic::firstOrCreate([
            'name' => 'Common Time Complexities', 
            'description' => '', 
            'parent_id' => $topic_algorithms->id, 
        ]);

        $topic_arrays = Topic::firstOrCreate([
            'name' => 'Arrays', 
            'description' => '', 
            'parent_id' => $topic_data_structures->id, 
        ]);

        $topic_linked_lists = Topic::firstOrCreate([
            'name' => 'Linked Lists', 
            'description' => '', 
            'parent_id' => $topic_data_structures->id, 
        ]);

        $topic_stacks = Topic::firstOrCreate([
            'name' => 'Stacks', 
            'description' => '', 
            'parent_id' => $topic_data_structures->id, 
        ]);

        $topic_queues = Topic::firstOrCreate([
            'name' => 'Queues', 
            'description' => '', 
            'parent_id' => $topic_data_structures->id, 
        ]);

        $topic_trees = Topic::firstOrCreate([
            'name' => 'Trees', 
            'description' => '', 
            'parent_id' => $topic_data_structures->id, 
        ]);

        $topic_heaps = Topic::firstOrCreate([
            'name' => 'Heaps', 
            'description' => '', 
            'parent_id' => $topic_data_structures->id, 
        ]);

        $topic_hash_tables = Topic::firstOrCreate([
            'name' => 'Hash Tables', 
            'description' => '', 
            'parent_id' => $topic_data_structures->id, 
        ]);

        $topic_graphs = Topic::firstOrCreate([
            'name' => 'Graphs', 
            'description' => '', 
            'parent_id' => $topic_data_structures->id, 
        ]);

        $topic_singly_linked_lists = Topic::firstOrCreate([
            'name' => 'Singly Linked Lists', 
            'description' => '', 
            'parent_id' => $topic_linked_lists->id, 
        ]);

        $topic_doubly_linked_lists = Topic::firstOrCreate([
            'name' => 'Doubly Linked Lists', 
            'description' => '', 
            'parent_id' => $topic_linked_lists->id, 
        ]);

        $topic_circular_linked_lists = Topic::firstOrCreate([
            'name' => 'Circular Linked Lists', 
            'description' => '', 
            'parent_id' => $topic_linked_lists->id, 
        ]);

        $topic_binary_trees = Topic::firstOrCreate([
            'name' => 'Binary Trees', 
            'description' => '', 
            'parent_id' => $topic_trees->id, 
        ]);

        $topic_binary_search_trees = Topic::firstOrCreate([
            'name' => 'Binary Search Trees (BST)', 
            'description' => '', 
            'parent_id' => $topic_trees->id, 
        ]);

        $topic_avl_trees = Topic::firstOrCreate([
            'name' => 'AVL Trees', 
            'description' => '', 
            'parent_id' => $topic_trees->id, 
        ]);

        $topic_red_black_trees = Topic::firstOrCreate([
            'name' => 'Red-Black Trees', 
            'description' => '', 
            'parent_id' => $topic_trees->id, 
        ]);

        $topic_min_heap = Topic::firstOrCreate([
            'name' => 'Min Heap', 
            'description' => '', 
            'parent_id' => $topic_heaps->id, 
        ]);

        $topic_max_heap = Topic::firstOrCreate([
            'name' => 'Max Heap', 
            'description' => '', 
            'parent_id' => $topic_heaps->id, 
        ]);

        $topic_directed_graphs = Topic::firstOrCreate([
            'name' => 'Directed Graphs', 
            'description' => '', 
            'parent_id' => $topic_graphs->id, 
        ]);

        $topic_undirected_graphs = Topic::firstOrCreate([
            'name' => 'Undirected Graphs', 
            'description' => '', 
            'parent_id' => $topic_graphs->id, 
        ]);

        $topic_string_operations = Topic::firstOrCreate([
            'name' => 'String Operations (Concatenation, Substring, Length)', 
            'description' => '', 
            'parent_id' => $topic_string_manipulation->id, 
        ]);

        $topic_character_encoding = Topic::firstOrCreate([
            'name' => 'Character Encoding', 
            'description' => '', 
            'parent_id' => $topic_string_manipulation->id, 
        ]);

        $topic_regex = Topic::firstOrCreate([
            'name' => 'Regular Expressions', 
            'description' => '', 
            'parent_id' => $topic_string_manipulation->id, 
        ]);

        $topic_lcs = Topic::firstOrCreate([
            'name' => 'Longest Common Subsequence (LCS)', 
            'description' => '', 
            'parent_id' => $topic_dynamic_programming->id, 
        ]);

        $topic_lcs = Topic::firstOrCreate([
            'name' => 'Longest Common Subsequence (LCS)', 
            'description' => '', 
            'parent_id' => $topic_dynamic_programming->id, 
        ]);

        $topic_knapsack_problem = Topic::firstOrCreate([
            'name' => 'Knapsack Problem', 
            'description' => '', 
            'parent_id' => $topic_dynamic_programming->id, 
        ]);

        $topic_coin_change_problem = Topic::firstOrCreate([
            'name' => 'Coin Change Problem', 
            'description' => '', 
            'parent_id' => $topic_dynamic_programming->id, 
        ]);

        $topic_lis = Topic::firstOrCreate([
            'name' => 'Longest Increasing Subsequence (LIS)', 
            'description' => '', 
            'parent_id' => $topic_dynamic_programming->id, 
        ]);

        $topic_edit_distance = Topic::firstOrCreate([
            'name' => 'Edit Distance', 
            'description' => '', 
            'parent_id' => $topic_dynamic_programming->id, 
        ]);

        $topic_prime_numbers = Topic::firstOrCreate([
            'name' => 'Prime Numbers', 
            'description' => '', 
            'parent_id' => $topic_math->id, 
        ]);

        $topic_factorial = Topic::firstOrCreate([
            'name' => 'Factorial', 
            'description' => '', 
            'parent_id' => $topic_math->id, 
        ]);

        $topic_fibonacci = Topic::firstOrCreate([
            'name' => 'Fibonacci Sequence', 
            'description' => '', 
            'parent_id' => $topic_math->id, 
        ]);

        $topic_combinations_permutations = Topic::firstOrCreate([
            'name' => 'Combinations and Permutations', 
            'description' => '', 
            'parent_id' => $topic_math->id, 
        ]);

        $topic_bit_manipulation = Topic::firstOrCreate([
            'name' => 'Bit Manipulation', 
            'description' => '', 
            'parent_id' => $topic_math->id, 
        ]);

        $topic_number_theory = Topic::firstOrCreate([
            'name' => 'Number Theory', 
            'description' => '', 
            'parent_id' => $topic_math->id, 
        ]);

        $topic_scalability = Topic::firstOrCreate([
            'name' => 'Scalability', 
            'description' => '', 
            'parent_id' => $topic_system_design->id, 
        ]);

        $topic_load_balancing = Topic::firstOrCreate([
            'name' => 'Load Balancing', 
            'description' => '', 
            'parent_id' => $topic_system_design->id, 
        ]);

        $topic_caching = Topic::firstOrCreate([
            'name' => 'Caching', 
            'description' => '', 
            'parent_id' => $topic_system_design->id, 
        ]);

        $topic_database_design = Topic::firstOrCreate([
            'name' => 'Database Design', 
            'description' => '', 
            'parent_id' => $topic_system_design->id, 
        ]);

        $topic_microservices_architecture = Topic::firstOrCreate([
            'name' => 'Microservices Architecture', 
            'description' => '', 
            'parent_id' => $topic_system_design->id, 
        ]);

        $topic_monolythic_architecture = Topic::firstOrCreate([
            'name' => 'Monolythic Architecture', 
            'description' => '', 
            'parent_id' => $topic_system_design->id, 
        ]);

        $topic_API_design = Topic::firstOrCreate([
            'name' => 'API Design', 
            'description' => '', 
            'parent_id' => $topic_system_design->id, 
        ]);

        $topic_process_management = Topic::firstOrCreate([
            'name' => 'Process Management', 
            'description' => '', 
            'parent_id' => $topic_os->id, 
        ]);

        $topic_memory_management = Topic::firstOrCreate([
            'name' => 'Memory Management', 
            'description' => '', 
            'parent_id' => $topic_os->id, 
        ]);

        $topic_file_systems = Topic::firstOrCreate([
            'name' => 'File Systems', 
            'description' => '', 
            'parent_id' => $topic_os->id, 
        ]);

        $topic_synchronization_concurrency = Topic::firstOrCreate([
            'name' => 'Synchronization and Concurrency', 
            'description' => '', 
            'parent_id' => $topic_os->id, 
        ]);

        $topic_HTML_CSS = Topic::firstOrCreate([
            'name' => 'HTML/CSS', 
            'description' => '', 
            'parent_id' => $topic_web_dev->id, 
        ]);

        $topic_javascript = Topic::firstOrCreate([
            'name' => 'JavaScript', 
            'description' => '', 
            'parent_id' => $topic_web_dev->id, 
        ]);

        $topic_frontend = Topic::firstOrCreate([
            'name' => 'Frontend', 
            'description' => '', 
            'parent_id' => $topic_web_dev->id, 
        ]);

        $topic_backend = Topic::firstOrCreate([
            'name' => 'Backend', 
            'description' => '', 
            'parent_id' => $topic_web_dev->id, 
        ]);

        $topic_RESTful_APIs = Topic::firstOrCreate([
            'name' => 'RESTful APIs', 
            'description' => '', 
            'parent_id' => $topic_web_dev->id, 
        ]);

        $topic_authentication_authorization = Topic::firstOrCreate([
            'name' => 'Authentication and Authorization', 
            'description' => '', 
            'parent_id' => $topic_web_dev->id, 
        ]);

        $topic_database_management = Topic::firstOrCreate([
            'name' => 'Database Management (SQL, NoSQL)', 
            'description' => '', 
            'parent_id' => $topic_web_dev->id, 
        ]);

        $topic_deplyment = Topic::firstOrCreate([
            'name' => 'Deployment (Cloud, Shared)', 
            'description' => '', 
            'parent_id' => $topic_web_dev->id, 
        ]);

        $topic_classes_objects = Topic::firstOrCreate([
            'name' => 'Classes and Objects', 
            'description' => '', 
            'parent_id' => $topic_oop->id, 
        ]);

        $topic_inheritance = Topic::firstOrCreate([
            'name' => 'Inheritance', 
            'description' => '', 
            'parent_id' => $topic_oop->id, 
        ]);

        $topic_polymorphism = Topic::firstOrCreate([
            'name' => 'Polymorphism', 
            'description' => '', 
            'parent_id' => $topic_oop->id, 
        ]);

        $topic_encapsulation = Topic::firstOrCreate([
            'name' => 'Encapsulation', 
            'description' => '', 
            'parent_id' => $topic_oop->id, 
        ]);

        $topic_abstraction = Topic::firstOrCreate([
            'name' => 'Abstraction', 
            'description' => '', 
            'parent_id' => $topic_oop->id, 
        ]);

        $topic_recursion = Topic::firstOrCreate([
            'name' => 'Recursion', 
            'description' => '', 
            'parent_id' => $topic_oop->id, 
        ]);

        $topic_pointers = Topic::firstOrCreate([
            'name' => 'Pointers', 
            'description' => '', 
            'parent_id' => $topic_oop->id, 
        ]);

        $topic_backtracking = Topic::firstOrCreate([
            'name' => 'Backtracking', 
            'description' => '', 
            'parent_id' => $topic_misc->id, 
        ]);

        $topic_divide_conquer = Topic::firstOrCreate([
            'name' => 'Divide and Conquer', 
            'description' => '', 
            'parent_id' => $topic_misc->id, 
        ]);

        $topic_creational_patterns = Topic::firstOrCreate([
            'name' => 'Creational Patterns', 
            'description' => '', 
            'parent_id' => $topic_design_patterns->id, 
        ]);

        $topic_structural_patterns = Topic::firstOrCreate([
            'name' => 'Structural Patterns', 
            'description' => '', 
            'parent_id' => $topic_design_patterns->id, 
        ]);

        $topic_behavioral_patterns = Topic::firstOrCreate([
            'name' => 'Behavioral Patterns', 
            'description' => '', 
            'parent_id' => $topic_design_patterns->id, 
        ]);

        $topic_singleton = Topic::firstOrCreate([
            'name' => 'Singleton', 
            'description' => '', 
            'parent_id' => $topic_creational_patterns->id, 
        ]);

        $topic_factory_method = Topic::firstOrCreate([
            'name' => 'Factory Method', 
            'description' => '', 
            'parent_id' => $topic_creational_patterns->id, 
        ]);

        $topic_abstract_factory = Topic::firstOrCreate([
            'name' => 'Abstract Factory', 
            'description' => '', 
            'parent_id' => $topic_creational_patterns->id, 
        ]);

        $topic_builder = Topic::firstOrCreate([
            'name' => 'Builder', 
            'description' => '', 
            'parent_id' => $topic_creational_patterns->id, 
        ]);

        $topic_prototype = Topic::firstOrCreate([
            'name' => 'Prototype', 
            'description' => '', 
            'parent_id' => $topic_creational_patterns->id, 
        ]);

        $topic_adapter = Topic::firstOrCreate([
            'name' => 'Adapter', 
            'description' => '', 
            'parent_id' => $topic_structural_patterns->id, 
        ]);
        
        $topic_bridge = Topic::firstOrCreate([
            'name' => 'Bridge', 
            'description' => '', 
            'parent_id' => $topic_structural_patterns->id, 
        ]);

        $topic_composite = Topic::firstOrCreate([
            'name' => 'Composite', 
            'description' => '', 
            'parent_id' => $topic_structural_patterns->id, 
        ]);

        $topic_decorator = Topic::firstOrCreate([
            'name' => 'Decorator', 
            'description' => '', 
            'parent_id' => $topic_structural_patterns->id, 
        ]);

        $topic_facade = Topic::firstOrCreate([
            'name' => 'Facade', 
            'description' => '', 
            'parent_id' => $topic_structural_patterns->id, 
        ]);

        $topic_flyweight = Topic::firstOrCreate([
            'name' => 'Flyweight', 
            'description' => '', 
            'parent_id' => $topic_structural_patterns->id, 
        ]);

        $topic_proxy = Topic::firstOrCreate([
            'name' => 'Proxy', 
            'description' => '', 
            'parent_id' => $topic_structural_patterns->id, 
        ]);
        
        $topic_chain_responsibility = Topic::firstOrCreate([
            'name' => 'Chain of Responsibility', 
            'description' => '', 
            'parent_id' => $topic_behavioral_patterns->id, 
        ]);
        
        $topic_command = Topic::firstOrCreate([
            'name' => 'Command', 
            'description' => '', 
            'parent_id' => $topic_behavioral_patterns->id, 
        ]);

        $topic_interpreter = Topic::firstOrCreate([
            'name' => 'Interpreter', 
            'description' => '', 
            'parent_id' => $topic_behavioral_patterns->id, 
        ]);

        $topic_iterator = Topic::firstOrCreate([
            'name' => 'Iterator', 
            'description' => '', 
            'parent_id' => $topic_behavioral_patterns->id, 
        ]);

        $topic_mediator = Topic::firstOrCreate([
            'name' => 'Mediator', 
            'description' => '', 
            'parent_id' => $topic_behavioral_patterns->id, 
        ]);

        $topic_memento = Topic::firstOrCreate([
            'name' => 'Memento', 
            'description' => '', 
            'parent_id' => $topic_behavioral_patterns->id, 
        ]);

        $topic_observer = Topic::firstOrCreate([
            'name' => 'Observer', 
            'description' => '', 
            'parent_id' => $topic_behavioral_patterns->id, 
        ]);

        $topic_state = Topic::firstOrCreate([
            'name' => 'State', 
            'description' => '', 
            'parent_id' => $topic_behavioral_patterns->id, 
        ]);

        $topic_strategy = Topic::firstOrCreate([
            'name' => 'Strategy', 
            'description' => '', 
            'parent_id' => $topic_behavioral_patterns->id, 
        ]);

        $topic_template_method = Topic::firstOrCreate([
            'name' => 'Template Method', 
            'description' => '', 
            'parent_id' => $topic_behavioral_patterns->id, 
        ]);

        $topic_visitor = Topic::firstOrCreate([
            'name' => 'Visitor', 
            'description' => '', 
            'parent_id' => $topic_behavioral_patterns->id, 
        ]);

        $topic_unit_testing = Topic::firstOrCreate([
            'name' => 'Unit Testing', 
            'description' => '', 
            'parent_id' => $topic_testing->id, 
        ]);

        $topic_integration_testing = Topic::firstOrCreate([
            'name' => 'Integration Testing', 
            'description' => '', 
            'parent_id' => $topic_testing->id, 
        ]);

        $topic_functional_testing = Topic::firstOrCreate([
            'name' => 'Functional Testing', 
            'description' => '', 
            'parent_id' => $topic_testing->id, 
        ]);

        $topic_performance_testing = Topic::firstOrCreate([
            'name' => 'Performance Testing', 
            'description' => '', 
            'parent_id' => $topic_testing->id, 
        ]);

        $topic_security_testing = Topic::firstOrCreate([
            'name' => 'Security Testing', 
            'description' => '', 
            'parent_id' => $topic_testing->id, 
        ]);

        $topic_test_automation = Topic::firstOrCreate([
            'name' => 'Test Automation', 
            'description' => '', 
            'parent_id' => $topic_testing->id, 
        ]);

        $topic_code_coverage = Topic::firstOrCreate([
            'name' => 'Code Coverage', 
            'description' => '', 
            'parent_id' => $topic_testing->id, 
        ]);

        $topic_writing_test_cases = Topic::firstOrCreate([
            'name' => 'Writing test cases', 
            'description' => '', 
            'parent_id' => $topic_unit_testing->id, 
        ]);

        $topic_test_driven_development = Topic::firstOrCreate([
            'name' => 'Test-driven development (TDD)', 
            'description' => '', 
            'parent_id' => $topic_unit_testing->id, 
        ]);

        $topic_mocking_stubbing = Topic::firstOrCreate([
            'name' => 'Mocking and stubbing', 
            'description' => '', 
            'parent_id' => $topic_unit_testing->id, 
        ]);

        $topic_testing_interactions = Topic::firstOrCreate([
            'name' => 'Testing interactions between different components/modules', 
            'description' => '', 
            'parent_id' => $topic_integration_testing->id, 
        ]);

        $topic_API_testing = Topic::firstOrCreate([
            'name' => 'API testing', 
            'description' => '', 
            'parent_id' => $topic_integration_testing->id, 
        ]);

        $topic_service_virtualization = Topic::firstOrCreate([
            'name' => 'Service virtualization', 
            'description' => '', 
            'parent_id' => $topic_integration_testing->id, 
        ]);

        $topic_testing_as_a_whole = Topic::firstOrCreate([
            'name' => 'Testing the functionality of the system as a whole', 
            'description' => '', 
            'parent_id' => $topic_functional_testing->id, 
        ]);

        $topic_user_acceptance_testing = Topic::firstOrCreate([
            'name' => 'User acceptance testing (UAT)', 
            'description' => '', 
            'parent_id' => $topic_functional_testing->id, 
        ]);

        $topic_end_to_end_testing = Topic::firstOrCreate([
            'name' => 'End-to-end testing', 
            'description' => '', 
            'parent_id' => $topic_functional_testing->id, 
        ]);

        $topic_load_testing = Topic::firstOrCreate([
            'name' => 'Load testing', 
            'description' => '', 
            'parent_id' => $topic_performance_testing->id, 
        ]);

        $topic_stress_testing = Topic::firstOrCreate([
            'name' => 'Stress testing', 
            'description' => '', 
            'parent_id' => $topic_performance_testing->id, 
        ]);

        $topic_scalability_testing = Topic::firstOrCreate([
            'name' => 'Scalability testing', 
            'description' => '', 
            'parent_id' => $topic_performance_testing->id, 
        ]);

        $topic_vulnerability_scanning = Topic::firstOrCreate([
            'name' => 'Vulnerability scanning', 
            'description' => '', 
            'parent_id' => $topic_security_testing->id, 
        ]);

        $topic_penetration_testing = Topic::firstOrCreate([
            'name' => 'Penetration testing', 
            'description' => '', 
            'parent_id' => $topic_security_testing->id, 
        ]);

        $topic_security_auditing = Topic::firstOrCreate([
            'name' => 'Security auditing', 
            'description' => '', 
            'parent_id' => $topic_security_testing->id, 
        ]);

        $topic_writing_automated_test_scripts = Topic::firstOrCreate([
            'name' => 'Writing automated test scripts', 
            'description' => '', 
            'parent_id' => $topic_test_automation->id, 
        ]);

        $topic_test_frameworks = Topic::firstOrCreate([
            'name' => 'Test frameworks', 
            'description' => '', 
            'parent_id' => $topic_test_automation->id, 
        ]);

        $topic_measuring_code_coverage = Topic::firstOrCreate([
            'name' => 'Measuring code coverage', 
            'description' => '', 
            'parent_id' => $topic_code_coverage->id, 
        ]);

        $topic_statement_coverage = Topic::firstOrCreate([
            'name' => 'Statement coverage', 
            'description' => '', 
            'parent_id' => $topic_code_coverage->id, 
        ]);

        $topic_branch_coverage = Topic::firstOrCreate([
            'name' => 'Branch coverage', 
            'description' => '', 
            'parent_id' => $topic_code_coverage->id, 
        ]);

        $topic_intro_to_version_control = Topic::firstOrCreate([
            'name' => 'Introduction to Version Control', 
            'description' => '', 
            'parent_id' => $topic_git->id, 
        ]);

        $topic_basic_git_commands = Topic::firstOrCreate([
            'name' => 'Basic Git Commands', 
            'description' => '', 
            'parent_id' => $topic_git->id, 
        ]);

        $topic_branching_merging = Topic::firstOrCreate([
            'name' => 'Branching and Merging', 
            'description' => '', 
            'parent_id' => $topic_git->id, 
        ]);

        $topic_resolving_conflicts = Topic::firstOrCreate([
            'name' => 'Resolving Conflicts', 
            'description' => '', 
            'parent_id' => $topic_git->id, 
        ]);

        $topic_git_workflow_strategies = Topic::firstOrCreate([
            'name' => 'Git Workflow Strategies (e.g., Git Flow, GitHub Flow)', 
            'description' => '', 
            'parent_id' => $topic_git->id, 
        ]);

        $topic_git_best_practices = Topic::firstOrCreate([
            'name' => 'Git Best Practices', 
            'description' => '', 
            'parent_id' => $topic_git->id, 
        ]);

        $topic_git_init = Topic::firstOrCreate([
            'name' => 'git init', 
            'description' => '', 
            'parent_id' => $topic_basic_git_commands->id, 
        ]);

        $topic_git_clone = Topic::firstOrCreate([
            'name' => 'git clone', 
            'description' => '', 
            'parent_id' => $topic_basic_git_commands->id, 
        ]);

        $topic_git_add = Topic::firstOrCreate([
            'name' => 'git add', 
            'description' => '', 
            'parent_id' => $topic_basic_git_commands->id, 
        ]);

        $topic_git_commit = Topic::firstOrCreate([
            'name' => 'git commit', 
            'description' => '', 
            'parent_id' => $topic_basic_git_commands->id, 
        ]);

        $topic_git_push = Topic::firstOrCreate([
            'name' => 'git push', 
            'description' => '', 
            'parent_id' => $topic_basic_git_commands->id, 
        ]);

        $topic_git_pull = Topic::firstOrCreate([
            'name' => 'git pull', 
            'description' => '', 
            'parent_id' => $topic_basic_git_commands->id, 
        ]);

        $topic_git_checkout = Topic::firstOrCreate([
            'name' => 'git checkout', 
            'description' => '', 
            'parent_id' => $topic_basic_git_commands->id, 
        ]);

        $topic_git_merge = Topic::firstOrCreate([
            'name' => 'git merge', 
            'description' => '', 
            'parent_id' => $topic_basic_git_commands->id, 
        ]);

        $topic_git_branch = Topic::firstOrCreate([
            'name' => 'git branch', 
            'description' => '', 
            'parent_id' => $topic_basic_git_commands->id, 
        ]);

        $topic_intro_to_github = Topic::firstOrCreate([
            'name' => 'Introduction to GitHub', 
            'description' => '', 
            'parent_id' => $topic_github->id, 
        ]);

        $topic_creating_managing_repositories = Topic::firstOrCreate([
            'name' => 'Creating and Managing Repositories', 
            'description' => '', 
            'parent_id' => $topic_github->id, 
        ]);

        $topic_collaborating_with_others = Topic::firstOrCreate([
            'name' => 'Collaborating with Others', 
            'description' => '', 
            'parent_id' => $topic_github->id, 
        ]);

        $topic_issue_tracking_and_management = Topic::firstOrCreate([
            'name' => 'Issue Tracking and Management', 
            'description' => '', 
            'parent_id' => $topic_github->id, 
        ]);

        $topic_github_pages = Topic::firstOrCreate([
            'name' => 'GitHub Pages', 
            'description' => '', 
            'parent_id' => $topic_github->id, 
        ]);

        $topic_github_actions = Topic::firstOrCreate([
            'name' => 'GitHub Actions', 
            'description' => '', 
            'parent_id' => $topic_github->id, 
        ]);

        $topic_github_projects = Topic::firstOrCreate([
            'name' => 'GitHub Projects', 
            'description' => '', 
            'parent_id' => $topic_github->id, 
        ]);

        $topic_github_best_practices = Topic::firstOrCreate([
            'name' => 'GitHub Best Practices', 
            'description' => '', 
            'parent_id' => $topic_github->id, 
        ]);

        $topic_forking = Topic::firstOrCreate([
            'name' => 'Forking', 
            'description' => '', 
            'parent_id' => $topic_collaborating_with_others->id, 
        ]);

        $topic_pull_requests = Topic::firstOrCreate([
            'name' => 'Pull Requests', 
            'description' => '', 
            'parent_id' => $topic_collaborating_with_others->id, 
        ]);
        
        $topic_code_reviews = Topic::firstOrCreate([
            'name' => 'Code Reviews', 
            'description' => '', 
            'parent_id' => $topic_collaborating_with_others->id, 
        ]);
        
        $topic_interview_preparation_strategies = Topic::firstOrCreate([
            'name' => 'Interview Preparation Strategies', 
            'description' => '', 
            'parent_id' => $topic_interview->id, 
        ]);

        $topic_problem_solving_approaches = Topic::firstOrCreate([
            'name' => 'Problem-Solving Approaches', 
            'description' => '', 
            'parent_id' => $topic_interview->id, 
        ]);

        $topic_soft_skills_development = Topic::firstOrCreate([
            'name' => 'Soft Skills Development', 
            'description' => '', 
            'parent_id' => $topic_interview->id, 
        ]);

        $topic_career_development = Topic::firstOrCreate([
            'name' => 'Career Development', 
            'description' => '', 
            'parent_id' => $topic_interview->id, 
        ]);

        $topic_psychological_aspects = Topic::firstOrCreate([
            'name' => 'Psychological Aspects of Interviewing', 
            'description' => '', 
            'parent_id' => $topic_interview->id, 
        ]);

        $topic_effective_communication_techniques = Topic::firstOrCreate([
            'name' => 'Effective Communication Techniques', 
            'description' => '', 
            'parent_id' => $topic_interview_preparation_strategies->id, 
        ]);

        $topic_body_language_and_nonverbal_communication = Topic::firstOrCreate([
            'name' => 'Body Language and Nonverbal Communication', 
            'description' => '', 
            'parent_id' => $topic_interview_preparation_strategies->id, 
        ]);

        $topic_interview_etiquette_and_professionalism = Topic::firstOrCreate([
            'name' => 'Interview Etiquette and Professionalism', 
            'description' => '', 
            'parent_id' => $topic_interview_preparation_strategies->id, 
        ]);

        $topic_handling_nervousness_and_stress_during_interviews = Topic::firstOrCreate([
            'name' => 'Handling Nervousness and Stress during Interviews', 
            'description' => '', 
            'parent_id' => $topic_interview_preparation_strategies->id, 
        ]);

        $topic_practicing_problem_solving_skills_and_critical_thinking = Topic::firstOrCreate([
            'name' => 'Practicing Problem-Solving Skills and Critical Thinking', 
            'description' => '', 
            'parent_id' => $topic_interview_preparation_strategies->id, 
        ]);

        $topic_breakdown_of_complex_problems_into_manageable_components = Topic::firstOrCreate([
            'name' => 'Breakdown of Complex Problems into Manageable Components', 
            'description' => '', 
            'parent_id' => $topic_problem_solving_approaches->id, 
        ]);

        $topic_algorithmic_thinking_and_problem_solving_strategies = Topic::firstOrCreate([
            'name' => 'Algorithmic Thinking and Problem-Solving Strategies', 
            'description' => '', 
            'parent_id' => $topic_problem_solving_approaches->id, 
        ]);

        $topic_creativity_and_innovation_in_problem_solving = Topic::firstOrCreate([
            'name' => 'Creativity and Innovation in Problem Solving', 
            'description' => '', 
            'parent_id' => $topic_problem_solving_approaches->id, 
        ]);

        $topic_analytical_thinking_and_logical_reasoning = Topic::firstOrCreate([
            'name' => 'Analytical Thinking and Logical Reasoning', 
            'description' => '', 
            'parent_id' => $topic_problem_solving_approaches->id, 
        ]);

        $topic_time_management_and_prioritization_of_tasks = Topic::firstOrCreate([
            'name' => 'Time Management and Prioritization of Tasks', 
            'description' => '', 
            'parent_id' => $topic_problem_solving_approaches->id, 
        ]);

        $topic_teamwork_and_collaboration = Topic::firstOrCreate([
            'name' => 'Teamwork and Collaboration', 
            'description' => '', 
            'parent_id' => $topic_soft_skills_development->id, 
        ]);

        $topic_leadership_skills = Topic::firstOrCreate([
            'name' => 'Leadership Skills', 
            'description' => '', 
            'parent_id' => $topic_soft_skills_development->id, 
        ]);

        $topic_adaptability_and_flexibility = Topic::firstOrCreate([
            'name' => 'Adaptability and Flexibility', 
            'description' => '', 
            'parent_id' => $topic_soft_skills_development->id, 
        ]);

        $topic_conflict_resolution_and_negotiation_skills = Topic::firstOrCreate([
            'name' => 'Conflict Resolution and Negotiation Skills', 
            'description' => '', 
            'parent_id' => $topic_soft_skills_development->id, 
        ]);

        $topic_empathy_and_emotional_intelligence = Topic::firstOrCreate([
            'name' => 'Empathy and Emotional Intelligence', 
            'description' => '', 
            'parent_id' => $topic_soft_skills_development->id, 
        ]);

        $topic_goal_setting_and_career_planning = Topic::firstOrCreate([
            'name' => 'Goal Setting and Career Planning', 
            'description' => '', 
            'parent_id' => $topic_career_development->id, 
        ]);

        $topic_networking_and_building_professional_relationships = Topic::firstOrCreate([
            'name' => 'Networking and Building Professional Relationships', 
            'description' => '', 
            'parent_id' => $topic_career_development->id, 
        ]);

        $topic_resume_writing_and_personal_´branding = Topic::firstOrCreate([
            'name' => 'Resume Writing and Personal Branding', 
            'description' => '', 
            'parent_id' => $topic_career_development->id, 
        ]);

        $topic_job_search_strategies_and_interview_techniques = Topic::firstOrCreate([
            'name' => 'Job Search Strategies and Interview Techniques', 
            'description' => '', 
            'parent_id' => $topic_career_development->id, 
        ]);

        $topic_continuous_learning_and_professional_growth = Topic::firstOrCreate([
            'name' => 'Continuous Learning and Professional Growth', 
            'description' => '', 
            'parent_id' => $topic_career_development->id, 
        ]);

        $topic_understanding_perspective_and_expectations = Topic::firstOrCreate([
            'name' => 'Understanding the Interviewer\'s Perspective and Expectations', 
            'description' => '', 
            'parent_id' => $topic_psychological_aspects->id, 
        ]);

        $topic_building_rapport = Topic::firstOrCreate([
            'name' => 'Building Rapport and Establishing a Connection with Interviewers', 
            'description' => '', 
            'parent_id' => $topic_psychological_aspects->id, 
        ]);

        $topic_overcoming_imposter_syndrome_and_self_doubt = Topic::firstOrCreate([
            'name' => 'Overcoming Imposter Syndrome and Self-Doubt', 
            'description' => '', 
            'parent_id' => $topic_psychological_aspects->id, 
        ]);

        $topic_managing_rejection_and_dealing_with_job_search_challenges = Topic::firstOrCreate([
            'name' => 'Managing Rejection and Dealing with Job Search Challenges', 
            'description' => '', 
            'parent_id' => $topic_psychological_aspects->id, 
        ]);

        $topic_maintaining_confidence_and_resilience_throughout_the_interview_process = Topic::firstOrCreate([
            'name' => 'Maintaining Confidence and Resilience throughout the Interview Process', 
            'description' => '', 
            'parent_id' => $topic_psychological_aspects->id, 
        ]);

        $topic_understanding_bias_and_fairness_in_algorithms = Topic::firstOrCreate([
            'name' => 'Understanding Bias and Fairness in Algorithms', 
            'description' => '', 
            'parent_id' => $topic_ethical_considerations_in_software_development->id, 
        ]);

        $topic_privacy_and_data_protection_in_software_applications = Topic::firstOrCreate([
            'name' => 'Privacy and Data Protection in Software Applications', 
            'description' => '', 
            'parent_id' => $topic_ethical_considerations_in_software_development->id, 
        ]);

        $topic_responsible_software_development_practices = Topic::firstOrCreate([
            'name' => 'Responsible Software Development Practices', 
            'description' => '', 
            'parent_id' => $topic_ethical_considerations_in_software_development->id, 
        ]);

        $topic_ethical_decision_making_in_software_design_and_deployment = Topic::firstOrCreate([
            'name' => 'Ethical Decision-Making in Software Design and Deployment', 
            'description' => '', 
            'parent_id' => $topic_ethical_considerations_in_software_development->id, 
        ]);

        $topic_exploring_the_latest_trends_in_software_development = Topic::firstOrCreate([
            'name' => 'Exploring the Latest Trends in Software Development', 
            'description' => '', 
            'parent_id' => $topic_industry_trends->id, 
        ]);

        $topic_applications_of_technology_in_various_industries = Topic::firstOrCreate([
            'name' => 'Applications of Technology in Various Industries', 
            'description' => '', 
            'parent_id' => $topic_industry_trends->id, 
        ]);

        $topic_emerging_technologies_and_their_impact_on_the_future_of_work = Topic::firstOrCreate([
            'name' => 'Emerging Technologies and Their Impact on the Future of Work', 
            'description' => '', 
            'parent_id' => $topic_industry_trends->id, 
        ]);

        $topic_opportunities_and_challenges_in_the_software_job_market = Topic::firstOrCreate([
            'name' => 'Opportunities and Challenges in the Software Job Market', 
            'description' => '', 
            'parent_id' => $topic_industry_trends->id, 
        ]);

        $topic_starting_a_tech_Startup_from_idea_to_execution = Topic::firstOrCreate([
            'name' => 'Starting a Tech Startup: From Idea to Execution', 
            'description' => '', 
            'parent_id' => $topic_entrepreneurship_and_startup_culture->id, 
        ]);

        $topic_lean_startup_methodology_and_MVP_development = Topic::firstOrCreate([
            'name' => 'Lean Startup Methodology and MVP Development', 
            'description' => '', 
            'parent_id' => $topic_entrepreneurship_and_startup_culture->id, 
        ]);

        $topic_pitching_and_fundraising_for_tech_startups = Topic::firstOrCreate([
            'name' => 'Pitching and Fundraising for Tech Startups', 
            'description' => '', 
            'parent_id' => $topic_entrepreneurship_and_startup_culture->id, 
        ]);

        $topic_building_and_leading_high_performing_startup_teams = Topic::firstOrCreate([
            'name' => 'Building and Leading High-Performing Startup Teams', 
            'description' => '', 
            'parent_id' => $topic_entrepreneurship_and_startup_culture->id, 
        ]);

        $topic_participating_in_developer_communities_and_meetups = Topic::firstOrCreate([
            'name' => 'Participating in Developer Communities and Meetups', 
            'description' => '', 
            'parent_id' => $topic_community_and_networking->id, 
        ]);

        $topic_leveraging_social_media_for_professional_networking = Topic::firstOrCreate([
            'name' => 'Leveraging Social Media for Professional Networking', 
            'description' => '', 
            'parent_id' => $topic_community_and_networking->id, 
        ]);

        $topic_mentorship_and_giving_back_to_the_community = Topic::firstOrCreate([
            'name' => 'Mentorship and Giving Back to the Community', 
            'description' => '', 
            'parent_id' => $topic_community_and_networking->id, 
        ]);

        $topic_building_a_personal_brand_in_the_software_industry = Topic::firstOrCreate([
            'name' => 'Building a Personal Brand in the Software Industry', 
            'description' => '', 
            'parent_id' => $topic_community_and_networking->id, 
        ]);

        $topic_best_practices_for_remote_work_and_collaboration = Topic::firstOrCreate([
            'name' => 'Best Practices for Remote Work and Collaboration', 
            'description' => '', 
            'parent_id' => $topic_remote_work_and_distributed_teams->id, 
        ]);

        $topic_tools_and_technologies_for_remote_team_communication = Topic::firstOrCreate([
            'name' => 'Tools and Technologies for Remote Team Communication', 
            'description' => '', 
            'parent_id' => $topic_remote_work_and_distributed_teams->id, 
        ]);

        $topic_overcoming_challenges_in_distributed_team_environments = Topic::firstOrCreate([
            'name' => 'Overcoming Challenges in Distributed Team Environments', 
            'description' => '', 
            'parent_id' => $topic_remote_work_and_distributed_teams->id, 
        ]);

        $topic_balancing_work_life_integration_in_remote_settings = Topic::firstOrCreate([
            'name' => 'Balancing Work-Life Integration in Remote Settings', 
            'description' => '', 
            'parent_id' => $topic_remote_work_and_distributed_teams->id, 
        ]);

        $topic_introduction_to_ethical_hacking = Topic::firstOrCreate([
            'name' => 'Introduction to Ethical Hacking', 
            'description' => '', 
            'parent_id' => $topic_security->id, 
        ]);

        $topic_networking_and_network_security = Topic::firstOrCreate([
            'name' => 'Networking and Network Security', 
            'description' => '', 
            'parent_id' => $topic_security->id, 
        ]);

        $topic_web_application_security = Topic::firstOrCreate([
            'name' => 'Web Application Security', 
            'description' => '', 
            'parent_id' => $topic_security->id, 
        ]);

        $topic_wireless_security = Topic::firstOrCreate([
            'name' => 'Wireless Security', 
            'description' => '', 
            'parent_id' => $topic_security->id, 
        ]);

        $topic_operating_system_security = Topic::firstOrCreate([
            'name' => 'Operating System Security', 
            'description' => '', 
            'parent_id' => $topic_security->id, 
        ]);

        $topic_social_engineering = Topic::firstOrCreate([
            'name' => 'Social Engineering', 
            'description' => '', 
            'parent_id' => $topic_security->id, 
        ]);

        $topic_cryptographic_concepts = Topic::firstOrCreate([
            'name' => 'Cryptographic Concepts', 
            'description' => '', 
            'parent_id' => $topic_security->id, 
        ]);

        $topic_understanding_the_role_of_ethical_hackers = Topic::firstOrCreate([
            'name' => 'Understanding the Role of Ethical Hackers', 
            'description' => '', 
            'parent_id' => $topic_introduction_to_ethical_hacking->id, 
        ]);

        $topic_ethics_and_legal_considerations_in_ethical_hacking = Topic::firstOrCreate([
            'name' => 'Ethics and Legal Considerations in Ethical Hacking', 
            'description' => '', 
            'parent_id' => $topic_introduction_to_ethical_hacking->id, 
        ]);

        $topic_types_of_security_assessments = Topic::firstOrCreate([
            'name' => 'Types of Security Assessments (e.g., Penetration Testing, Vulnerability Assessment)', 
            'description' => '', 
            'parent_id' => $topic_introduction_to_ethical_hacking->id, 
        ]);

        $topic_TCP_IP_fundamentals = Topic::firstOrCreate([
            'name' => 'TCP/IP Fundamentals', 
            'description' => '', 
            'parent_id' => $topic_networking_and_network_security->id, 
        ]);

        $topic_network_scanning_and_enumeration_techniques = Topic::firstOrCreate([
            'name' => 'Network Scanning and Enumeration Techniques', 
            'description' => '', 
            'parent_id' => $topic_networking_and_network_security->id, 
        ]);

        $topic_exploitation_and_post_exploitation_techniques = Topic::firstOrCreate([
            'name' => 'Exploitation and Post-Exploitation Techniques', 
            'description' => '', 
            'parent_id' => $topic_networking_and_network_security->id, 
        ]);

        $topic_network_defense_mechanisms = Topic::firstOrCreate([
            'name' => 'Network Defense Mechanisms (e.g., Firewalls, Intrusion Detection Systems)', 
            'description' => '', 
            'parent_id' => $topic_networking_and_network_security->id, 
        ]);

        $topic_common_web_application_vulnerabilities  = Topic::firstOrCreate([
            'name' => 'Common Web Application Vulnerabilities (e.g., SQL Injection, Cross-Site Scripting)', 
            'description' => '', 
            'parent_id' => $topic_web_application_security->id, 
        ]);

        $topic_web_application_testing_methodologies  = Topic::firstOrCreate([
            'name' => 'Web Application Testing Methodologies (e.g., OWASP Top 10)', 
            'description' => '', 
            'parent_id' => $topic_web_application_security->id, 
        ]);

        $topic_secure_coding_practices_for_web_developers  = Topic::firstOrCreate([
            'name' => 'Secure Coding Practices for Web Developers', 
            'description' => '', 
            'parent_id' => $topic_web_application_security->id, 
        ]);

        $topic_SSL_TLS_certificates  = Topic::firstOrCreate([
            'name' => 'SSL/TLS Certificates', 
            'description' => '', 
            'parent_id' => $topic_web_application_security->id, 
        ]);

        $topic_wireless_networking_fundamentals  = Topic::firstOrCreate([
            'name' => 'Wireless Networking Fundamentals', 
            'description' => '', 
            'parent_id' => $topic_wireless_security->id, 
        ]);

        $topic_wireless_security_protocols  = Topic::firstOrCreate([
            'name' => 'Wireless Security Protocols (e.g., WEP, WPA, WPA2)', 
            'description' => '', 
            'parent_id' => $topic_wireless_security->id, 
        ]); 

        $topic_wireless_security_auditing_and_penetration_testing  = Topic::firstOrCreate([
            'name' => 'Wireless Security Auditing and Penetration Testing', 
            'description' => '', 
            'parent_id' => $topic_wireless_security->id, 
        ]); 

        $topic_securing_windows_and_linux_systems  = Topic::firstOrCreate([
            'name' => 'Securing Windows and Linux Systems', 
            'description' => '', 
            'parent_id' => $topic_operating_system_security->id, 
        ]); 

        $topic_user_and_group_management  = Topic::firstOrCreate([
            'name' => 'User and Group Management', 
            'description' => '', 
            'parent_id' => $topic_operating_system_security->id, 
        ]); 

        $topic_file_system_permissions_and_access_controls  = Topic::firstOrCreate([
            'name' => 'File System Permissions and Access Controls', 
            'description' => '', 
            'parent_id' => $topic_operating_system_security->id, 
        ]); 
        
        $topic_understanding_social_engineering_attacks  = Topic::firstOrCreate([
            'name' => 'Understanding Social Engineering Attacks', 
            'description' => '', 
            'parent_id' => $topic_social_engineering->id, 
        ]); 

        $topic_phishing_techniques_and_countermeasures  = Topic::firstOrCreate([
            'name' => 'Phishing Techniques and Countermeasures', 
            'description' => '', 
            'parent_id' => $topic_social_engineering->id, 
        ]); 

        $topic_social_engineering_prevention_and_awareness_training  = Topic::firstOrCreate([
            'name' => 'Social Engineering Prevention and Awareness Training', 
            'description' => '', 
            'parent_id' => $topic_social_engineering->id, 
        ]); 

        $topic_cryptography_fundamentals = Topic::firstOrCreate([
            'name' => 'Cryptography Fundamentals (e.g., Encryption, Hashing)', 
            'description' => '', 
            'parent_id' => $topic_cryptographic_concepts->id, 
        ]); 

        $topic_public_key_infrastructure = Topic::firstOrCreate([
            'name' => 'Public Key Infrastructure (PKI)', 
            'description' => '', 
            'parent_id' => $topic_cryptographic_concepts->id, 
        ]); 

        $topic_digital_signatures_and_certificates = Topic::firstOrCreate([
            'name' => 'Digital Signatures and Certificates', 
            'description' => '', 
            'parent_id' => $topic_cryptographic_concepts->id, 
        ]); 

        $topic_introduction_to_SSL_TLS_certificates = Topic::firstOrCreate([
            'name' => 'Introduction to SSL/TLS Certificates', 
            'description' => '', 
            'parent_id' => $topic_SSL_TLS_certificates->id, 
        ]); 

        $topic_types_of_SSL_TLS = Topic::firstOrCreate([
            'name' => 'Types of SSL/TLS Certificates (e.g., Domain Validated, Organization Validated, Extended Validation)', 
            'description' => '', 
            'parent_id' => $topic_SSL_TLS_certificates->id, 
        ]); 

        $topic_certificate_Authorities = Topic::firstOrCreate([
            'name' => 'Certificate Authorities (CAs) and Certificate Chains', 
            'description' => '', 
            'parent_id' => $topic_SSL_TLS_certificates->id, 
        ]); 

        $topic_generating_SSL_TLS_certificates = Topic::firstOrCreate([
            'name' => 'Generating SSL/TLS Certificates', 
            'description' => '', 
            'parent_id' => $topic_SSL_TLS_certificates->id, 
        ]); 

        $topic_SSL_TLS_handshake_process = Topic::firstOrCreate([
            'name' => 'SSL/TLS Handshake Process', 
            'description' => '', 
            'parent_id' => $topic_SSL_TLS_certificates->id, 
        ]); 

        $topic_SSL_TLS_configuration_in_web_servers = Topic::firstOrCreate([
            'name' => 'SSL/TLS Configuration in Web Servers (e.g., Apache, Nginx)', 
            'description' => '', 
            'parent_id' => $topic_SSL_TLS_certificates->id, 
        ]); 

        $topic_certificate_management_and_renewal = Topic::firstOrCreate([
            'name' => 'Certificate Management and Renewal', 
            'description' => '', 
            'parent_id' => $topic_SSL_TLS_certificates->id, 
        ]); 

        $topic_best_practices_for_SSL_TLS_configuration = Topic::firstOrCreate([
            'name' => 'Best Practices for SSL/TLS Configuration', 
            'description' => '', 
            'parent_id' => $topic_SSL_TLS_certificates->id, 
        ]); 

        $topic_troubleshooting_SSL_TLS_certificate_issues = Topic::firstOrCreate([
            'name' => 'Troubleshooting SSL/TLS Certificate Issues', 
            'description' => '', 
            'parent_id' => $topic_SSL_TLS_certificates->id, 
        ]); 

        $topic_introduction_to_linkedin = Topic::firstOrCreate([
            'name' => 'Introduction to LinkedIn', 
            'description' => '', 
            'parent_id' => $topic_linkedin->id, 
        ]); 

        $topic_creating_an_effective_linkedin_profile = Topic::firstOrCreate([
            'name' => 'Creating an Effective LinkedIn Profile', 
            'description' => '', 
            'parent_id' => $topic_linkedin->id, 
        ]); 

        $topic_networking_on_linkedin = Topic::firstOrCreate([
            'name' => 'Networking on LinkedIn', 
            'description' => '', 
            'parent_id' => $topic_linkedin->id, 
        ]); 

        $topic_job_searching_and_career_opportunities = Topic::firstOrCreate([
            'name' => 'Job Searching and Career Opportunities', 
            'description' => '', 
            'parent_id' => $topic_linkedin->id, 
        ]); 

        $topic_personal_branding_and_content_creation = Topic::firstOrCreate([
            'name' => 'Personal Branding and Content Creation', 
            'description' => '', 
            'parent_id' => $topic_linkedin->id, 
        ]); 

        $topic_linkedin_premium_and_additional_features = Topic::firstOrCreate([
            'name' => 'LinkedIn Premium and Additional Features', 
            'description' => '', 
            'parent_id' => $topic_linkedin->id, 
        ]); 

        $topic_overview_of_LinkedIn_Platform_and_Features = Topic::firstOrCreate([
            'name' => 'Overview of LinkedIn Platform and Features', 
            'description' => '', 
            'parent_id' => $topic_introduction_to_linkedin->id, 
        ]); 

        $topic_importance_of_LinkedIn_in_Career_Development = Topic::firstOrCreate([
            'name' => 'Importance of LinkedIn in Career Development', 
            'description' => '', 
            'parent_id' => $topic_introduction_to_linkedin->id, 
        ]); 

        $topic_profile_Setup_and_Optimization = Topic::firstOrCreate([
            'name' => 'Profile Setup and Optimization', 
            'description' => '', 
            'parent_id' => $topic_creating_an_effective_linkedin_profile->id, 
        ]); 

        $topic_writing_a_Compelling_Headline_and_Summary = Topic::firstOrCreate([
            'name' => 'Writing a Compelling Headline and Summary', 
            'description' => '', 
            'parent_id' => $topic_creating_an_effective_linkedin_profile->id, 
        ]); 

        $topic_Highlighting_Skills_Experience_and_Accomplishments = Topic::firstOrCreate([
            'name' => 'Highlighting Skills, Experience, and Accomplishments', 
            'description' => '', 
            'parent_id' => $topic_creating_an_effective_linkedin_profile->id, 
        ]); 

        $topic_adding_Education_Certifications_and_Awards = Topic::firstOrCreate([
            'name' => 'Adding Education, Certifications, and Awards', 
            'description' => '', 
            'parent_id' => $topic_creating_an_effective_linkedin_profile->id, 
        ]); 

        $topic_Uploading_a_Professional_Profile_Picture_and_Background_Photo = Topic::firstOrCreate([
            'name' => 'Uploading a Professional Profile Picture and Background Photo', 
            'description' => '', 
            'parent_id' => $topic_creating_an_effective_linkedin_profile->id, 
        ]); 

        $topic_Connecting_with_Peers_Colleagues_and_Industry_Professionals = Topic::firstOrCreate([
            'name' => 'Connecting with Peers, Colleagues, and Industry Professionals', 
            'description' => '', 
            'parent_id' => $topic_networking_on_linkedin->id, 
        ]); 

        $topic_Building_and_Expanding_Your_Professional_Network = Topic::firstOrCreate([
            'name' => 'Building and Expanding Your Professional Network', 
            'description' => '', 
            'parent_id' => $topic_networking_on_linkedin->id, 
        ]); 

        $topic_Engaging_with_Connections_through_Likes_Comments_and_Shares = Topic::firstOrCreate([
            'name' => 'Engaging with Connections through Likes, Comments, and Shares', 
            'description' => '', 
            'parent_id' => $topic_networking_on_linkedin->id, 
        ]); 

        $topic_Participating_in_LinkedIn_Groups_and_Discussions = Topic::firstOrCreate([
            'name' => 'Participating in LinkedIn Groups and Discussions', 
            'description' => '', 
            'parent_id' => $topic_networking_on_linkedin->id, 
        ]); 

        $topic_Searching_for_Jobs_and_Internships_on_LinkedIn = Topic::firstOrCreate([
            'name' => 'Searching for Jobs and Internships on LinkedIn', 
            'description' => '', 
            'parent_id' => $topic_job_searching_and_career_opportunities->id, 
        ]); 

        $topic_Applying_for_Positions_and_Utilizing_the_Job_Search_Features = Topic::firstOrCreate([
            'name' => 'Applying for Positions and Utilizing the Job Search Features', 
            'description' => '', 
            'parent_id' => $topic_job_searching_and_career_opportunities->id, 
        ]); 

        $topic_Leveraging_LinkedIns_Job_Alerts_and_Recommendations = Topic::firstOrCreate([
            'name' => 'Leveraging LinkedIn\'s Job Alerts and Recommendations', 
            'description' => '', 
            'parent_id' => $topic_job_searching_and_career_opportunities->id, 
        ]); 

        $topic_Researching_Companies_and_Employers = Topic::firstOrCreate([
            'name' => 'Researching Companies and Employers', 
            'description' => '', 
            'parent_id' => $topic_job_searching_and_career_opportunities->id, 
        ]); 

        $topic_Establishing_Your_Personal_Brand_on_LinkedIn = Topic::firstOrCreate([
            'name' => 'Establishing Your Personal Brand on LinkedIn', 
            'description' => '', 
            'parent_id' => $topic_personal_branding_and_content_creation->id, 
        ]); 

        $topic_Creating_and_Sharing_Engaging_Content = Topic::firstOrCreate([
            'name' => 'Creating and Sharing Engaging Content (Posts, Articles, Videos)', 
            'description' => '', 
            'parent_id' => $topic_personal_branding_and_content_creation->id, 
        ]); 

        $topic_Showcasing_Projects_Portfolio_Pieces_and_Work_Samples = Topic::firstOrCreate([
            'name' => 'Showcasing Projects, Portfolio Pieces, and Work Samples', 
            'description' => '', 
            'parent_id' => $topic_personal_branding_and_content_creation->id, 
        ]); 
        
        $topic_Utilizing_LinkedIns_Publishing_Platform_for_Thought_Leadership = Topic::firstOrCreate([
            'name' => 'Utilizing LinkedIn\'s Publishing Platform for Thought Leadership', 
            'description' => '', 
            'parent_id' => $topic_personal_branding_and_content_creation->id, 
        ]); 

        $topic_Overview_of_LinkedIn_Premium_Subscription_Options = Topic::firstOrCreate([
            'name' => 'Overview of LinkedIn Premium Subscription Options', 
            'description' => '', 
            'parent_id' => $topic_linkedin_premium_and_additional_features->id, 
        ]); 

        $topic_Accessing_Premium_Features_linkedin = Topic::firstOrCreate([
            'name' => 'Accessing Premium Features (e.g., InMail, Advanced Search, Profile Insights)', 
            'description' => '', 
            'parent_id' => $topic_linkedin_premium_and_additional_features->id, 
        ]); 

        $topic_Leveraging_LinkedIn_Learning_for_Skill_Development_and_Training = Topic::firstOrCreate([
            'name' => 'Leveraging LinkedIn Learning for Skill Development and Training', 
            'description' => '', 
            'parent_id' => $topic_linkedin_premium_and_additional_features->id, 
        ]); 

        $topic_Introduction_to_MVP = Topic::firstOrCreate([
            'name' => 'Introduction to MVP', 
            'description' => '', 
            'parent_id' => $topic_MVP->id, 
        ]); 

        $topic_Identifying_Customer_Needs_and_Pain_Points = Topic::firstOrCreate([
            'name' => 'Identifying Customer Needs and Pain Points', 
            'description' => '', 
            'parent_id' => $topic_MVP->id, 
        ]); 

        $topic_Defining_MVP_Features_and_Scope = Topic::firstOrCreate([
            'name' => 'Defining MVP Features and Scope', 
            'description' => '', 
            'parent_id' => $topic_MVP->id, 
        ]); 

        $topic_Building_and_Launching_MVP = Topic::firstOrCreate([
            'name' => 'Building and Launching MVP', 
            'description' => '', 
            'parent_id' => $topic_MVP->id, 
        ]); 

        $topic_Gathering_Feedback_and_Iterating = Topic::firstOrCreate([
            'name' => 'Gathering Feedback and Iterating', 
            'description' => '', 
            'parent_id' => $topic_MVP->id, 
        ]); 

        $topic_Scaling_and_Growth_Strategies = Topic::firstOrCreate([
            'name' => 'Scaling and Growth Strategies', 
            'description' => '', 
            'parent_id' => $topic_MVP->id, 
        ]); 

        $topic_Definition_and_Concept_of_Minimum_Viable_Product = Topic::firstOrCreate([
            'name' => 'Definition and Concept of Minimum Viable Product', 
            'description' => '', 
            'parent_id' => $topic_Introduction_to_MVP->id, 
        ]); 

        $topic_Benefits_of_MVP_Approach_in_Product_Development = Topic::firstOrCreate([
            'name' => 'Benefits of MVP Approach in Product Development', 
            'description' => '', 
            'parent_id' => $topic_Introduction_to_MVP->id, 
        ]); 

        $topic_Key_Principles_of_MVP_Development = Topic::firstOrCreate([
            'name' => 'Key Principles of MVP Development (e.g., Build-Measure-Learn Loop)', 
            'description' => '', 
            'parent_id' => $topic_Introduction_to_MVP->id, 
        ]); 

        $topic_Market_Research_and_Customer_Discovery = Topic::firstOrCreate([
            'name' => 'Market Research and Customer Discovery', 
            'description' => '', 
            'parent_id' => $topic_Identifying_Customer_Needs_and_Pain_Points->id, 
        ]); 

        $topic_Defining_User_Personas_and_Target_Audience = Topic::firstOrCreate([
            'name' => 'Defining User Personas and Target Audience', 
            'description' => '', 
            'parent_id' => $topic_Identifying_Customer_Needs_and_Pain_Points->id, 
        ]); 

        $topic_Conducting_User_Interviews_and_Surveys = Topic::firstOrCreate([
            'name' => 'Conducting User Interviews and Surveys', 
            'description' => '', 
            'parent_id' => $topic_Identifying_Customer_Needs_and_Pain_Points->id, 
        ]); 

        $topic_Identifying_Core_Problems_to_Solve_with_MVP = Topic::firstOrCreate([
            'name' => 'Identifying Core Problems to Solve with MVP', 
            'description' => '', 
            'parent_id' => $topic_Identifying_Customer_Needs_and_Pain_Points->id, 
        ]); 

        $topic_Prioritizing_Features_based_on_User_Needs_and_Business_Goals = Topic::firstOrCreate([
            'name' => 'Prioritizing Features based on User Needs and Business Goals', 
            'description' => '', 
            'parent_id' => $topic_Defining_MVP_Features_and_Scope->id, 
        ]); 

        $topic_Creating_User_Stories_and_Feature_Requirements = Topic::firstOrCreate([
            'name' => 'Creating User Stories and Feature Requirements', 
            'description' => '', 
            'parent_id' => $topic_Defining_MVP_Features_and_Scope->id, 
        ]); 

        $topic_Determining_Minimum_Feature_Set_for_MVP_Launch = Topic::firstOrCreate([
            'name' => 'Determining Minimum Feature Set for MVP Launch', 
            'description' => '', 
            'parent_id' => $topic_Defining_MVP_Features_and_Scope->id, 
        ]); 

        $topic_Setting_Success_Metrics_and_Key_Performance_Indicators = Topic::firstOrCreate([
            'name' => 'Setting Success Metrics and Key Performance Indicators (KPIs)', 
            'description' => '', 
            'parent_id' => $topic_Defining_MVP_Features_and_Scope->id, 
        ]); 

        $topic_Choosing_the_Right_Development_Approach = Topic::firstOrCreate([
            'name' => 'Choosing the Right Development Approach (e.g., Prototyping, Lean Development)', 
            'description' => '', 
            'parent_id' => $topic_Building_and_Launching_MVP->id, 
        ]); 

        $topic_Iterative_Development_and_Rapid_Prototyping = Topic::firstOrCreate([
            'name' => 'Iterative Development and Rapid Prototyping', 
            'description' => '', 
            'parent_id' => $topic_Building_and_Launching_MVP->id, 
        ]); 

        $topic_MVP_Design_and_Development_Considerations = Topic::firstOrCreate([
            'name' => 'MVP Design and Development Considerations', 
            'description' => '', 
            'parent_id' => $topic_Building_and_Launching_MVP->id, 
        ]); 

        $topic_MVP_Testing_and_Validation_with_Early_Adopters = Topic::firstOrCreate([
            'name' => 'MVP Testing and Validation with Early Adopters', 
            'description' => '', 
            'parent_id' => $topic_Building_and_Launching_MVP->id, 
        ]); 

        $topic_Collecting_User_Feedback_and_Metrics = Topic::firstOrCreate([
            'name' => 'Collecting User Feedback and Metrics', 
            'description' => '', 
            'parent_id' => $topic_Gathering_Feedback_and_Iterating->id, 
        ]); 

        $topic_Analyzing_User_Behavior_and_Usage_Patterns = Topic::firstOrCreate([
            'name' => 'Analyzing User Behavior and Usage Patterns', 
            'description' => '', 
            'parent_id' => $topic_Gathering_Feedback_and_Iterating->id, 
        ]); 

        $topic_Iterating_on_MVP_Features_based_on_User_Feedback = Topic::firstOrCreate([
            'name' => 'Iterating on MVP Features based on User Feedback', 
            'description' => '', 
            'parent_id' => $topic_Gathering_Feedback_and_Iterating->id, 
        ]); 

        $topic_Continuous_Improvement_and_Product_Iteration = Topic::firstOrCreate([
            'name' => 'Continuous Improvement and Product Iteration', 
            'description' => '', 
            'parent_id' => $topic_Gathering_Feedback_and_Iterating->id, 
        ]); 

        $topic_Scaling_MVP_into_a_Full_fledged_Product = Topic::firstOrCreate([
            'name' => 'Scaling MVP into a Full-fledged Product', 
            'description' => '', 
            'parent_id' => $topic_Scaling_and_Growth_Strategies->id, 
        ]); 

        $topic_Identifying_Growth_Opportunities_and_Expansion_Strategies = Topic::firstOrCreate([
            'name' => 'Identifying Growth Opportunities and Expansion Strategies', 
            'description' => '', 
            'parent_id' => $topic_Scaling_and_Growth_Strategies->id, 
        ]); 

        $topic_Customer_Acquisition_and_Retention_Tactics = Topic::firstOrCreate([
            'name' => 'Customer Acquisition and Retention Tactics', 
            'description' => '', 
            'parent_id' => $topic_Scaling_and_Growth_Strategies->id, 
        ]); 

        $topic_Building_Sustainable_Business_Models = Topic::firstOrCreate([
            'name' => 'Building Sustainable Business Models', 
            'description' => '', 
            'parent_id' => $topic_Scaling_and_Growth_Strategies->id, 
        ]); 
    }
}
