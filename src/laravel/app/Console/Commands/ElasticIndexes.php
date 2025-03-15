<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Migrations\Migration;
use PDPhilip\Elasticsearch\Schema\Schema;
use PDPhilip\Elasticsearch\Schema\IndexBlueprint;
use PDPhilip\Elasticsearch\Schema\AnalyzerBlueprint;
use Illuminate\Support\Facades\Http;
use Elastic\Elasticsearch\ClientBuilder;

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


        $array = [
            'cs_text_index'=>'czech',
            'da_text_index'=>'danish',
            'nl_text_index'=>'dutch',
            'en_text_index'=>'english',
            'et_text_index'=>'estonian',
            'fi_text_index'=>'finnish',
            'de_text_index'=>'german',
            'fr_text_index'=>'french',
            'el_text_index'=>'greek',
            'it_text_index'=>'italian',
            'no_text_index'=>'norwegian',
            'pl_text_index'=>'standard',
            'pt_text_index'=>'portuguese',
            'sl_text_index'=>'standard',
            'es_text_index'=>'spanish',
            'tr_text_index'=>'turkish',
            'ru_text_index'=>'russian',
            'sv_text_index' =>'swedish',
        ];

        if(!env('ES_HOSTS')){
            $output->writeln("<info>ES is not configured. Aborting.</info>");
            return;
        }

        do {
            $output->writeln("<info>Check if ES is ready to accept requests...</info>");
            $response = Http::get(env('ES_HOSTS'));
            sleep(5);
        } while ($response->failed());

        $output->writeln("<info>It is ready.</info>");

        $shards = env('ES_NUMBER_OF_SHARDS_PER_INDEX');
        $replicas = env('ES_NUMBER_OF_REPLICAS_PER_INDEX');

        foreach($array as $key => $analyzer)
        {
            Schema::createIfNotExists($key, static function (IndexBlueprint $index) use ($analyzer, $shards, $replicas) {
                $index->settings('number_of_shards', $shards);
                $index->settings('number_of_replicas', $replicas);
                $index->integer('external_id');
                $index->integer('project_id');
                $index->text('original_content');
                $index->text('normalized_content')->analyzer($analyzer);
                $index->date('created_at');
            });
            $output->writeln("<info>$analyzer index created.</info>");
        }

        Schema::createIfNotExists('chunks', static function (IndexBlueprint $index) use ($shards, $replicas) {
            $index->settings('number_of_shards', $shards);
            $index->settings('number_of_replicas', $replicas);
            $index->integer('external_id');
            $index->text('original_content')->analyzer('standard');
            $index->date('created_at');
        });
        $output->writeln("<info>'chunks' index created.</info>");
    }
}
