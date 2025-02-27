<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Migrations\Migration;
use PDPhilip\Elasticsearch\Schema\Schema;
use PDPhilip\Elasticsearch\Schema\IndexBlueprint;
use PDPhilip\Elasticsearch\Schema\AnalyzerBlueprint;
use Illuminate\Support\Facades\Http;

class ElasticIndexes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'es:create-index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates indexes for Elasticsearch';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $output = new \Symfony\Component\Console\Output\ConsoleOutput();

        do {
            $output->writeln("<info>Check if ES is ready to accept requests...</info>");
            $response = Http::get(env('ES_HOSTS'));
            sleep(5);
        } while ($response->failed());

        $output->writeln("<info>It is ready.</info>");

        Schema::createIfNotExists('cs_text_index', function (IndexBlueprint $index) {
            $index->integer('external_id');
            $index->text('content')->analyzer('czech');
            $index->date('created_at');
        });
        $output->writeln("<info>czech index created.</info>");
        Schema::createIfNotExists('da_text_index', function (IndexBlueprint $index) {
            $index->integer('external_id');
            $index->text('content')->analyzer('danish');
            $index->date('created_at');
        });
        $output->writeln("<info>dutch index created.</info>");
        Schema::createIfNotExists('nl_text_index', function (IndexBlueprint $index) {
            $index->integer('external_id');
            $index->text('content')->analyzer('dutch');
            $index->date('created_at');
        });
        $output->writeln("<info>english index created.</info>");
        Schema::createIfNotExists('en_text_index', function (IndexBlueprint $index) {
            $index->integer('external_id');
            $index->text('content')->analyzer('english');
            $index->date('created_at');
        });
        $output->writeln("<info>estonian index created.</info>");
        Schema::createIfNotExists('et_text_index', function (IndexBlueprint $index) {
            $index->integer('external_id');
            $index->text('content')->analyzer('estonian');
            $index->date('created_at');
        });
        $output->writeln("<info>finnish index created.</info>");
        Schema::createIfNotExists('fi_text_index', function (IndexBlueprint $index) {
            $index->integer('external_id');
            $index->text('content')->analyzer('finnish');
            $index->date('created_at');
        });
        $output->writeln("<info>french index created.</info>");
        Schema::createIfNotExists('fr_text_index', function (IndexBlueprint $index) {
            $index->integer('external_id');
            $index->text('content')->analyzer('french');
            $index->date('created_at');
        });
        $output->writeln("<info>german index created.</info>");
        Schema::createIfNotExists('de_text_index', function (IndexBlueprint $index) {
            $index->integer('external_id');
            $index->text('content')->analyzer('german');
            $index->date('created_at');
        });
        $output->writeln("<info>greek index created.</info>");
        Schema::createIfNotExists('el_text_index', function (IndexBlueprint $index) {
            $index->integer('external_id');
            $index->text('content')->analyzer('greek');
            $index->date('created_at');
        });
        $output->writeln("<info>italian index created.</info>");
        Schema::createIfNotExists('it_text_index', function (IndexBlueprint $index) {
            $index->integer('external_id');
            $index->text('content')->analyzer('italian');
            $index->date('created_at');
        });
        $output->writeln("<info>norwegian index created.</info>");
        Schema::createIfNotExists('no_text_index', function (IndexBlueprint $index) {
            $index->integer('external_id');
            $index->text('content')->analyzer('norwegian');
            $index->date('created_at');
        });
        $output->writeln("<info>polish (standard) index created.</info>");
        Schema::createIfNotExists('pl_text_index', function (IndexBlueprint $index) {
            $index->integer('external_id');
            $index->text('content')->analyzer('standard');
            $index->date('created_at');
        });
        $output->writeln("<info>portuguese index created.</info>");
        Schema::createIfNotExists('pt_text_index', function (IndexBlueprint $index) {
            $index->integer('external_id');
            $index->text('content')->analyzer('portuguese');
            $index->date('created_at');
        });
        $output->writeln("<info>slovene (standard) index created.</info>");
        Schema::createIfNotExists('sl_text_index', function (IndexBlueprint $index) {
            $index->integer('external_id');
            $index->text('content')->analyzer('standard');
            $index->date('created_at');
        });
        $output->writeln("<info>spanish index created.</info>");
        Schema::createIfNotExists('es_text_index', function (IndexBlueprint $index) {
            $index->integer('external_id');
            $index->text('content')->analyzer('spanish');
            $index->date('created_at');
        });
        $output->writeln("<info>turkish index created.</info>");
        Schema::createIfNotExists('tr_text_index', function (IndexBlueprint $index) {
            $index->integer('external_id');
            $index->text('content')->analyzer('turkish');
            $index->date('created_at');
        });
    }
}
