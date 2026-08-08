# AI Interviewer — Technical Feature Report

## Stack
- Backend: Laravel 10 (PHP 8.1), Jetstream Livewire stack, Sanctum sessions, Spatie Permission for roles, Socialite (Google OAuth).
- AI: `openai-php/laravel` for chat completions (optional structured `json_schema` for challenge gen + code analysis); custom `App\Tool` helpers for prompts, schemas, and imports. Not using `laravel/ai` (requires newer Laravel/PHP).
- Frontend: Blade + Livewire 3 components, Tailwind CSS (forms/typography), Vite build, Axios for HTTP.
- Data: Eloquent models with rich pivot data (`challenge_solver`), soft deletes, seeders for baseline content; `Enviro` holds challenge-gen prompt + admin `prompt_templates`.
- Tooling: Laravel Pint, PHPUnit, Debugbar; Vite/Tailwind dev workflow.

## Core User Flows
- **Auth & roles**: Email/password via Jetstream, email verification, Sanctum sessions; Google OAuth login; role gates (`admin`/`recruiter`) around admin routes.
- **Landing & dashboard**: Authenticated users reach `landing` and `dashboard` views that summarize progress and entry points into interviews.
- **Challenge selection**: `Interview` Livewire component lets users pick difficulty and topic; fetches available challenges (optionally by banner) and resets cached selections when filters change.
- **Interview session**: `Start` Livewire component drives the interview:
  - Decodes encoded route params for difficulty/topic/challenge, manages a queue of challenge IDs (with optional shuffle).
  - Tracks attempts, elapsed time, and solved status per user/challenge pivot.
  - Enforces time limits and stops timers when solved or expired.
  - Computes bonus XP and extra bonus based on completion speed; persists to pivot (`challenge_solver`) along with solution timing.
  - Builds a personalized chat welcome message from `Tool::promptTemplate('welcome')`.
- **Chatbot assistance**: Validates user chat input, appends to stored `openai_chat_settings` per challenge/user, emits UI events for loader/error states, and keeps the chat transcript for OpenAI follow-ups. Interviewer-style prompts discourage full solutions.
- **Hints & solutions**: Challenges carry hints, test cases, and solution code (solution stripped for non-admin solvers to prevent leakage).
- **Gamification & metrics**:
  - Progress counters (total vs solved), percentage solved, per-challenge/user bonus totals.
  - Metrics pages for difficulty, hint usage, topic, attempts, time-based performance, leaderboard, and comparison views.
  - Feedback history structures for AI-generated performance feedback across problem-specific, optimization, and best-practice categories.
- **Leaderboard data**: `challenge_solver` pivot stores attempts, bonus XP, extra bonus, solution code, and solved timestamps to power leaderboards and historical stats.

## Content & Admin Features
- **Challenge management (admin/recruiter)**:
  - List, soft-delete, and edit challenges; adjust status/visibility/difficulty and time limits with inline validation.
  - Attach/detach topics, languages, frameworks, packages, and tags; reloads challenge relations after each toggle.
  - Views include AI solution code for privileged roles.
- **AI admin settings** (`/admin/ai-settings`):
  - Replaces the old non-functional “Manage Topics” nav stub.
  - Edit interview-related prompt templates stored in `enviros.prompt_templates` (welcome, recommendations, session system, analyze, complexity, feedback, DALL·E, challenge-generation content rules).
  - Falls back to `config/openai_prompts.php` / `.env` when DB values are empty.
  - Documents `??` wildcards for operators.
- **Admin prompt building**:
  - Admin-only screens curate challenge-generation blueprints in `enviros.prompt` with wildcards (`??topics`, `??languages`, `??difficulty_level`, `??dbchallenges`, `??tags`, …).
  - `Tool::replaceWildcards` / `Tool::wildcards` inject live data.
- **AI challenge generation**:
  - Uses OpenAI **structured outputs** (`response_format` / `json_schema`) so challenge fields + `solution_code` arrive as one JSON object.
  - Legacy separator (`%%%%%`) + `fixJsonString` kept as fallback only.
  - `Tool::importAIChallenge` persists the challenge and relations; optional DALL·E banners (prompt template driven).
- **Embed editor**: `/embed-editor` serves Monaco from `storage/app/code-editor/editor.html`. Host page uses `wire:ignore` around the iframe so Livewire re-renders do not destroy the editor; CDN load is pinned/single-path to reduce blank-iframe races. Host height styling left intentionally as-is.

## Data Model Highlights
- `Challenge` belongs to `Difficulty`, `Status`, `Visibility`; many-to-many with `Topic`, `Language`, `Framework`, `Package`, `Tag`, and `User` (as creator and solver).
- `challenge_solver` pivot includes attempts, time_limit snapshot, solution code, bonus XP, openai chat settings, observations, and solved timestamp; custom `ChallengeSolver` pivot model casts chat settings to array.
- `enviros.prompt_templates` JSON for admin-editable AI prompt overrides.
- Utility queries: filter challenges by difficulty/topic, fetch counts per difficulty, compute solved percentage, and paginate solved-challenge metrics.

## Architecture & Runtime Notes
- Routes: Authenticated group for landing/dashboard/interview/metrics; role-gated admin group for dashboard, prompt tooling, AI settings, and challenge management; public login and Google OAuth endpoints.
- Livewire events drive UI feedback (toasts, loaders) and timer control; session storage caches pending challenge queues.
- Default model via `OPENAI_MODEL` (e.g. `gpt-5-mini`).
- Security: Role middleware plus Sanctum session guard; solution code stripped for non-admin solvers to avoid leakage.

## History
- Pre-upgrade (gpt-3.5-era, prompt-forced JSON, separators): see [`docs/LEGACY_AI_SYSTEM.md`](docs/LEGACY_AI_SYSTEM.md).
- Git snapshot: tag `legacy-gpt-3.5-era`, branch `archive/legacy-gpt-3.5-era`.

## Developer Experience
- Build: `npm run dev`/`npm run build` (Vite); Tailwind via PostCSS; Laravel assets via Jetstream presets.
- Quality: PHPUnit tests scaffolded for auth/profile/token flows; Pint for formatting; Debugbar for local profiling.
- Config: Requires `OPENAI_API_KEY`, `OPENAI_MODEL`, OAuth client IDs/secrets, and DB creds; prompt defaults in `config/openai_prompts.php` with Enviro/admin overrides.
