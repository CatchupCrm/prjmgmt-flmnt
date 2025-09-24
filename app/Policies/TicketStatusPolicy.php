<?php

namespace App\Policies;

use App\Models\TicketStatus;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class TicketStatusPolicy
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
        return $user->can('List ticket statuses');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User         $user
     * @param TicketStatus $ticketStatus
     *
     * @return Response|bool
     */
    public function view(User $user, TicketStatus $ticketStatus)
    {
        return $user->can('View ticket status');
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
        return $user->can('Create ticket status');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User         $user
     * @param TicketStatus $ticketStatus
     *
     * @return Response|bool
     */
    public function update(User $user, TicketStatus $ticketStatus)
    {
        return $user->can('Update ticket status');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User         $user
     * @param TicketStatus $ticketStatus
     *
     * @return Response|bool
     */
    public function delete(User $user, TicketStatus $ticketStatus)
    {
        return $user->can('Delete ticket status');
    }
}
