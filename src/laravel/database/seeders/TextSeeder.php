<?php

namespace Database\Seeders;

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

        $langMap = [
            'cs' => 'cs_CZ',
            'da' => 'da_DK',
            'nl' => 'nl_NL',
            'en' => 'en_US',
            'et' => 'et_EE',
            'fi' => 'fi_FI',
            'fr' => 'fr_FR',
            'de' => 'de_DE',
            'el' => 'el_GR',
            'it' => 'it_IT',
            'no' => 'nl_NL',
            'pl' => 'pl_PL',
            'pt' => 'pt_PT',
            'ru' => 'ru_RU',
            'sl' => 'sl_SI',
            'es' => 'es_ES',
            'sv' => 'sv_SE',
            'tr' => 'tr_TR',
        ];

        for($i=1; $i<=300; $i++)
        {
            $project = $projects->random();
            $language = $project->language;
            $locale = $langMap[$language->code];
            $faker = Faker::create($locale);
            $text = new Text();
            $text->content = $faker->paragraphs(rand(3,30), true);
            $previousText = $project->lastText();
            $text->version = $previousText ? $previousText->version + 1 : 1;
            $text->user_id = $users->random()->id;
            $text->project_id = $project->id;
            $text->save();
        }
    }
}
