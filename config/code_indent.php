<?php

/**
 * Per-language indentation conventions for solution_code / codebox / Monaco.
 * `style`: "space" | "tab"
 * `size`: spaces per level, or tab characters per level
 *
 * String values are aliases to another key (e.g. 'js' => 'javascript').
 */
return [

  'default' => [
    'style' => 'tab',
    'size' => 1,
  ],

  /*
   * JavaScript / TypeScript — common community style (Prettier / Airbnb default).
   */
  'javascript' => [
    'style' => 'space',
    'size' => 2,
  ],

  'js' => 'javascript',
  'node' => 'javascript',
  'nodejs' => 'javascript',
  'typescript' => 'javascript',
  'ts' => 'javascript',
  'tsx' => 'javascript',

  // Future examples (not applied until explicitly wired):
  // 'python' => ['style' => 'space', 'size' => 4],
  // 'php' => ['style' => 'space', 'size' => 4],
  // 'go' => ['style' => 'tab', 'size' => 1],

];
