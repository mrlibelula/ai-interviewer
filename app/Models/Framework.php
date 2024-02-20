<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Framework extends Model
{
    use HasFactory;

    protected $table = 'frameworks';
    protected $guarded = [];

    public function challenges()
    {
        return $this->belongsToMany(Challenge::class);
    }
}
