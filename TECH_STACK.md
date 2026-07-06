# AI Interviewer — Technical Stack & Architecture

> A comprehensive technical overview for content creation targeting tech interviewers and talent hunters.

---

## Project Summary

**AI Interviewer** is a modern web application that leverages artificial intelligence to revolutionize the technical interview process. Built on a robust Laravel 10 foundation with real-time Livewire components and OpenAI GPT integration, this platform enables recruiters to conduct AI-powered coding challenges, automate candidate assessments, and generate intelligent performance feedback.

---

## Technology Stack Overview

### Backend Framework

| Technology | Version | Purpose |
|------------|---------|---------|
| **PHP** | ^8.1 | Server-side runtime |
| **Laravel** | 10.x | Full-stack PHP framework |
| **Laravel Jetstream** | ^4.2 | Authentication scaffolding with teams, profile management |
| **Laravel Livewire** | ^3.0 | Real-time, reactive UI components without JavaScript |
| **Laravel Sanctum** | ^3.3 | SPA/session authentication |
| **Laravel Socialite** | ^5.21 | OAuth2 social login (Google) |
| **Spatie Laravel Permission** | ^6.3 | Role-based access control (RBAC) |

### AI & Machine Learning

| Technology | Version | Purpose |
|------------|---------|---------|
| **openai-php/laravel** | ^0.8.1 | OpenAI API client for Laravel |
| **OpenAI GPT Models** | Configurable via ENV | AI chat completions, challenge generation |
| **DALL·E Integration** | API v1 | AI-generated challenge banner images |

### Frontend Stack

| Technology | Version | Purpose |
|------------|---------|---------|
| **Blade Templates** | Laravel 10 native | Server-side templating |
| **Livewire 3** | ^3.0 | Reactive components, real-time updates |
| **Tailwind CSS** | ^3.1.0 | Utility-first CSS framework |
| **@tailwindcss/forms** | ^0.5.2 | Form styling plugin |
| **@tailwindcss/typography** | ^0.5.0 | Prose content styling |
| **Vite** | ^5.0.0 | Next-gen frontend build tool |
| **Axios** | ^1.6.4 | HTTP client for API requests |
| **Alpine.js** | (via Livewire) | Lightweight JS for interactivity |

### Development & Tooling

| Technology | Version | Purpose |
|------------|---------|---------|
| **Laravel Pint** | ^1.0 | PHP code style fixer (PSR-12) |
| **PHPUnit** | ^10.1 | Unit & feature testing |
| **Laravel Sail** | ^1.18 | Docker development environment |
| **Mockery** | ^1.4.4 | Test mocking library |
| **FakerPHP** | ^1.9.1 | Fake data generation |
| **Laravel Debugbar** | ^3.13 | Development debugging toolbar |
| **Spatie Laravel Ignition** | ^2.0 | Error page & debugging |
| **Nunomaduro Collision** | ^7.0 | CLI error reporting |
| **PostCSS** | ^8.4.14 | CSS post-processing |
| **Autoprefixer** | ^10.4.7 | CSS vendor prefixing |

### HTTP & APIs

| Technology | Version | Purpose |
|------------|---------|---------|
| **GuzzleHTTP** | ^7.2 | HTTP client for external APIs |

---

## Application Architecture

### Project Structure

