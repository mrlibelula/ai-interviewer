# AI Interviewer — Technical Feature Report

## Stack
- Backend: Laravel 10 (PHP 8.1), Jetstream Livewire stack, Sanctum sessions, Spatie Permission for roles, Socialite (Google OAuth).
- AI: openai-php/laravel for chat completions and DALL·E image gen; custom `App\Tool` helpers to build prompts, parse completions, and import AI-created challenges.
- Frontend: Blade + Livewire 3 components, Tailwind CSS (forms/typography), Vite build, Axios for HTTP.
- Data: Eloquent models with rich pivot data (`challenge_solver`), soft deletes, seeders for baseline content.
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
  - Builds a personalized chat welcome message from env config.
- **Chatbot assistance**: Validates user chat input, appends to stored `openai_chat_settings` per challenge/user, emits UI events for loader/error states, and keeps the chat transcript for OpenAI follow-ups.
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
- **Admin prompt building**:
  - Admin-only screens let you curate prompt blueprints stored in the `Enviro` table; blueprints use wildcards (`??topics`, `??languages`, `??difficulty_level`, `??dbchallenges`, `??tags`, `??separator`) so a single template adapts to different interview contexts.
  - `Tool::replaceWildcards` and `Tool::wildcards` inject live data (topics, languages in DB, tag lists, recent challenge titles) to produce a fully contextualized prompt per request.
  - Separator-aware splitting (`OPENAI_CODE_SEPARATOR`) keeps narrative and code outputs separated for reliable parsing.
  - Final prompts are saved (`updateEnviroPromptString`) so operators can preview/iterate before calling OpenAI.
- **AI challenge generation**:
  - Prompt blueprints with wildcard replacement (`??topics`, `??languages`, `??difficulty_level`, `??dbchallenges`, `??tags`, `??separator`).
  - `Tool::getLLMChallenge` calls OpenAI Chat API, fixes malformed JSON, splits content from code via a configurable separator, and emulates a `Challenge` model from the response.
  - `Tool::importAIChallenge` persists the challenge, links topics/languages/frameworks/packages/tags, stores the original prompt/completion IDs, and records the model used.
  - Optional DALL·E image generation for challenge banners (configurable prompt template).
- **Prompt builder & environment**:
  - Prompt parts stored in `Enviro` table; utilities to update the final prompt string and to expand separator-based parts for better LLM control.
  - Helpers to encode/decode route-safe data and to sanitize AI answer strings for storage/display.
- **Embed editor**: `/embed-editor` serves a stored HTML code editor from `storage/app/code-editor/editor.html` for inline coding previews.

## Data Model Highlights
- `Challenge` belongs to `Difficulty`, `Status`, `Visibility`; many-to-many with `Topic`, `Language`, `Framework`, `Package`, `Tag`, and `User` (as creator and solver).
- `challenge_solver` pivot includes attempts, time_limit snapshot, solution code, bonus XP, openai chat settings, observations, and solved timestamp.
- Utility queries: filter challenges by difficulty/topic, fetch counts per difficulty, compute solved percentage, and paginate solved-challenge metrics.

## Architecture & Runtime Notes
- Routes: Authenticated group for landing/dashboard/interview/metrics; role-gated admin group for dashboard, prompt tooling, and challenge management; public login and Google OAuth endpoints.
- Livewire events drive UI feedback (toasts, loaders) and timer control; session storage caches pending challenge queues.
- Time calculations and XP logic rely on Carbon helpers (`calculateSeconds`, `calculateCompletionTime`, `calculateBonusXP`).
- Security: Role middleware plus Sanctum session guard; solution code stripped for non-admin solvers to avoid leakage.

## Developer Experience
- Build: `npm run dev`/`npm run build` (Vite); Tailwind via PostCSS; Laravel assets via Jetstream presets.
- Quality: PHPUnit tests scaffolded for auth/profile/token flows; Pint for formatting; Debugbar for local profiling.
- Config: Requires `OPENAI_API_KEY`, `OPENAI_MODEL`, `OPENAI_CODE_SEPARATOR`, OAuth client IDs/secrets, and DB creds; prompt templates and defaults live in the `enviro` table and `.env`.
