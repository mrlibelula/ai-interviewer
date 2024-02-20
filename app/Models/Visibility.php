<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visibility extends Model
{
    use HasFactory;

    protected $table = 'visibilities';
    protected $guarded = [];

    public function challenges()
    {
        return $this->hasMany(Challenge::class);
    }
}
