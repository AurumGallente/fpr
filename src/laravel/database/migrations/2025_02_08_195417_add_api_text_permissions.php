<?php

use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $array = [
            ['name' => Permission::API_V1_SHOW_ALL_TEXTS, 'label' => 'show all texts of a project'],
            ['name' => Permission::API_V1_SHOW_TEXT, 'label' => 'show a text of a project'],
        ];
        foreach ($array as $arr) {
            $permission = new Permission();
            $permission->name = $arr['name'];
            $permission->label = $arr['label'];
            $permission->save();
        }
        $showAllTexts = Permission::where('name', Permission::API_V1_SHOW_ALL_TEXTS)->first();
        $showTheText = Permission::where('name', Permission::API_V1_SHOW_TEXT)->first();


        $users = User::all();
        foreach ($users as $user)
        {
            $user->permissions()->saveMany([
                $showAllTexts,
                $showTheText
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Permission::where('name', Permission::API_V1_SHOW_ALL_TEXTS)->delete();
        Permission::where('name', Permission::API_V1_SHOW_TEXT)->delete();
    }
};
