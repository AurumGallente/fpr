<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\Project;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $languages = Language::all();

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

        for($i=1; $i<=40; $i++)
        {
            $language = $languages->random();
            $locale = $langMap[$language->code];
            $faker = Faker::create($locale);
            $project = new Project();
            $project->name = $faker->words(rand(3, 5), true);
            $project->user_id = $users->random()->id;
            $project->language_id = $language->id;
            $project->description = $faker->paragraph(rand(1, 5), true);
            $project->save();
        }
    }
}
