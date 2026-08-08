<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ChallengeSolver extends Pivot
{
    protected $table = 'challenge_solver';

    protected $casts = [
        'openai_chat_settings' => 'array',
        'observations' => 'array',
        'solved_at' => 'datetime',
    ];
}
