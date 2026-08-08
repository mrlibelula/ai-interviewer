<?php

namespace Tests\Unit;

use App\Tool;
use PHPUnit\Framework\TestCase;

class CodeAnalysisResponseTest extends TestCase
{
  public function test_sanitize_strips_legacy_separator_instructions(): void
  {
    $legacy = 'Analyze this user code. Immediatly after your answer, put a "%%%%%" (5 percentage symbols) separator (without spaces) followed by a "true" or "false" string. If the user has solved the challenge return "true", otherwise return "false". Refer by name.';
    $result = Tool::sanitizeAnalyzeUserCodePrompt($legacy);

    $this->assertStringNotContainsString('%%%%%', $result);
    $this->assertStringNotContainsString('Immediatly after your answer', $result);
    $this->assertStringContainsString('structured JSON schema', $result);
    $this->assertStringContainsString('solved', $result);
  }

  public function test_parse_structured_json_strips_leaked_separator_in_feedback(): void
  {
    $raw = json_encode([
      'feedback' => 'Overall: your code correctly solves the stated problem. Good work!%%%%%true',
      'solved' => true,
    ]);

    $parsed = Tool::parseCodeAnalysisResponse($raw);

    $this->assertSame('Overall: your code correctly solves the stated problem. Good work!', $parsed['feedback']);
    $this->assertTrue($parsed['solved']);
    $this->assertStringNotContainsString('%%%%%', $parsed['feedback']);
  }

  public function test_parse_legacy_separator_plaintext(): void
  {
    $raw = "Nice approach, almost there.%%%%%false";

    $parsed = Tool::parseCodeAnalysisResponse($raw);

    $this->assertSame('Nice approach, almost there.', $parsed['feedback']);
    $this->assertFalse($parsed['solved']);
  }

  public function test_parse_clean_structured_json(): void
  {
    $raw = json_encode([
      'feedback' => 'Solid solution. Consider edge cases with duplicates.',
      'solved' => true,
    ]);

    $parsed = Tool::parseCodeAnalysisResponse($raw);

    $this->assertSame('Solid solution. Consider edge cases with duplicates.', $parsed['feedback']);
    $this->assertTrue($parsed['solved']);
  }
}
