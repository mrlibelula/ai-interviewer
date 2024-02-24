<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

    protected $table = 'topics';
    protected $guarded = [];

    public function challenges()
    {
        return $this->belongsToMany(Challenge::class);
    }
}