```
ai-interviewer/
├── app/
│   ├── Http/Controllers/     # Traditional controllers (OAuth, etc.)
│   ├── Livewire/             # Livewire components (main UI logic)
│   │   ├── Admin/            # Admin dashboard, prompt builder, challenges
│   │   ├── Interview.php     # Challenge selection
│   │   ├── Start.php         # Interview session driver
│   │   ├── Chatbot.php       # AI assistant component
│   │   ├── Metrics*.php      # Analytics components
│   │   └── ...
│   ├── Models/               # Eloquent models
│   │   ├── Challenge.php     # Core challenge entity
│   │   ├── User.php          # Extended user with gamification
│   │   ├── Topic.php         # Hierarchical topics
│   │   ├── Difficulty.php    # Challenge difficulty levels
│   │   ├── Language.php      # Programming languages
│   │   ├── Framework.php     # Frameworks (Laravel, React, etc.)
│   │   ├── Package.php       # Libraries/packages
│   │   ├── Tag.php           # Challenge tags
│   │   ├── Enviro.php        # Prompt configuration storage
│   │   └── ...
│   └── Tool.php              # AI utilities & helper functions
├── config/
│   ├── openai.php            # OpenAI API configuration
│   ├── permission.php        # Spatie roles/permissions
│   ├── jetstream.php         # Auth & team settings
│   └── ...
├── resources/
│   ├── views/
│   │   ├── livewire/         # Livewire component views
│   │   ├── components/       # Blade components
│   │   └── layouts/          # App layouts
│   ├── css/app.css           # Tailwind entry point
│   └── js/app.js             # Alpine/Axios bootstrap
├── routes/
│   ├── web.php               # Web routes with auth groups
│   └── api.php               # API endpoints
└── database/
    ├── migrations/           # Schema definitions
    ├── seeders/              # Baseline data
    └── factories/            # Model factories
```

### Authentication & Authorization

```
┌─────────────────────────────────────────────────────────────┐
│                    Authentication Flow                       │
├─────────────────────────────────────────────────────────────┤
│  Email/Password → Jetstream → Sanctum Session               │
│  Google OAuth   → Socialite → Sanctum Session               │
│  Email Verification → Required for protected routes         │
├─────────────────────────────────────────────────────────────┤
│                    Authorization Flow                        │
├─────────────────────────────────────────────────────────────┤
│  Roles: admin | recruiter | user                            │
│  Middleware: role:admin|recruiter → Admin routes            │
│  Spatie Permission → Route & action protection              │
└─────────────────────────────────────────────────────────────┘
```

---

## Core Features & Implementation

### 1. AI-Powered Challenge Generation

The platform uses OpenAI's GPT models to dynamically generate coding challenges:

**Prompt Engineering System:**
- Configurable prompt blueprints stored in `Enviro` table
- Dynamic wildcard replacement system (`??topics`, `??languages`, `??difficulty_level`, etc.)
- Separator-based content splitting for reliable JSON + code extraction
- Auto-fix for malformed JSON responses from LLM

**Key Methods in `App\Tool`:**
- `getLLMChallenge()` — Calls OpenAI API, parses response, creates emulated Challenge model
- `importAIChallenge()` — Persists AI-generated challenge with all relations
- `wildcards()` — Injects live database data into prompt templates
- `fixJsonString()` — Repairs malformed JSON from LLM responses
- `generateChallengeImage()` — DALL·E integration for banner images

### 2. Real-Time Interview Sessions

**Livewire Components:**
- `Interview.php` — Topic/difficulty selection with dynamic filtering
- `Start.php` — Session driver with timer, attempts, solution tracking
- `Chatbot.php` — AI assistant with conversation persistence
- `CountdownTimer.php` — Real-time countdown with pause/resume

**Session Features:**
- Encoded route parameters for security
- Challenge queue management with optional shuffle
- Per-user time tracking and attempt counting
- XP/bonus calculation based on completion speed

### 3. Gamification & Metrics

**XP System:**
- Base XP for challenge completion
- Bonus XP tiers based on completion time thresholds
- Extra bonus calculated from time saved

**Analytics Dashboards:**
- `MetricsDifficulty` — Performance by difficulty level
- `MetricsHintUsage` — Hint utilization analysis
- `MetricsTopic` — Topic-wise performance
- `MetricsAttempts` — Attempt patterns analysis
- `MetricsTimeBased` — Time performance metrics
- `MetricsLeaderboard` — User rankings
- `MetricsComparison` — Comparative analytics

### 4. Admin Prompt Builder

**Features:**
- Visual prompt template editor with wildcard placeholders
- Live preview with database data injection
- Separator-aware content structuring
- Direct OpenAI API testing from admin panel
- Challenge import with automatic relation linking

---

## Data Model

### Entity Relationship Overview

