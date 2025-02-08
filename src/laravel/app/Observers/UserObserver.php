<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Permission;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $user->permissions()->saveMany($this->getDefaultPermissions());
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        foreach($user->tokens()->get() as $token)
        {
            $token->revoke();
        }
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        $user->permissions()->sync([]);
        foreach($user->tokens()->get() as $token)
        {
            $token->revoke();
        }
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        $user->permissions()->saveMany($this->getDefaultPermissions());
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }

    /**
     * @return array
     */
    private function getDefaultPermissions(): array
    {
        $createProjectPermission = Permission::where('name', Permission::API_V1_CREATE_PROJECT)->first();
        $seeOwnProjectPermission = Permission::where('name', Permission::API_V1_SEE_OWN_PROJECTS)->first();
        $updateOwnProjectPermission = Permission::where('name', Permission::API_V1_UPDATE_OWN_PROJECTS)->first();
        $deleteOwnProjectPermission = Permission::where('name', Permission::API_V1_DELETE_OWN_PROJECTS)->first();

        return [
            $createProjectPermission,
            $seeOwnProjectPermission,
            $updateOwnProjectPermission,
            $deleteOwnProjectPermission
        ];
    }
}
