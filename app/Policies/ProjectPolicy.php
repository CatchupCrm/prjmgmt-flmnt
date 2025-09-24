<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
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
        return $user->can('List projects');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Project $project
     *
     * @return Response|bool
     */
    public function view(User $user, Project $project)
    {
        return $user->can('View project')
            && (
                $project->owner_id === $user->id
                || $project->users()->where('users.id', $user->id)->count()
            );
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
        return $user->can('Create project');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Project $project
     *
     * @return Response|bool
     */
    public function update(User $user, Project $project)
    {
        return $user->can('Update project')
            && (
                $project->owner_id === $user->id
                || $project->users()->where('users.id', $user->id)
                    ->where('role', config('system.projects.affectations.roles.can_manage'))
                    ->count()
            );
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Project $project
     *
     * @return Response|bool
     */
    public function delete(User $user, Project $project)
    {
        return $user->can('Delete project');
    }
}
