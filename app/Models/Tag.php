<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $table = 'tags';
    protected $guarded = [];

    public function challenges()
    {
        return $this->belongsToMany(Challenge::class);
    }
}
