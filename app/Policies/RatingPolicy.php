<?php

namespace App\Policies;

use App\Models\Rating;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RatingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function update(User $user, Rating $rating)
{
    return $user->id === $rating->user_id;
}

public function delete(User $user, Rating $rating)
{
    return $user->id === $rating->user_id;
}
}