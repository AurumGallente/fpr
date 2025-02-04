<?php

namespace App\Policies\Api\V1;

use App\Models\User;
use App\Models\Project;

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
        return ($user->id == $project->user_id);
    }

    /**
     * @param User $user
     * @param Project $project
     * @return bool
     */
    public function show(User $user, Project $project): bool
    {
        return ($user->id == $project->user_id);
    }

    /**
     * @param User $user
     * @param Project $project
     * @return bool
     */
    public function delete(User $user, Project $project): bool
    {
        return ($user->id == $project->user_id);
    }

    /**
     * @param User $user
     * @param Project $project
     * @return bool
     */
    public function store(User $user, Project $project): bool
    {
        // no logic yet
        return true;
    }
}
