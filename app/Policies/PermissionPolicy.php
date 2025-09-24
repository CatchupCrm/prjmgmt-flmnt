<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class PermissionPolicy
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
        return $user->can('List permissions');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User       $user
     * @param Permission $permission
     *
     * @return Response|bool
     */
    public function view(User $user, Permission $permission)
    {
        return $user->can('View permission');
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
        return $user->can('Create permission');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User       $user
     * @param Permission $permission
     *
     * @return Response|bool
     */
    public function update(User $user, Permission $permission)
    {
        return $user->can('Update permission');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User       $user
     * @param Permission $permission
     *
     * @return Response|bool
     */
    public function delete(User $user, Permission $permission)
    {
        return $user->can('Delete permission');
    }
}
