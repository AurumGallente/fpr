<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Permission;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $array = [
            ['name' => Permission::API_V1_CREATE_PROJECT, 'label' => 'create a project'],
            ['name' => Permission::API_V1_SEE_OWN_PROJECTS, 'label' => 'see own projects'],
            ['name' => Permission::API_V1_UPDATE_OWN_PROJECTS, 'label' => 'update own projects'],
            ['name' => Permission::API_V1_DELETE_OWN_PROJECTS, 'label' => 'delete own projects'],
            ['name' => Permission::API_V1_SEE_ALL_PROJECTS, 'label' => 'see all projects'],
            ['name' => Permission::API_V1_UPDATE_ANY_PROJECTS, 'label' => 'update any projects'],
            ['name' => Permission::API_V1_DELETE_ANY_PROJECTS, 'label' => 'delete any projects'],
        ];
        foreach ($array as $arr) {
            $permission = new Permission();
            $permission->name = $arr['name'];
            $permission->label = $arr['label'];
            $permission->save();
        }
        $createProjectPermission = Permission::where('name', Permission::API_V1_CREATE_PROJECT)->first();
        $seeOwnProjectPermission = Permission::where('name', Permission::API_V1_SEE_OWN_PROJECTS)->first();
        $updateOwnProjectPermission = Permission::where('name', Permission::API_V1_UPDATE_OWN_PROJECTS)->first();
        $deleteOwnProjectPermission = Permission::where('name', Permission::API_V1_DELETE_OWN_PROJECTS)->first();

        $users = User::all();
        foreach ($users as $user)
        {
            $user->permissions()->saveMany([
                $createProjectPermission,
                $seeOwnProjectPermission,
                $updateOwnProjectPermission,
                $deleteOwnProjectPermission
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('permissions')->truncate();
        DB::table('permission_user')->truncate();
    }
};
