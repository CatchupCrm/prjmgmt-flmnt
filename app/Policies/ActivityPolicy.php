<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActivityPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     *
     * @return Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->can('List activities');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Activity $activity
     *
     * @return Response|bool
     */
    public function view(User $user, Activity $activity)
    {
        return $user->can('View activity');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     *
     * @return Response|bool
     */
    public function create(User $user)
    {
        return $user->can('Create activity');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Activity $activity
     *
     * @return Response|bool
     */
    public function update(User $user, Activity $activity)
    {
        return $user->can('Update activity');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Activity $activity
     *
     * @return Response|bool
     */
    public function delete(User $user, Activity $activity)
    {
        return $user->can('Delete activity');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Activity $activity
     *
     * @return Response|bool
     */
    public function restore(User $user, Activity $activity) {}

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Activity $activity
     *
     * @return Response|bool
     */
    public function forceDelete(User $user, Activity $activity) {}
}
