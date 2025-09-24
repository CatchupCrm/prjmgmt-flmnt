<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class TicketPolicy
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
        return $user->can('List tickets');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User   $user
     * @param Ticket $ticket
     *
     * @return Response|bool
     */
    public function view(User $user, Ticket $ticket)
    {
        return $user->can('View ticket')
            && (
                $ticket->owner_id === $user->id
                || $ticket->responsible_id === $user->id
                || $ticket->project->users()->where('users.id', auth()->user()->id)->count()
                || $ticket->project->owner_id === $user->id
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
        return $user->can('Create ticket');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User   $user
     * @param Ticket $ticket
     *
     * @return Response|bool
     */
    public function update(User $user, Ticket $ticket)
    {
        return $user->can('Update ticket')
            && (
                $ticket->owner_id === $user->id
                || $ticket->responsible_id === $user->id
                || $ticket->project->users()->where('users.id', auth()->user()->id)->count()
                || $ticket->project->owner_id === $user->id
            );
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User   $user
     * @param Ticket $ticket
     *
     * @return Response|bool
     */
    public function delete(User $user, Ticket $ticket)
    {
        return $user->can('Delete ticket');
    }
}
