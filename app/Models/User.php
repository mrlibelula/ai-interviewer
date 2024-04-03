<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Many-to-many relationship with Challenge
     */
    public function challenges()
    {
        return $this->belongsToMany(Challenge::class, 'challenge_solver')
            ->withPivot('solved_at', 'current_time_limit', 'solution_code', 'tries', 'bonus_xp', 'openai_chat_history', 'observations')
            ->withTimestamps();
    }

    /**
     * Attaches Challenge to User
     *
     * @param Challenge $challenge
     * @param array $attributes
     * @return void
     */
    public function attachChallenge(Challenge $challenge, array $attributes = [])
    {
        return $this->challenges()->attach($challenge, $attributes);
    }

    /**
     * Detaches Challenge from User
     *
     * @param Challenge $challenge
     * @return void
     */
    public function detachChallenge(Challenge $challenge)
    {
        return $this->challenges()->detach($challenge);
    }
}
