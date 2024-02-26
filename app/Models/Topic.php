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

    /*
        Recursive methods for retrieving Topics tree
    */

    public function children()
    {
        return $this->hasMany(Topic::class, 'parent_id');
    }
    
    public function parent()
    {
        return $this->belongsTo(Topic::class, 'parent_id');
    }

    public static function getTree()
    {
        return static::with('children', 'challenges:id,title')
            ->withCount('challenges')
            ->whereNull('parent_id')
            ->get();
    }

    public function recursiveChildren()
    {
        return $this->children()
            ->with('recursiveChildren');
    }
}
