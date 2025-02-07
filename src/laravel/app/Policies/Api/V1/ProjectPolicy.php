<?php

namespace App\Policies\Api\V1;

use App\Models\User;
use App\Models\Project;
use App\Models\Permission;

class ProjectPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * @param User $user
     * @param Project $project
     * @return bool
     */
    public function update(User $user, Project $project): bool
    {
        if($user->id == $project->user_id)
        {
            return $user->tokenCan(Permission::API_V1_UPDATE_OWN_PROJECTS);
        } else
        {
            return $user->tokenCan(Permission::API_V1_UPDATE_ANY_PROJECTS);
        }
    }

    /**
     * @param User $user
     * @param Project $project
     * @return bool
     */
    public function show(User $user, Project $project): bool
    {
        if($user->id == $project->user_id)
        {
            return $user->tokenCan(Permission::API_V1_SEE_OWN_PROJECTS);
        } else
        {
            return $user->tokenCan(Permission::API_V1_SEE_ALL_PROJECTS);
        }
    }

    /**
     * @param User $user
     * @param Project $project
     * @return bool
     */
    public function delete(User $user, Project $project): bool
    {
        if($user->id == $project->user_id)
        {
            return $user->tokenCan(Permission::API_V1_DELETE_OWN_PROJECTS);
        } else
        {
            return $user->tokenCan(Permission::API_V1_DELETE_ANY_PROJECTS);
        }
    }

    /**
     * @param User $user
     * @param Project $project
     * @return bool
     */
    public function store(User $user, Project $project): bool
    {
        return $user->tokenCan(Permission::API_V1_CREATE_PROJECT);
    }
}
