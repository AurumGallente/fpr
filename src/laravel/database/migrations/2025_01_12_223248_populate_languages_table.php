<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Language;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $data =
            [
                [
                    'language' => "Czech",
                    'code' => "cs",
                ],
                [
                    'language' => "Danish",
                    'code' => "da",
                ],
                [
                    'language' => "Dutch",
                    'code' => "nl",
                ],
                [
                    'language' => "English",
                    'code' => "en",
                ],
                [
                    'language' => "Estonian",
                    'code' => "et",
                ],
                [
                    'language' => "Finnish",
                    'code' => "fi",
                ],
                [
                    'language' => "French",
                    'code' => "fr",
                ],
                [
                    'language' => "German",
                    'code' => "de",
                ],
                [
                    'language' => "Greek",
                    'code' => "el",
                ],
                [
                    'language' => "Italian",
                    'code' => "it",
                ],
                [
                    'language' => "Norwegian",
                    'code' => "no",
                ],
                [
                    'language' => "Polish",
                    'code' => "pl",
                ],
                [
                    'language' => "Portuguese",
                    'code' => "pt",
                ],
                [
                    'language' => "Russian",
                    'code' => "ru",
                ],
                [
                    'language' => "Slovene",
                    'code' => "sl",
                ],
                [
                    'language' => "Spanish",
                    'code' => "es",
                ],
                [
                    'language' => "Swedish",
                    'code' => "sv",
                ],
                [
                    'language' => "Turkish",
                    'code' => "tr",
                ],
            ];

        foreach ($data as $row) {
            Language::createOrRestore(
                [
                    'language' => $row['language'],
                    'code' => $row['code']
                ]
            );
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Language::query()->forceDelete();
    }
};