```
                              ┌───────────────┐
                              │     User      │
                              └───────┬───────┘
                                      │
               ┌──────────────────────┼──────────────────────┐
               │                      │                      │
               ▼                      ▼                      ▼
        ┌─────────────┐       ┌──────────────┐       ┌─────────────┐
        │   Creator   │       │    Solver    │       │    Role     │
        │  (M:M pivot)│       │(M:M pivot +  │       │  (Spatie)   │
        │             │       │  rich data)  │       │             │
        └──────┬──────┘       └──────┬───────┘       └─────────────┘
               │                     │
               └──────────┬──────────┘
                          ▼
                 ┌─────────────────┐
                 │    Challenge    │
                 └────────┬────────┘
                          │
        ┌─────────────────┼─────────────────┐
        │                 │                 │
        ▼                 ▼                 ▼
  ┌───────────┐    ┌───────────┐    ┌────────────┐
  │Difficulty │    │  Status   │    │ Visibility │
  │  (1:M)    │    │   (1:M)   │    │   (1:M)    │
  │ belongsTo │    │ belongsTo │    │ belongsTo  │
  └───────────┘    └───────────┘    └────────────┘

                          │
    ┌───────────┬─────────┼─────────┬───────────┐
    │           │         │         │           │
    ▼           ▼         ▼         ▼           ▼
┌────────┐ ┌────────┐ ┌────────┐ ┌────────┐ ┌────────┐
│ Topic  │ │  Tag   │ │Language│ │Frame-  │ │Package │
│ (M:M)  │ │ (M:M)  │ │ (M:M)  │ │ work   │ │ (M:M)  │
│        │ │        │ │        │ │ (M:M)  │ │        │
│ ┌────┐ │ └────────┘ └────────┘ └────────┘ └────────┘
│ │Tree│ │   (belongsToMany - pivot tables)
│ └────┘ │
└────────┘
     ▲
     │ self-referential
     │ (parent_id)
     ▼
┌────────┐
│Children│
│Topics  │
└────────┘
```

**Relationship Legend:**
- `1:M` = One-to-Many (Challenge `belongsTo` one Difficulty/Status/Visibility)
- `M:M` = Many-to-Many (Challenge `belongsToMany` Topic/Tag/Language/Framework/Package)
- Topic has hierarchical self-referential relationship via `parent_id`

### Key Pivot Table: `challenge_solver`

| Column | Type | Description |
|--------|------|-------------|
| `user_id` | FK | Solver reference |
| `challenge_id` | FK | Challenge reference |
| `solved_at` | timestamp | Completion timestamp |
| `solved_time_seconds` | int | Time to complete |
| `current_time_limit` | string | Snapshot of time limit |
| `attempts` | int | Number of attempts |
| `bonus_xp` | int | Base XP earned |
| `extra_bonus` | int | Speed bonus |
| `solution_code` | text | User's solution |
| `openai_chat_settings` | json | Chat transcript |
| `observations` | text | Notes/feedback |

---

## Application Flow Diagrams

### High-Level User Journey

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                        AI INTERVIEWER - USER JOURNEY                         │
└─────────────────────────────────────────────────────────────────────────────┘

    ┌──────────────┐     ┌──────────────┐     ┌──────────────┐
    │    Login     │────▶│   Landing    │────▶│  Dashboard   │
    │              │     │    Page      │     │   Overview   │
    │ • Email/Pass │     │              │     │              │
    │ • Google OAuth│     │ • Welcome    │     │ • Stats      │
    └──────────────┘     │ • Quick nav  │     │ • Progress   │
                         └──────────────┘     └───────┬──────┘
                                                      │
                         ┌────────────────────────────┼────────────────────┐
                         ▼                            ▼                    ▼
                  ┌─────────────┐            ┌──────────────┐      ┌─────────────┐
                  │  Interview  │            │   Metrics    │      │   Admin     │
                  │  Selection  │            │  Dashboards  │      │   Panel     │
                  └──────┬──────┘            └──────────────┘      │(admin only) │
                         │                                         └─────────────┘
                         ▼
            ┌────────────────────────┐
            │  SELECT DIFFICULTY     │
            │  ┌─────┬───────┬─────┐ │
            │  │Easy │Medium │Hard │ │
            │  └─────┴───────┴─────┘ │
            └───────────┬────────────┘
                        ▼
            ┌────────────────────────┐
            │    SELECT TOPIC        │
            │  (filtered by          │
            │   difficulty)          │
            │                        │
            │  • JavaScript          │
            │  • PHP / Laravel       │
            │  • Data Structures     │
            │  • Algorithms          │
            │  • ...                 │
            └───────────┬────────────┘
                        ▼
            ┌────────────────────────┐
            │   CHALLENGE LIST       │
            │  (filtered by          │
            │   difficulty + topic)  │
            │                        │
            │  Shows: solved status, │
            │  banners, descriptions │
            └───────────┬────────────┘
                        ▼
            ┌────────────────────────┐
            │   START INTERVIEW      │──────────────────────────▶
            │   SESSION              │    (See Challenge Flow)
            └────────────────────────┘
