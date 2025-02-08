<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Permission extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;


    const API_V1_CREATE_PROJECT = 'api_v1_create_project';
    const API_V1_SEE_OWN_PROJECTS = 'api_v1_see_own_projects';
    const API_V1_UPDATE_OWN_PROJECTS = 'api_v1_update_own_projects';
    const API_V1_DELETE_OWN_PROJECTS = 'api_v1_delete_own_projects';
    const API_V1_SEE_ALL_PROJECTS = 'api_v1_see_all_projects';
    const API_V1_UPDATE_ANY_PROJECTS = 'api_v1_update_any_projects';
    const API_V1_DELETE_ANY_PROJECTS = 'api_v1_delete_any_projects';
    const API_V1_SHOW_ALL_TEXTS = 'api_v1_show_all_texts';
    const API_V1_SHOW_TEXT = 'api_v1_show_text';


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'permission_user', 'permission_id', 'user_id');
    }
}
