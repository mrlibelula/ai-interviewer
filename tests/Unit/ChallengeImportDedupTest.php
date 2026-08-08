<?php

namespace Tests\Unit;

use App\Models\Challenge;
use App\Models\Difficulty;
use App\Models\Status;
use App\Models\Topic;
use App\Models\User;
use App\Models\Visibility;
use App\Tool;
use Illuminate\Foundation\Testing\RefreshDatabase;
use stdClass;
use Tests\TestCase;

class ChallengeImportDedupTest extends TestCase
{
  use RefreshDatabase;

  private int $difficultyId;

  private int $statusId;

  private int $visibilityId;

  protected function setUp(): void
  {
    parent::setUp();

    $this->difficultyId = Difficulty::create(['name' => 'medium'])->id;
    $this->statusId = Status::create(['name' => 'active'])->id;
    $this->visibilityId = Visibility::create(['name' => 'public'])->id;
  }

  public function test_challenge_titles_for_generation_are_global_across_topics(): void
  {
    $algorithms = Topic::create(['name' => 'Algorithms']);
    $dataStructures = Topic::create(['name' => 'Data Structures']);

    $parens = $this->makeChallenge(['title' => 'Valid Parentheses']);
    $parens->addTopic($algorithms);

    $stack = $this->makeChallenge(['title' => 'Min Stack']);
    $stack->addTopic($dataStructures);

    $topicOnly = Tool::challengeTitlesByTopic('Data Structures');
    $this->assertSame(['Min Stack'], $topicOnly);

    $global = Tool::challengeTitlesByTopic('all topics');
    $this->assertEqualsCanonicalizing(['Valid Parentheses', 'Min Stack'], $global);
  }

  public function test_wildcards_dbchallenges_includes_titles_from_other_topics(): void
  {
    $algorithms = Topic::create(['name' => 'Algorithms']);
    $parens = $this->makeChallenge(['title' => 'Valid Parentheses']);
    $parens->addTopic($algorithms);

    $prompt = Tool::wildcards(
      'Occupied: " ??dbchallenges ". Topic: " ??topics ".',
      'medium',
      'Data Structures',
      'any'
    );

    $this->assertStringContainsString('Valid Parentheses', $prompt);
  }

  public function test_find_existing_matches_title_case_insensitively_across_topics(): void
  {
    $algorithms = Topic::create(['name' => 'Algorithms']);
    $existing = $this->makeChallenge(['title' => 'Valid Parentheses']);
    $existing->addTopic($algorithms);

    $found = Tool::findExistingChallengeByTitleOrSlug('valid parentheses');

    $this->assertNotNull($found);
    $this->assertSame($existing->id, $found->id);
  }

  public function test_import_skips_duplicate_title_and_attaches_missing_topic(): void
  {
    $this->actingAs(User::factory()->create());

    $algorithms = Topic::create(['name' => 'Algorithms']);
    $dataStructures = Topic::create(['name' => 'Data Structures']);
    Difficulty::create(['name' => 'easy']);

    $existing = $this->makeChallenge(['title' => 'Valid Parentheses']);
    $existing->addTopic($algorithms);

    $result = Tool::importAIChallenge($this->makeLlmChallengePayload(
      title: 'Valid Parentheses',
      topics: ['Data Structures'],
      difficulty: 'medium'
    ));

    $this->assertTrue($result->skipped_duplicate);
    $this->assertNull($result->challenge);
    $this->assertSame(1, Challenge::where('title', 'Valid Parentheses')->count());

    $existing->refresh();
    $this->assertTrue($existing->topics->contains('id', $dataStructures->id));
    $this->assertTrue($existing->topics->contains('id', $algorithms->id));
  }

  public function test_import_creates_when_title_is_new(): void
  {
    $this->actingAs(User::factory()->create());

    Topic::create(['name' => 'Algorithms']);
    Difficulty::create(['name' => 'easy']);

    $result = Tool::importAIChallenge($this->makeLlmChallengePayload(
      title: 'Two Sum',
      topics: ['Algorithms'],
      difficulty: 'medium'
    ));

    $this->assertFalse($result->skipped_duplicate);
    $this->assertNotNull($result->challenge);
    $this->assertSame('Two Sum', $result->challenge->title);
    $this->assertSame(1, Challenge::where('title', 'Two Sum')->count());
  }

  /**
   * @param  array<string, mixed>  $overrides
   */
  private function makeChallenge(array $overrides = []): Challenge
  {
    $title = $overrides['title'] ?? 'Sample Challenge';

    return Challenge::create(array_merge([
      'title' => $title,
      'description' => '',
      'challenge_slug' => str($title)->slug()->toString(),
      'difficulty_id' => $this->difficultyId,
      'test_cases' => json_encode([]),
      'hints' => '',
      'time_limit' => '00:30:00',
      'status_id' => $this->statusId,
      'visibility_id' => $this->visibilityId,
      'options' => json_encode(new stdClass()),
      'solution_code' => '',
    ], $overrides));
  }

  /**
   * @param  array<int, string>  $topics
   */
  private function makeLlmChallengePayload(string $title, array $topics, string $difficulty): stdClass
  {
    $challenge = new stdClass;
    $challenge->title = $title;
    $challenge->challenge = 'Describe the problem.';
    $challenge->difficulty_level = $difficulty;
    $challenge->test_cases = ['example' => 'ok'];
    $challenge->hints = 'think carefully';
    $challenge->time_limit = '00:30:00';
    $challenge->topics = $topics;
    $challenge->languages = [];
    $challenge->packages = [];
    $challenge->tags = [];
    $challenge->frameworks = [];
    $challenge->solution_code = "function solve() {\n  return true;\n}";

    $completion = new stdClass;
    $completion->id = 'cmpl_test';
    $completion->model = 'gpt-test';

    $payload = new stdClass;
    $payload->challenge = $challenge;
    $payload->completion = $completion;
    $payload->prompt = 'test prompt';
    $payload->completion_text = '{}';
    $payload->emulated_challenge_model = $challenge;

    return $payload;
  }
}