```

### Challenge Session Flow (Candidate Experience)

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    CHALLENGE SESSION - DETAILED FLOW                         │
└─────────────────────────────────────────────────────────────────────────────┘

                    ┌────────────────────────────────┐
                    │       SESSION INIT             │
                    │  • Decode route params         │
                    │  • Load challenge queue        │
                    │  • Attach user to challenge    │
                    │  • Increment attempts          │
                    │  • Load chat history           │
                    └───────────────┬────────────────┘
                                    ▼
    ┌───────────────────────────────────────────────────────────────────────┐
    │                         INTERVIEW SESSION UI                           │
    │  ┌─────────────────────────────────┬─────────────────────────────────┐ │
    │  │       CHALLENGE PANEL           │         AI CHATBOT PANEL        │ │
    │  │  ┌───────────────────────────┐  │  ┌───────────────────────────┐  │ │
    │  │  │ Title & Description       │  │  │ Welcome Message           │  │ │
    │  │  │ Difficulty Badge          │  │  │ "Hi [Name], I'm your AI   │  │ │
    │  │  │ Topic Tags                │  │  │  interviewer..."          │  │ │
    │  │  └───────────────────────────┘  │  └───────────────────────────┘  │ │
    │  │  ┌───────────────────────────┐  │  ┌───────────────────────────┐  │ │
    │  │  │ ⏱️ COUNTDOWN TIMER        │  │  │ 💬 CHAT MESSAGES          │  │ │
    │  │  │    00:45:00               │  │  │                           │  │ │
    │  │  └───────────────────────────┘  │  │ [User]: How do I start?   │  │ │
    │  │  ┌───────────────────────────┐  │  │ [AI]: Consider the edge   │  │ │
    │  │  │ 💡 HINTS                  │  │  │ cases first...            │  │ │
    │  │  │ • Hint 1: Think about...  │  │  │                           │  │ │
    │  │  │ • Hint 2: Consider...     │  │  └───────────────────────────┘  │ │
    │  │  └───────────────────────────┘  │  ┌───────────────────────────┐  │ │
    │  │  ┌───────────────────────────┐  │  │ 📝 INPUT                  │  │ │
    │  │  │ 📝 CODE EDITOR            │  │  │ [Type your question...]   │  │ │
    │  │  │                           │  │  │ [Send] [Analyze Code]     │  │ │
    │  │  │ function solve() {        │  │  │ [Check Complexity]        │  │ │
    │  │  │   // your code here       │  │  └───────────────────────────┘  │ │
    │  │  │ }                         │  │                                  │ │
    │  │  │                           │  │  ┌───────────────────────────┐  │ │
    │  │  │ [Save] [Submit]           │  │  │ 📊 STATS                  │  │ │
    │  │  └───────────────────────────┘  │  │ Attempts: 2               │  │ │
    │  │                                  │  │ Bonus XP: 0 (pending)     │  │ │
    │  │                                  │  │ Solved: 5/120             │  │ │
    │  └─────────────────────────────────┴─────────────────────────────────┘ │
    └───────────────────────────────────────────────────────────────────────┘
                                    │
         ┌──────────────────────────┼──────────────────────────┐
         ▼                          ▼                          ▼
┌─────────────────┐      ┌───────────────────┐      ┌──────────────────┐
│  ASK CHATBOT    │      │  SUBMIT CODE      │      │  TIME EXPIRES    │
│                 │      │  FOR ANALYSIS     │      │                  │
│ User types      │      │                   │      │ Timer reaches    │
│ question        │      │ Code sent to      │      │ 00:00:00         │
└────────┬────────┘      │ OpenAI for        │      └────────┬─────────┘
         │               │ evaluation        │               │
         ▼               └─────────┬─────────┘               ▼
┌─────────────────┐                │               ┌──────────────────┐
│ OpenAI API Call │                ▼               │  SESSION ENDED   │
│                 │      ┌───────────────────┐     │  (No bonus XP)   │
│ Context:        │      │   AI EVALUATION   │     │                  │
│ • Challenge     │      │                   │     │ • Timer stops    │
│ • Difficulty    │      │ GPT analyzes:     │     │ • bonus_xp = 0   │
│ • Topic         │      │ • Correctness     │     │ • Can still      │
│ • User history  │      │ • Edge cases      │     │   practice       │
└────────┬────────┘      │ • Best practices  │     └──────────────────┘
         │               │                   │
         ▼               │ Returns:          │
┌─────────────────┐      │ • Feedback text   │
│ Response saved  │      │ • solved: bool    │
│ to chat history │      └─────────┬─────────┘
│                 │                │
│ Persisted in    │       ┌───────┴───────┐
│ pivot table     │       ▼               ▼
└─────────────────┘   CORRECT          INCORRECT
                          │               │
                          ▼               ▼
              ┌──────────────────┐  ┌──────────────────┐
              │  🎉 SOLVED!      │  │  Keep trying...  │
              │                  │  │                  │
              │ • Timer STOPS    │  │ • Hints shown    │
              │ • Calculate XP:  │  │ • AI feedback    │
              │   - Base bonus   │  │ • Try again      │
              │   - Speed bonus  │  │                  │
              │ • Update pivot   │  └──────────────────┘
              │ • solved_at set  │
              └────────┬─────────┘
                       │
                       ▼
              ┌──────────────────┐
              │  NEXT CHALLENGE  │
              │  or              │
              │  VIEW METRICS    │
              └──────────────────┘
```

