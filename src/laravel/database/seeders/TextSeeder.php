<?php

namespace Database\Seeders;

use App\Helpers\FakerHelper;
use App\Models\Language;
use App\Models\Project;
use App\Models\Text;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TextSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $users = User::all();
        $languages = Language::all();
        $projects = Project::all();

        $langMap = FakerHelper::$map;

        for($i=1; $i<=50; $i++)
        {
            $project = $projects->random();
            $language = $project->language;
            $text = new Text();
            $helper = new FakerHelper($language->code);
            $text->content = $helper->getText();
            $previousText = $project->lastText();
            $text->version = $previousText ? $previousText->version + 1 : 1;
            $text->user_id = $users->random()->id;
            $text->project_id = $project->id;
            $text->save();
        }
    }
}
