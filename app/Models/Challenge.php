<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    use HasFactory;

    protected $table = 'challenges';
    protected $guarded = [];

    public function difficulty()
    {
        return $this->belongsTo(Difficulty::class, 'dificulty_id');
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

    /*
        ACTIONS API
    */

    public function addTag(Tag $tag): Tag
    {
        return $this->tags()->save($tag);
    }

    public function removeTag(Tag $tag): bool
    {
        return $this->tags()->detach($tag);
    }

    public function addLanguage(Language $lang): Language
    {
        return $this->languages()->save($lang);
    }

    public function removeLanguage(Language $lang): bool
    {
        return $this->languages()->detach($lang);
    }

    public function addFramework(Framework $frame): Framework
    {
        return $this->frameworks()->save($frame);
    }

    public function removeFramework(Framework $frame): bool
    {
        return $this->frameworks()->detach($frame);
    }

    public function addPackage(Package $package): Package
    {
        return $this->packages()->save($package);
    }

    public function removePackage(Package $package): bool
    {
        return $this->packages()->detach($package);
    }
}
