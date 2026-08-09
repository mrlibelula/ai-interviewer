<?php

namespace Tests\Feature;

use App\Livewire\Interview;
use App\Livewire\TopHeader;
use App\Models\Challenge;
use App\Models\Difficulty;
use App\Models\Status;
use App\Models\Topic;
use App\Models\User;
use App\Models\Visibility;
use App\Tool;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class InterviewBackNavigationTest extends TestCase
{
  use RefreshDatabase;

  private int $difficultyId;

  private int $statusId;

  private int $visibilityId;

  protected function setUp(): void
  {
    parent::setUp();

    $this->difficultyId = Difficulty::create(['name' => 'easy'])->id;
    Difficulty::create(['name' => 'medium']);
    $this->statusId = Status::create(['name' => 'active'])->id;
    $this->visibilityId = Visibility::create(['name' => 'public'])->id;
  }

  public function test_interview_restores_topic_and_challenge_cards_from_session(): void
  {
    $user = User::factory()->create();
    $topic = Topic::create(['name' => 'Algorithms']);
    $challenge = $this->makeChallenge(['title' => 'Two Sum']);
    $challenge->addTopic($topic);

    $this->actingAs($user);

    session([
      Interview::SESSION_DIFFICULTY_KEY => 'easy',
      Interview::SESSION_TOPIC_KEY => $topic->id,
    ]);

    $component = Livewire::test(Interview::class);

    $component
      ->assertSet('selected_difficulty', 'easy')
      ->assertSet('selected_topic_id', $topic->id);

    $selected = $component->get('selected_challenges');
    $this->assertNotNull($selected);
    $this->assertCount(1, $selected);
    $this->assertSame((int) $challenge->id, (int) $selected->first()->id);
  }

  public function test_topic_header_back_url_points_to_interview(): void
  {
    $encDifficulty = Tool::encode('medium');
    $encTopicId = Tool::encode('42');
    $uri = '/interview/start/'.$encDifficulty.'/'.$encTopicId;

    $request = \Illuminate\Http\Request::create($uri, 'GET');
    $route = app('router')->getRoutes()->match($request);
    $request->setRouteResolver(static fn () => $route);
    app()->instance('request', $request);

    $this->assertSame(route('interview'), app(TopHeader::class)->interviewBackUrl());
  }

  public function test_selecting_topic_persists_filters_in_session(): void
  {
    $user = User::factory()->create();
    $topic = Topic::create(['name' => 'Algorithms']);
    $challenge = $this->makeChallenge(['title' => 'Two Sum']);
    $challenge->addTopic($topic);

    $this->actingAs($user);

    $component = Livewire::test(Interview::class)
      ->set('selected_topic_id', $topic->id);

    $selected = $component->get('selected_challenges');
    $this->assertNotNull($selected);
    $this->assertCount(1, $selected);
    $this->assertSame((int) $challenge->id, (int) $selected->first()->id);

    $this->assertSame('easy', session(Interview::SESSION_DIFFICULTY_KEY));
    $this->assertSame($topic->id, session(Interview::SESSION_TOPIC_KEY));
  }

  public function test_changing_difficulty_updates_session_and_clears_topic(): void
  {
    $user = User::factory()->create();
    $topic = Topic::create(['name' => 'Algorithms']);
    $challenge = $this->makeChallenge(['title' => 'Two Sum']);
    $challenge->addTopic($topic);

    $this->actingAs($user);

    Livewire::test(Interview::class)
      ->set('selected_topic_id', $topic->id)
      ->set('selected_difficulty', 'medium')
      ->assertSet('selected_topic_id', -1)
      ->assertSet('selected_challenges', null);

    $this->assertSame('medium', session(Interview::SESSION_DIFFICULTY_KEY));
    $this->assertSame(-1, session(Interview::SESSION_TOPIC_KEY));
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
      'options' => json_encode(new \stdClass()),
      'solution_code' => '',
    ], $overrides));
  }
}
