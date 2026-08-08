<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enviro extends Model
{
    use HasFactory;

    protected $table = 'enviros';
    protected $guarded = [];

    protected $casts = [
        'prompt' => 'array',
        'openai' => 'array',
        'prompt_templates' => 'array',
    ];
}
