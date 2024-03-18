<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

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

    /**
     * Returns the complete Topic recursive tree
     *
     * @return mixed
     */
    public static function getTree()
    {
        return static::with('children', 'challenges:id,title')
            ->withCount('challenges')
            ->whereNull('parent_id')
            ->get();
    }

    /**
     * Returns the recursive children of a specific Topic model
     *
     * @return mixed
     */
    public function recursiveChildren()
    {
        return $this->children()
            ->with('recursiveChildren');
    }

    /**
     * Gets a collection of top level topics
     * with 'challenges_count' according to 'difficulty' ('easy|medium|hard')
     *
     * @param string $selected_difficulty
     * @return Collection
     */
    public static function byDifficultyWithCountChallenges(string $selected_difficulty, bool $ordered = true): Collection
    {
        $difficulty_id = Difficulty::select('id', 'name')->where('name', '=', strtolower($selected_difficulty))->first()->id;
        $builder = static::where('parent_id', '=', null)
            ->withCount(['challenges' => function ($query) use ($difficulty_id) {
                $query->where('difficulty_id', $difficulty_id);
            }]);
        return $ordered ? $builder->orderBy('name', 'asc')->get() : $builder->get();
    }
}
