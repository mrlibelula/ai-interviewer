<?php

namespace Tests\Unit;

use App\Models\Challenge;
use App\Models\Difficulty;
use App\Models\Status;
use App\Models\Visibility;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChallengeSearchTest extends TestCase
{
  use RefreshDatabase;

  private int $difficultyId;

  private int $statusId;

  private int $visibilityId;

  protected function setUp(): void
  {
    parent::setUp();

    $this->difficultyId = Difficulty::create(['name' => 'easy'])->id;
    $this->statusId = Status::create(['name' => 'active'])->id;
    $this->visibilityId = Visibility::create(['name' => 'public'])->id;
  }

  public function test_empty_term_returns_all_challenges(): void
  {
    $this->makeChallenge(['title' => 'Alpha']);
    $this->makeChallenge(['title' => 'Beta']);

    $this->assertCount(2, Challenge::search('')->get());
    $this->assertCount(2, Challenge::search('   ')->get());
    $this->assertCount(2, Challenge::search(null)->get());
  }

  public function test_matches_title(): void
  {
    $match = $this->makeChallenge(['title' => 'Find Missing Number']);
    $this->makeChallenge(['title' => 'Rotate Array']);

    $results = Challenge::search('Missing')->get();

    $this->assertCount(1, $results);
    $this->assertTrue($results->contains('id', $match->id));
  }

  public function test_matches_description(): void
  {
    $match = $this->makeChallenge([
      'title' => 'Challenge A',
      'description' => 'Return the missing integer from 0..n',
    ]);
    $this->makeChallenge([
      'title' => 'Challenge B',
      'description' => 'Reverse a linked list in place',
    ]);

    $results = Challenge::search('missing integer')->get();

    $this->assertCount(1, $results);
    $this->assertTrue($results->contains('id', $match->id));
  }

  public function test_matches_hints(): void
  {
    $match = $this->makeChallenge([
      'title' => 'Challenge A',
      'hints' => 'Use XOR for O(1) space',
    ]);
    $this->makeChallenge([
      'title' => 'Challenge B',
      'hints' => 'Two pointers from both ends',
    ]);

    $results = Challenge::search('XOR')->get();

    $this->assertCount(1, $results);
    $this->assertTrue($results->contains('id', $match->id));
  }

  public function test_matches_test_cases_substring(): void
  {
    $match = $this->makeChallenge([
      'title' => 'Challenge A',
      'test_cases' => json_encode(['assert.equal(missing([0,1,3]), 2)']),
    ]);
    $this->makeChallenge([
      'title' => 'Challenge B',
      'test_cases' => json_encode(['assert.equal(sum([1,2]), 3)']),
    ]);

    $results = Challenge::search('missing([0,1,3])')->get();

    $this->assertCount(1, $results);
    $this->assertTrue($results->contains('id', $match->id));
  }

  public function test_matches_solution_code(): void
  {
    $match = $this->makeChallenge([
      'title' => 'Challenge A',
      'solution_code' => "function findMissing(nums) {\n  return nums.reduce((a, b) => a ^ b, 0);\n}",
    ]);
    $this->makeChallenge([
      'title' => 'Challenge B',
      'solution_code' => "function rotate(arr, k) {\n  return arr;\n}",
    ]);

    $results = Challenge::search('findMissing')->get();

    $this->assertCount(1, $results);
    $this->assertTrue($results->contains('id', $match->id));
  }

  public function test_does_not_match_unrelated_rows(): void
  {
    $this->makeChallenge([
      'title' => 'Queue via Two Stacks',
      'description' => 'Implement a queue',
      'hints' => 'Push and pop',
      'test_cases' => json_encode(['enqueue then dequeue']),
      'solution_code' => 'class Queue {}',
    ]);

    $this->assertCount(0, Challenge::search('binary search tree')->get());
  }

  public function test_excludes_soft_deleted_challenges(): void
  {
    $alive = $this->makeChallenge(['title' => 'Alive Missing Number']);
    $deleted = $this->makeChallenge(['title' => 'Deleted Missing Number']);
    $deleted->delete();

    $results = Challenge::search('Missing')->get();

    $this->assertCount(1, $results);
    $this->assertTrue($results->contains('id', $alive->id));
    $this->assertFalse($results->contains('id', $deleted->id));
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
