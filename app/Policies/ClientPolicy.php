<?php

namespace App\Policies;

use App\Models\Client;
use Modules\User\Entities\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClientPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the client.
     *
     * @param  \Modules\User\Entities\User  $user
     * @param  \App\Models\Client  $client
     * @return mixed
     */
    public function view(User $user, Client $client)
    {
        return $user->hasPermissionTo('clients.view');
    }

    /**
     * Determine whether the user can create clients.
     *
     * @param  \Modules\User\Entities\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('clients.create');
    }

    /**
     * Determine whether the user can update the client.
     *
     * @param  \Modules\User\Entities\User  $user
     * @param  \App\Models\Client  $client
     * @return mixed
     */
    public function update(User $user, Client $client)
    {
        return $user->hasPermissionTo('clients.update');
    }

    /**
     * Determine whether the user can delete the client.
     *
     * @param  \Modules\User\Entities\User  $user
     * @param  \App\Models\Client  $client
     * @return mixed
     */
    public function delete(User $user, Client $client)
    {
        return $user->hasPermissionTo('clients.delete');
    }

    /**
     * Determine whether the user can list clients.
     *
     * @param  \Modules\User\Entities\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return $user->hasPermissionTo('clients.view');
    }

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('clients.view');
    }
}