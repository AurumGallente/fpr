<?php

namespace App\Policies\Api\V1;

use App\Models\Permission;
use App\Models\Project;
use App\Models\User;

class TextPolicy
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
    public function index(User $user, Project $project): bool
    {
        $canSeeThisProject = $this->checkProject($user, $project);
        return ($user->tokenCan(Permission::API_V1_SHOW_ALL_TEXTS) && $canSeeThisProject);
    }

    /**
     * @param User $user
     * @param Project $project
     * @return bool
     */
    public function show(User $user, Project $project): bool
    {
        $canSeeThisProject = $this->checkProject($user, $project);
        return ($user->tokenCan(Permission::API_V1_SHOW_TEXT) && $canSeeThisProject);
    }

    /**
     * @param User $user
     * @param Project $project
     * @return bool
     */
    private function checkProject(User $user, Project $project):bool
    {
        if($user->id == $project->user_id)
        {
            return  $user->tokenCan(Permission::API_V1_SEE_OWN_PROJECTS);
        } else
        {
            return  $user->tokenCan(Permission::API_V1_SEE_ALL_PROJECTS);
        }
    }
}
