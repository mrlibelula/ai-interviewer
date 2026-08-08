# Legacy AI System (pre–structured-output era)

Historical notes for the AI Interviewer before the `feature/ai-enhancements` upgrade.  
Git snapshot: tag [`legacy-gpt-3.5-era`](https://github.com/mrlibelula/ai-interviewer/releases/tag/legacy-gpt-3.5-era) and branch [`archive/legacy-gpt-3.5-era`](https://github.com/mrlibelula/ai-interviewer/tree/archive/legacy-gpt-3.5-era).

## Context

The app was built against **OpenAI Chat Completions** when **gpt-3.5-turbo** was the practical default. There was no reliable schema-guaranteed JSON from the API for this stack, so prompts and post-processing did the heavy lifting.

Stack at that time:

- Laravel 10 + Livewire 3
- `openai-php/laravel` (`OpenAI::chat()->create`)
- Prompt templates in `.env` (`OPENAI_*`)
- Challenge-generation blueprint also persisted in `enviros.prompt` after the admin “Build LLM Prompt” screen
- Dynamic substitution via spaced wildcards: `" ??name "`

## Prompt-forced JSON

Challenge generation used a long `OPENAI_PROMPT_BASE_TEXT` that instructed the model to:

1. Return a JSON object with fixed keys (`title`, `challenge`, `difficulty_level`, `test_cases`, …)
2. Avoid `\n` inside JSON values
3. Append solution code **after** a separator (`OPENAI_CODE_SEPARATOR`, default `%%%%%`), **not** as a JSON key
4. Follow many edge-case rules so `json_decode` would succeed

Parsing lived in `App\Tool::getLLMChallenge()`:

1. Take completion text
2. `explode` on the separator
3. Run `Tool::fixJsonString()` on the left part (repair common LLM JSON mistakes)
4. `json_decode` → challenge metadata
5. Right part → `solution_code`

Code analysis used the same separator idea: free-text feedback, then `%%%%%true|false` so the UI could mark the challenge solved (`Chatbot::userCode`).

## Why it felt fragile

- Models sometimes ignored format rules → null JSON, separator missing, or prose around JSON
- `fixJsonString` and debug `dump`/`info` paths were defensive workarounds
- Chat/analyze/complexity/feedback templates lived only in `.env` (not admin-editable except challenge-gen via `enviros`)
- `enviros.prompt`, `challenge_solver.openai_chat_settings`, and `users.options` stored JSON as text without Eloquent casts

## What stayed the same by design

- `??wildcard` replacement style (`Tool::replaceWildcards` / `Tool::wildcards`)
- Interview chatbot as multi-turn messages on the `challenge_solver` pivot
- JS-in-browser Monaco editor + local `console.log` “output terminal”
- No Laravel AI SDK (`laravel/ai` requires a newer Laravel/PHP than this project)

## What replaced this (post-upgrade)

See current `TECH_STACK.md` / `PROJECT_FEATURES.md`. In short:

- Prefer `response_format` + `json_schema` (structured outputs) for challenge generation and code analysis
- `solution_code` / `solved` live inside schema objects instead of `%%%%%` splits
- Admin **AI settings** edits `enviros.prompt_templates` with `.env` fallback
- Eloquent casts + pivot model for safer JSON I/O
- Default model moved to a cheaper modern fit (e.g. `gpt-5-mini`) while keeping `openai-php/laravel`

This document is intentionally a history file, not the live how-to.
