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

  public function test_sanitize_appends_vanilla_js_no_exports_rule(): void
  {
    $prompt = 'Create a coding challenge with solution_code.';
    $result = Tool::sanitizeChallengeGenerationPrompt($prompt);

    $this->assertStringContainsString('no module.exports', $result);
    $this->assertStringContainsString('console.log test', $result);
  }

  public function test_sanitize_does_not_duplicate_vanilla_js_rule(): void
  {
    $prompt = 'Create a challenge. For JavaScript, solution_code MUST be a complete vanilla script with no module.exports, export, require, or import. End with pure console.log test cases.';
    $result = Tool::sanitizeChallengeGenerationPrompt($prompt);

    $this->assertSame(1, substr_count(strtolower($result), 'no module.exports'));
  }

  public function test_normalize_expands_escaped_newlines_when_source_is_flat(): void
  {
    $code = "function a() {\\n  return 1;\\n}";
    $result = Tool::normalizeSolutionCode($code);

    $this->assertSame("function a() {\n\treturn 1;\n}", $result);
  }

  public function test_normalize_preserves_real_newlines(): void
  {
    $code = "function a() {\n  return 1;\n}";
    $this->assertSame("function a() {\n\treturn 1;\n}", Tool::normalizeSolutionCode($code));
  }

  public function test_normalize_expands_minified_javascript(): void
  {
    $code = 'function twoSum(nums,target){const seen=new Map();for(let i=0;i<nums.length;i++){const c=target-nums[i];if(seen.has(c))return [seen.get(c),i];seen.set(nums[i],i);}return [];}';
    $result = Tool::normalizeSolutionCode($code);

    $this->assertGreaterThan(3, substr_count($result, "\n"));
    $this->assertStringContainsString("function twoSum", $result);
    $this->assertStringContainsString("\n\t", $result);
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

  public function test_normalize_strips_module_exports_line(): void
  {
    $code = "class MinStack {\n  push(val) {}\n}\n\nmodule.exports = MinStack;";
    $result = Tool::normalizeSolutionCode($code);

    $this->assertStringNotContainsString('module.exports', $result);
    $this->assertStringContainsString('class MinStack', $result);
  }

  public function test_normalize_strips_export_keyword_but_keeps_declaration(): void
  {
    $code = "export class MinStack {\n  push(val) {}\n}\nexport default MinStack;";
    $result = Tool::normalizeSolutionCode($code);

    $this->assertStringNotContainsString('export', $result);
    $this->assertStringContainsString('class MinStack', $result);
  }

  public function test_normalize_converts_space_indent_to_tabs(): void
  {
    $code = "class MinStack {\n  constructor() {\n    this.stack = [];\n  }\n}";
    $result = Tool::normalizeSolutionCode($code);

    $this->assertStringContainsString("\n\tconstructor", $result);
    $this->assertStringContainsString("\n\t\tthis.stack", $result);
    $this->assertDoesNotMatchRegularExpression('/^  /m', $result);
  }

  public function test_normalize_converts_four_space_indent_to_tabs(): void
  {
    $code = "function add(a, b) {\n    return a + b;\n}";
    $result = Tool::normalizeSolutionCode($code);

    $this->assertStringContainsString("\n\treturn a + b;", $result);
    $this->assertDoesNotMatchRegularExpression('/^ {2,}/m', $result);
  }

  public function test_sanitize_appends_tab_indentation_rule(): void
  {
    $prompt = 'Create a coding challenge with solution_code.';
    $result = Tool::sanitizeChallengeGenerationPrompt($prompt);

    $this->assertMatchesRegularExpression('/tab indentation|tabs for indent/i', $result);
  }

  public function test_default_challenge_generation_prompt_requires_vanilla_console_tests(): void
  {
    $templates = require dirname(__DIR__, 2) . '/config/openai_prompts.php';
    $prompt = $templates['challenge_generation'];

    $this->assertStringContainsString('no module.exports', $prompt);
    $this->assertStringContainsString('console.log test cases', $prompt);
    $this->assertStringContainsString('perfect tab indentation', $prompt);
  }
}
