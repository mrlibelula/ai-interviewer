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

  public function test_normalize_keeps_indent_on_long_single_statement_lines(): void
  {
    $code = <<<'JS'
function countWays(n, steps) {
  // Validate and preprocess steps: unique positive integers only
  const allowed = Array.from(new Set(steps)).filter(s => Number.isInteger(s) && s > 0);
  if (n === 0) return 1;
  return allowed.length;
}
JS;

    $result = Tool::normalizeSolutionCode($code);
    $lines = explode("\n", $result);

    $this->assertSame("\t", substr($lines[1], 0, 1));
    $this->assertStringStartsWith("\tconst allowed", $lines[2]);
    $this->assertStringStartsWith("\tif (n === 0)", $lines[3]);
  }

  public function test_normalize_reindents_expanded_dense_nested_loops(): void
  {
    $code = <<<'JS'
function countWays(n, steps) {
  const dp = new Array(n + 1).fill(0);
  dp[0] = 1;

  for (let i = 1; i <= n; i++) { let sum = 0; for (let j = 0; j < allowed.length; j++) { const step = allowed[j]; if (i - step >= 0) sum += dp[i - step]; } dp[i] = sum; }

  return dp[n];
}
JS;

    $result = Tool::normalizeSolutionCode($code);

    $this->assertStringContainsString("\n\tfor (let i = 1; i <= n; i++) {", $result);
    $this->assertStringContainsString("\n\t\tlet sum = 0;", $result);
    $this->assertStringContainsString("\n\t\tfor (let j = 0; j < allowed.length; j++) {", $result);
    $this->assertStringContainsString("\n\t\t\tconst step = allowed[j];", $result);
    $this->assertStringContainsString("\n\t\tdp[i] = sum;", $result);
    $this->assertStringContainsString("\n\treturn dp[n];", $result);
  }

  public function test_normalize_reindents_inconsistent_ai_indentation(): void
  {
    $code = <<<'JS'
function countWays(n, steps) {
  const dp = new Array(n + 1).fill(0);
dp[0] = 1;
for (let i = 1; i <= n; i++) {
    let sum = 0;
for (let j = 0; j < allowed.length; j++) {
        const step = allowed[j];
        if (i - step >= 0) sum += dp[i - step];
    }
    dp[i] = sum;
}
return dp[n];
}
JS;

    $result = Tool::normalizeSolutionCode($code);

    $this->assertStringContainsString("\n\tdp[0] = 1;", $result);
    $this->assertStringContainsString("\n\tfor (let i = 1; i <= n; i++) {", $result);
    $this->assertStringContainsString("\n\t\tlet sum = 0;", $result);
    $this->assertStringContainsString("\n\t\tfor (let j = 0; j < allowed.length; j++) {", $result);
    $this->assertStringContainsString("\n\t\t\tconst step = allowed[j];", $result);
    $this->assertStringContainsString("\n\treturn dp[n];", $result);
  }

  public function test_normalize_does_not_reindent_python(): void
  {
    $code = "def add(a, b):\n  return a + b\n";
    $this->assertSame("def add(a, b):\n\treturn a + b", Tool::normalizeSolutionCode($code));
  }
}