### XP & Bonus Calculation Flow

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         XP CALCULATION SYSTEM                                │
└─────────────────────────────────────────────────────────────────────────────┘

              Challenge Time Limit: 01:00:00 (3600 seconds)
                              │
                              ▼
              ┌───────────────────────────────┐
              │   User completes challenge    │
              │   Elapsed time recorded       │
              └───────────────┬───────────────┘
                              │
                              ▼
              ┌───────────────────────────────┐
              │  completion_time = time_limit │
              │              - elapsed_time   │
              └───────────────┬───────────────┘
                              │
         ┌────────────────────┼────────────────────┐
         ▼                    ▼                    ▼
┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐
│ FAST COMPLETION │  │ MEDIUM SPEED    │  │ SLOW/TIMEOUT    │
│ < 5 min (300s)  │  │ < 10 min (600s) │  │ > 10 min        │
│                 │  │                 │  │                 │
│ bonus_xp = 20   │  │ bonus_xp = 10   │  │ bonus_xp = 0    │
│ extra_bonus =   │  │ extra_bonus =   │  │ extra_bonus = 0 │
│ (300-time)*0.1  │  │ (600-time)*0.05 │  │                 │
└────────┬────────┘  └────────┬────────┘  └────────┬────────┘
         │                    │                    │
         └────────────────────┼────────────────────┘
                              ▼
              ┌───────────────────────────────┐
              │   PERSIST TO DATABASE         │
              │                               │
              │   challenge_solver pivot:     │
              │   • bonus_xp                  │
              │   • extra_bonus               │
              │   • solved_time_seconds       │
              │   • solved_at                 │
              └───────────────────────────────┘
