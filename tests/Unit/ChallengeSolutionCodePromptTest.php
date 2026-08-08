<?php

namespace Tests\Unit;

use App\Tool;
use PHPUnit\Framework\TestCase;

class ChallengeSolutionCodePromptTest extends TestCase
{
  public function test_sanitize_strips_legacy_no_newline_ban_and_requires_multiline(): void
  {
    $legacy = 'Create a challenge. None of the JSON values must contain line breaks "\n" neither the solution code. Keep going.';
    $result = Tool::sanitizeChallengeGenerationPrompt($legacy);

    $this->assertStringNotContainsString('must contain line breaks', $result);
    $this->assertStringContainsString('Never minify or collapse solution_code', $result);
  }

  public function test_normalize_expands_escaped_newlines_when_source_is_flat(): void
  {
    $code = "function a() {\\n  return 1;\\n}";
    $result = Tool::normalizeSolutionCode($code);

    $this->assertSame("function a() {\n  return 1;\n}", $result);
  }

  public function test_normalize_preserves_real_newlines(): void
  {
    $code = "function a() {\n  return 1;\n}";
    $this->assertSame($code, Tool::normalizeSolutionCode($code));
  }

  public function test_normalize_expands_minified_javascript(): void
  {
    $code = 'function twoSum(nums,target){const seen=new Map();for(let i=0;i<nums.length;i++){const c=target-nums[i];if(seen.has(c))return [seen.get(c),i];seen.set(nums[i],i);}return [];}';
    $result = Tool::normalizeSolutionCode($code);

    $this->assertGreaterThan(3, substr_count($result, "\n"));
    $this->assertStringContainsString("function twoSum", $result);
    $this->assertStringContainsString("\n  ", $result);
  }

  public function test_normalize_splits_leading_line_comment_from_minified_body(): void
  {
    $code = '// single-line demo. Example: console.log(foo()); function foo(){if(true){return 1;}return 0;}';
    $result = Tool::normalizeSolutionCode($code);
    $firstLine = explode("\n", $result)[0];

    $this->assertGreaterThan(1, substr_count($result, "\n"));
    $this->assertStringStartsWith('//', $result);
    $this->assertStringContainsString("function foo()", $result);
    $this->assertStringNotContainsString('function foo()', $firstLine);
  }
}
