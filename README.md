<div align="center">

[![Libe.dev](https://libe.dev/images/libesoft.io_inv.png)](https://libe.dev)

# AI Interviewer

**AI-powered technical interviews for hiring teams — built before structured AI responses existed.**

[Portfolio](https://libe.dev) · [Read the build story](https://libe.dev/blog/an-ai-interviewer-built-before-structured-output-existed) · [Technical docs](./TECH_STACK.md)

</div>

---

## Who this is for

**Recruiters**, **engineering managers**, and **founders** who need a faster, fairer way to screen technical talent — especially when interviews are remote, async-friendly, or hard to schedule across time zones.

This is not a generic chatbot wrapper. It is a full interview workflow: curated coding challenges, timed sessions, an in-session AI assistant, automated solution review, and performance analytics your team can actually use in hiring decisions.

> **Historical note:** This app was built when LLMs returned free-form text only — no JSON schema, no structured outputs, no guaranteed parseable fields. Every AI integration in this repo was engineered around that constraint.

---

## Built before structured AI responses

When this project started, you could not ask an API for a typed, validated response. To generate challenges, evaluate code, and power the in-session chatbot, the app had to **coax structure out of prose**:

- **Prompt blueprints** with wildcards (`??topics`, `??languages`, `??difficulty_level`) filled from live database data
- **Custom separators** (`OPENAI_CODE_SEPARATOR`) to split JSON metadata from solution code in a single completion
- **JSON repair logic** to fix truncated brackets and trailing commas when the model drifted
- **Boolean parsing from text** — e.g. `solved: true|false` embedded in a natural-language reply after code analysis

That is the engineering story behind the repo — and why it remains a useful reference for teams integrating LLMs into real products, not just demos.

**[→ Full write-up: An AI Interviewer Built Before Structured Output Existed](https://libe.dev/blog/an-ai-interviewer-built-before-structured-output-existed)**

---

## The problem

Technical hiring is expensive and slow:

- Senior engineers spend hours on first-round screens that could be standardized
- Live coding interviews are hard to schedule and easy to bias
- Take-home assignments often go unreviewed or lack consistent rubrics
- Remote candidates drop off when the process feels opaque or repetitive

**AI Interviewer** compresses the early funnel: candidates work through real challenges while the platform captures attempts, timing, code, chat context, and AI-assisted feedback — so your team reviews signal, not noise.

---

## What it does

| Capability | What hiring teams get |
|------------|----------------------|
| **Challenge library** | Topic- and difficulty-based coding challenges (PHP, JavaScript, Laravel, algorithms, and more) |
| **Timed interview sessions** | Countdown timers, attempt tracking, and completion timestamps per candidate |
| **AI interviewer chatbot** | Context-aware hints and Q&A during the challenge — without giving away the answer |
| **Automated code review** | GPT evaluates submitted solutions and marks challenges solved when criteria are met |
| **Gamification & leaderboards** | XP, speed bonuses, and rankings to keep candidates engaged |
| **Metrics dashboards** | Performance by difficulty, topic, hints used, attempts, time, and comparisons |
| **Admin challenge generation** | Recruiters/admins can generate new challenges from prompt templates via OpenAI |
| **Role-based access** | `admin` / `recruiter` gates for content management; candidates see scrubbed solutions |

---

## How a candidate session works

```
Login → Pick difficulty & topic → Start challenge
  → Code in editor + ask AI assistant
  → Submit solution for AI review
  → Earn XP / view progress → Next challenge or metrics
```

Behind the scenes, every session persists rich data on the `challenge_solver` pivot: attempts, elapsed time, solution code, chat transcript, bonus XP, and solve timestamp — the raw material for shortlists and debriefs.

For architecture diagrams and flow charts, see [TECH_STACK.md](./TECH_STACK.md).

---

## Why this repo exists

A production-minded experiment in **LLM-assisted hiring** — shipped at a time when structured AI responses did not exist. No schema enforcement, no native JSON mode you could trust in production. The team had to design reliability into prompts, parsers, and admin tooling instead.

The blog post covers the full arc: what broke, what we patched, and what we would do differently now that structured outputs are available.

Built by [Libe.dev](https://libe.dev) — portfolio and case studies for founders and hiring leaders evaluating technical partners.

---

## Stack (at a glance)

| Layer | Technologies |
|-------|----------------|
| Backend | Laravel 10, PHP 8.1, Jetstream, Livewire 3, Sanctum |
| AI | OpenAI GPT (chat completions), optional DALL·E banners |
| Auth | Email/password, Google OAuth, Spatie roles (`admin`, `recruiter`) |
| Frontend | Blade, Tailwind CSS, Vite |
| Data | Eloquent, rich pivot tables, soft deletes |

Full package list, data model, and session flows: **[TECH_STACK.md](./TECH_STACK.md)**  
Feature inventory: **[PROJECT_FEATURES.md](./PROJECT_FEATURES.md)**

---

## For engineering leaders evaluating the codebase

- **Pre-structured-output LLM patterns** — separator splitting, JSON repair (`Tool::fixJsonString`), and wildcard prompt injection in `App\Tool` — the glue that made unreliable completions usable
- **Real-time UI without a heavy SPA** — Livewire 3 drives interview sessions, timers, and chat
- **Prompt ops in the database** — `Enviro` table stores blueprints; wildcards inject topics, languages, and existing challenge titles at runtime
- **Defense in depth** — solution code hidden from candidates; role middleware on admin routes; encoded route params
- **Observable hiring signal** — metrics components aggregate solve rate, hint usage, time-to-complete, and leaderboard data

---

## Quick start (developers)

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed

npm run dev          # Vite
php artisan serve    # http://127.0.0.1:8000
```

**Required environment variables:**

```env
OPENAI_API_KEY=
OPENAI_MODEL=gpt-4
OPENAI_CODE_SEPARATOR=%%%CODE%%%
DB_*=...
GOOGLE_CLIENT_ID=...      # optional, for OAuth
GOOGLE_CLIENT_SECRET=...
```

---

## UI reference

Design inspiration: [Intervio — AI Interview Dashboard (Dribbble)](https://dribbble.com/shots/22237746-Intervio-AI-Interview-Dashboard#)

---

## Resources

- [Libe.dev — Portfolio](https://libe.dev)
- [Blog: How this app was built](https://libe.dev/blog/an-ai-interviewer-built-before-structured-output-existed)
- [OpenAI PHP client](https://github.com/openai-php/client)
- [OpenAI for Laravel](https://laravel-news.com/openai-for-laravel)
- [Laracasts: Fun with OpenAI and Laravel](https://laracasts.com/series/fun-with-openai-and-laravel/episodes/1)

---

## License

MIT

---

<div align="center">

**Questions about adopting or extending this for your hiring pipeline?**  
Visit [libe.dev](https://libe.dev) or open an issue.

</div>