```

### AI Chatbot Interaction Flow

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                      AI CHATBOT - MESSAGE FLOW                               │
└─────────────────────────────────────────────────────────────────────────────┘

    User Action                    System                         OpenAI
    ───────────                    ──────                         ──────
         │                            │                              │
         │  1. Type message           │                              │
         │  (3-150 chars)             │                              │
         │ ──────────────────────────▶│                              │
         │                            │                              │
         │                            │  2. Validate input           │
         │                            │  ─────────────┐              │
         │                            │               │              │
         │                            │◀──────────────┘              │
         │                            │                              │
         │                            │  3. Append to chat history   │
         │                            │  ─────────────┐              │
         │                            │               │              │
         │                            │◀──────────────┘              │
         │                            │                              │
         │                            │  4. Build context prompt     │
         │                            │  ─────────────────────────────▶
         │                            │                              │
         │                            │     Context includes:        │
         │                            │     • Challenge title/desc   │
         │                            │     • Topic & difficulty     │
         │                            │     • Full chat history      │
         │                            │     • System instructions    │
         │                            │                              │
         │                            │  5. GPT response             │
         │                            │◀─────────────────────────────│
         │                            │                              │
         │                            │  6. Save to pivot table      │
         │                            │  (openai_chat_settings)      │
         │                            │  ─────────────┐              │
         │                            │               │              │
         │                            │◀──────────────┘              │
         │                            │                              │
         │  7. Display response       │                              │
         │◀───────────────────────────│                              │
         │                            │                              │
         │  8. Dispatch 'speak'       │                              │
         │  (TTS if enabled)          │                              │
         │◀───────────────────────────│                              │
         │                            │                              │

    ┌─────────────────────────────────────────────────────────────────────┐
    │  SPECIAL ACTIONS:                                                    │
    │                                                                      │
    │  [Analyze Code] ─────▶ userCode() ─────▶ GPT evaluates solution     │
    │                                          Returns: feedback + solved  │
    │                                                                      │
    │  [Check Complexity] ──▶ complexityCode() ▶ GPT analyzes Big-O       │
    │                                            Returns: time/space       │
    │                                                                      │
    │  [Save Code] ─────────▶ saveUserCode() ──▶ Persists to pivot table  │
    └─────────────────────────────────────────────────────────────────────┘
```

### Admin Challenge Generation Flow

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    ADMIN - AI CHALLENGE GENERATION                           │
└─────────────────────────────────────────────────────────────────────────────┘

┌──────────────────┐     ┌───────────────────────────────────────────────────┐
│ PROMPT BUILDER   │     │                  WORKFLOW                          │
│                  │     │                                                    │
│ 1. Select Topic  │     │   ┌─────────────┐                                 │
│ 2. Select Diff.  │     │   │ Load prompt │                                 │
│ 3. Edit Template │────▶│   │ blueprint   │                                 │
│                  │     │   └──────┬──────┘                                 │
│ Wildcards:       │     │          ▼                                        │
│ ??topics         │     │   ┌─────────────────────────────┐                 │
│ ??languages      │     │   │ Replace wildcards with      │                 │
│ ??difficulty     │     │   │ live database data:         │                 │
│ ??dbchallenges   │     │   │                             │                 │
│ ??tags           │     │   │ • Topics from DB            │                 │
│ ??separator      │     │   │ • Languages from DB         │                 │
└──────────────────┘     │   │ • Existing challenge titles │                 │
                         │   │ • Available tags            │                 │
                         │   └──────────────┬──────────────┘                 │
                         │                  ▼                                │
                         │   ┌─────────────────────────────┐                 │
                         │   │     Call OpenAI API         │                 │
                         │   │     getLLMChallenge()       │                 │
                         │   └──────────────┬──────────────┘                 │
                         │                  ▼                                │
                         │   ┌─────────────────────────────┐                 │
                         │   │ Parse Response:             │                 │
                         │   │ • Split by SEPARATOR        │                 │
                         │   │ • Part 1: JSON metadata     │                 │
                         │   │ • Part 2: Solution code     │                 │
                         │   │ • Fix malformed JSON        │                 │
                         │   └──────────────┬──────────────┘                 │
                         │                  ▼                                │
                         │   ┌─────────────────────────────┐                 │
                         │   │ Preview Challenge           │                 │
                         │   │ (Admin reviews before save) │                 │
                         │   └──────────────┬──────────────┘                 │
                         │                  ▼                                │
                         │   ┌─────────────────────────────┐                 │
                         │   │ importAIChallenge():        │                 │
                         │   │ • Create Challenge record   │                 │
                         │   │ • Link Topics (M:M)         │                 │
                         │   │ • Link Languages (M:M)      │                 │
                         │   │ • Link Frameworks (M:M)     │                 │
                         │   │ • Link Packages (M:M)       │                 │
                         │   │ • Link Tags (M:M)           │                 │
                         │   │ • Set creator (current user)│                 │
                         │   │ • Store completion_id       │                 │
                         │   │ • Store ai_model used       │                 │
                         │   └──────────────┬──────────────┘                 │
                         │                  ▼                                │
                         │   ┌─────────────────────────────┐                 │
                         │   │ Optional: Generate banner   │                 │
                         │   │ via DALL·E API              │                 │
                         │   └─────────────────────────────┘                 │
                         └───────────────────────────────────────────────────┘
