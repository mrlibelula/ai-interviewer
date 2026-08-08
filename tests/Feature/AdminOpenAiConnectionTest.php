<?php

namespace Tests\Feature;

use App\Livewire\Admin\Challenges;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use OpenAI\Exceptions\ErrorException;
use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Models\ListResponse;
use Tests\TestCase;

class AdminOpenAiConnectionTest extends TestCase
{
  use RefreshDatabase;

  public function test_openai_connection_test_marks_established_on_success(): void
  {
    OpenAI::fake([
      ListResponse::fake(),
    ]);

    $this->actingAs(User::factory()->create());

    Livewire::test(Challenges::class)
      ->assertSet('openaiConnectionStatus', 'untested')
      ->call('testOpenAiConnection')
      ->assertSet('openaiConnectionStatus', 'established')
      ->assertSet('openaiConnectionError', null);

    $this->assertTrue(session('openai_status'));
  }

  public function test_openai_connection_test_marks_failed_on_api_error(): void
  {
    OpenAI::fake([
      new ErrorException([
        'message' => 'Incorrect API key provided',
        'type' => 'invalid_request_error',
        'code' => 'invalid_api_key',
      ]),
    ]);

    $this->actingAs(User::factory()->create());

    Livewire::test(Challenges::class)
      ->call('testOpenAiConnection')
      ->assertSet('openaiConnectionStatus', 'failed')
      ->assertSet('openaiConnectionError', 'Incorrect API key provided');

    $this->assertFalse(session('openai_status'));
  }
}
