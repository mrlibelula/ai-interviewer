<?php

namespace App\Models;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Challenge extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'challenges';
    protected $guarded = [];
    protected $dates = ['deleted_at'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'challenge_solver')
            ->withPivot('solved_at', 'current_time_limit', 'solution_code', 'attempts', 'bonus_xp', 'openai_chat_settings', 'observations')
            ->withTimestamps();
    }

    public function difficulty()
    {
        return $this->belongsTo(Difficulty::class, 'difficulty_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function visibility()
    {
        return $this->belongsTo(Visibility::class, 'visibility_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class)->withPivot('created_at')->orderBy('challenge_tag.created_at', 'DESC');
    }

    public function languages()
    {
        return $this->belongsToMany(Language::class);
    }

    public function frameworks()
    {
        return $this->belongsToMany(Framework::class);
    }

    public function packages()
    {
        return $this->belongsToMany(Package::class);
    }

    public function topics()
    {
        return $this->belongsToMany(Topic::class);
    }

    public function creators()
    {
        return $this->belongsToMany(User::class);
    }

    /*
        ACTIONS API
    */

    public function addTag(Tag $tag)
    {
        return $this->tags()->attach($tag);
    }

    public function removeTag(Tag $tag): bool
    {
        return $this->tags()->detach($tag);
    }

    public function addLanguage(Language $lang)
    {
        return $this->languages()->attach($lang);
    }

    public function removeLanguage(Language $lang): bool
    {
        return $this->languages()->detach($lang);
    }

    public function addFramework(Framework $frame)
    {
        return $this->frameworks()->attach($frame);
    }

    public function removeFramework(Framework $frame): bool
    {
        return $this->frameworks()->detach($frame);
    }

    public function addPackage(Package $package)
    {
        return $this->packages()->attach($package);
    }

    public function removePackage(Package $package): bool
    {
        return $this->packages()->detach($package);
    }

    public function addTopic(Topic $topic)
    {
        return $this->topics()->attach($topic);
    }

    public function removeTopic(Topic $topic): bool
    {
        return $this->topics()->detach($topic);
    }

    public function addCreator(User $user)
    {
        return $this->creators()->attach($user);
    }

    public function removeCreator(User $user): bool
    {
        return $this->creators()->detach($user);
    }

    /*
        Static methods
    */

    public static function byDifficulty(string $selected_difficulty, bool $ordered = true): Collection
    {
        $builder = static::whereHas('difficulty', function($query) use($selected_difficulty) {
            $query->where('name', $selected_difficulty);
        });
        return $ordered ? $builder->orderBy('title', 'asc')->get() : $builder->get();
    }

    // public static function byDifficulty(string $selected_difficulty, bool $ordered = true): Collection
    // {
    //     $difficulty_id = Difficulty::select('id', 'name')->where('name', '=', strtolower($selected_difficulty))->first()->id;
    //     $builder = static::select('id', 'title')
    //         ->where('difficulty_id', '=', $difficulty_id);
    //     return $ordered ? $builder->orderBy('title', 'asc')->get() : $builder->get();
    // }

    public static function byDifficultyAndTopic(string $selected_difficulty, int $topic_id, array $return_cols = ['id', 'title'], bool $ordered = true, string $order_by = 'title', string $order = 'asc'): Collection
    {
        $difficulty_id = Difficulty::select('id', 'name')->where('name', '=', strtolower($selected_difficulty))->first()->id;
        $builder = static::select(...$return_cols)
            ->whereHas('difficulty', function ($q) use ($difficulty_id) {
                $q->whereId($difficulty_id);
            })
            ->whereHas('topics', function ($q) use ($topic_id) {
                $q->whereIn('topic_id', [$topic_id]);
            });
        return $ordered ? $builder->orderBy($order_by, $order)->get() : $builder->get();
    }
}