```

---

## API & External Integrations

### OpenAI Integration

**Configuration (`config/openai.php`):**
```php
'api_key' => env('OPENAI_API_KEY'),
'organization' => env('OPENAI_ORGANIZATION'),
'request_timeout' => env('OPENAI_REQUEST_TIMEOUT', 30),
```

**Environment Variables:**
```env
OPENAI_API_KEY=sk-...
OPENAI_MODEL=gpt-4
OPENAI_CODE_SEPARATOR=%%%CODE%%%
OPENAI_DALLE_CHALLENGE_PROMPT_BASE_TEXT=...
```

### Google OAuth

**Socialite Configuration:**
- Redirect: `/login/google`
- Callback: `/login/google/redirect`
- Scopes: email, profile

---

## Security Considerations

1. **Authentication**: Multi-layer with Jetstream + Sanctum sessions
2. **Authorization**: Role-based middleware (admin/recruiter gates)
3. **Data Protection**: Solution code stripped for non-admin users
4. **Input Validation**: Livewire validation rules on all inputs
5. **Route Encoding**: Base64 URL-safe encoding for sensitive params
6. **CSRF Protection**: Laravel's built-in token verification
7. **XSS Prevention**: Blade auto-escaping + manual sanitization

---

## Development Workflow

### Local Setup

```bash
# Clone & install
composer install
npm install

# Environment
cp .env.example .env
php artisan key:generate

# Database
php artisan migrate --seed

# Development
npm run dev           # Vite HMR
php artisan serve     # Laravel server
```

### Build & Deploy

```bash
npm run build         # Production assets
php artisan optimize  # Route/config caching
```

### Code Quality

```bash
./vendor/bin/pint     # PHP code style
./vendor/bin/phpunit  # Test suite
```

---

## Key Differentiators

1. **AI-First Architecture**: OpenAI GPT deeply integrated for challenge generation, chatbot assistance, and performance feedback
2. **Real-Time UX**: Livewire 3 enables SPA-like experience without complex JavaScript
3. **Gamification Engine**: XP, bonuses, and leaderboards drive candidate engagement
4. **Flexible Prompt System**: Admin-controlled templates with dynamic data injection
5. **Comprehensive Analytics**: Multi-dimensional performance tracking and comparison
6. **Enterprise-Ready Auth**: Role-based access, OAuth, and session management

---

## Target Audience Benefits

### For Tech Recruiters
- Automated initial screening saves 70%+ interview time
- Consistent, unbiased challenge delivery
- AI-generated insights on candidate performance
- Scalable to thousands of candidates

### For Talent Hunters
- Technical skill validation before referral
- Portfolio of completed challenges as proof of skill
- Gamified experience improves candidate engagement
- Real-time feedback accelerates talent matching

---

## Version Information

- **Laravel Version**: 10.x (LTS)
- **PHP Version**: 8.1+
- **Node.js**: 18+ recommended
- **Database**: MySQL 8.0+ / PostgreSQL 13+

---

*Document generated for blog post creation — AI Interviewer Platform*
