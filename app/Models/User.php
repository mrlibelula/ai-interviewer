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
use stdClass;

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
        'options',
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
     * Challenges that the User attempted or solved
     */
    public function challenges()
    {
        return $this->belongsToMany(Challenge::class, 'challenge_solver')
            ->withPivot('solved_at', 'current_time_limit', 'solution_code', 'attempts', 'bonus_xp', 'openai_chat_settings', 'observations')
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

    /**
     * Updates the "Solver" pivot table extra attributes 
     *
     * @param Challenge $challenge
     * @return integer
     */
    public function updateChallenge(Challenge $challenge, array $attributes): int
    {
        return $this->challenges()->updateExistingPivot($challenge, $attributes);
    }

    /**
     * Get user's options
     *
     * @return stdClass|null
     */
    public function options(): ?stdClass
    {
        $options = json_decode($this->options);
        if (!property_exists($options, 'metrics')) {
            $options->metrics = (object)[
                'performance' => [
                    'ai_problem_specific_feedback_history' => [],
                    'ai_optimization_feedback_history' => [],
                    'ai_best_practices_feedback_history' => [],
                ],
            ];
            $this->options = json_encode($options);
            $this->save();
        }

        return json_decode($this->options) ?: new stdClass();
    }

    
    // /**
    //  * Method to update a specific branch of the options JSON
    //  *
    //  * @param string $branch
    //  * @param mixed $key
    //  * @param mixed $value
    //  * @return boolean
    //  */
    // public function updateMetricsPerformanceOption(string $branch, mixed $key, mixed $value): bool
    // {
    //     $options = $this->options();

    //     // Navigate to the specific branch and key, and update the value
    //     if (isset($options->metrics->performance->$branch)) {
    //         $options->metrics->performance->$branch->$key = $value;
    //     } else {
    //         // Optionally handle the case where the branch/key doesn't exist
    //         $options->metrics->performance->$branch = (object)[$key => $value];
    //     }

    //     // Encode the updated options back to JSON and update the database
    //     $this->options = json_encode($options);
    //     return $this->save();
    // }

    /**
     * Method to append data to an array within a specific branch
     * for the User's options
     *
     * @param string $branch
     * @param mixed $value
     * @return boolean
     */
    public function appendToMetricsPerformanceFeedbackHistoryArray(string $branch, mixed $value): bool
    {
        $options = $this->options();

        // Check if the branch exists and is an array
        if (isset($options->metrics->performance->$branch) && is_array($options->metrics->performance->$branch)) {
            $options->metrics->performance->$branch[] = $value;
        } else {
            // Initialize the branch as an array if it doesn't exist
            $options->metrics->performance->$branch = [$value];
        }

        // Encode the updated options back to JSON and update the database
        $this->options = json_encode($options);
        return $this->save();
    }

    
    /**
     * Method to remove an item from an array within a specific branch based on 'id'
     *
     * @param string $branch
     * @param integer $id
     * @return boolean
     */
    public function removeFromOptionArray(string $branch, int $id): bool
    {
        $options = $this->options();

        // Check if the branch exists and is an array
        if (isset($options->metrics->performance->$branch) && is_array($options->metrics->performance->$branch)) {
            // Filter out the item with the matching 'id'
            $options->metrics->performance->$branch = array_filter(
                $options->metrics->performance->$branch, 
                fn ($item) => $item->id !== $id
            );

            // Reindex the array to maintain numeric keys
            $options->metrics->performance->$branch = array_values($options->metrics->performance->$branch);
        }

        // Encode the updated options back to JSON and update the database
        $this->options = json_encode($options);
        return $this->save();
    }

    
    /**
     * Method to empty an array within a specific branch
     *
     * @param string $branch
     * @return boolean
     */
    public function emptyOptionArray(string $branch): bool
    {
        $options = $this->options();

        // Check if the branch exists and is an array
        if (isset($options->metrics->performance->$branch) && is_array($options->metrics->performance->$branch)) {
            // Set the array to empty
            $options->metrics->performance->$branch = [];
        }

        // Encode the updated options back to JSON and update the database
        $this->options = json_encode($options);
        return $this->save();
    }

    /**
     * Method to calculate and return the next available 'id' in a specific feedback history array
     *
     * @param string $branch
     * @return integer
     */
    public function getNextFeedbackId(string $branch): int
    {
        $options = $this->options();

        // Check if the branch exists and is an array
        if (isset($options->metrics->performance->$branch) && is_array($options->metrics->performance->$branch)) {
            $current_ids = array_map(fn ($item) => $item->id ?? 0, $options->metrics->performance->$branch);
            // Return the next incremented id
            return empty($current_ids) ? 1 : max($current_ids) + 1;
        }

        // If the branch doesn't exist or isn't an array, return 1 as the starting id
        return 1;
    }
}
