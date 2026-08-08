<?php

namespace Tests\Unit;

use App\Tool;
use PHPUnit\Framework\TestCase;

class TimeLimitNormalizeTest extends TestCase
{
  public function test_keeps_valid_hms(): void
  {
    $this->assertSame('00:30:00', Tool::normalizeTimeLimit('00:30:00'));
    $this->assertSame('01:05:09', Tool::normalizeTimeLimit('1:5:9'));
  }

  public function test_parses_human_phrases(): void
  {
    $this->assertSame('00:30:00', Tool::normalizeTimeLimit('30 minutes'));
    $this->assertSame('00:45:00', Tool::normalizeTimeLimit('45m'));
    $this->assertSame('01:00:00', Tool::normalizeTimeLimit('1 hour'));
    $this->assertSame('01:30:00', Tool::normalizeTimeLimit('1 hour 30 minutes'));
    $this->assertSame('00:20:00', Tool::normalizeTimeLimit('20'));
  }

  public function test_falls_back_on_garbage(): void
  {
    $this->assertSame('00:30:00', Tool::normalizeTimeLimit('soon'));
    $this->assertSame('00:15:00', Tool::normalizeTimeLimit('', '00:15:00'));
  }

  public function test_time_limit_parts_are_ints(): void
  {
    $parts = Tool::timeLimitParts('30 minutes');

    $this->assertSame(0, $parts['hours']);
    $this->assertSame(30, $parts['minutes']);
    $this->assertSame(0, $parts['seconds']);
    $this->assertIsInt($parts['hours']);
    $this->assertIsInt($parts['minutes']);
    $this->assertIsInt($parts['seconds']);
  }
}
