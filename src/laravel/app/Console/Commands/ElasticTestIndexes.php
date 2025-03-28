<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use PDPhilip\Elasticsearch\Schema\IndexBlueprint;
use PDPhilip\Elasticsearch\Schema\Schema;

class ElasticTestIndexes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'es:create-test-index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates test indexes for Elasticsearch';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {

        $output = new \Symfony\Component\Console\Output\ConsoleOutput();


        $array = [
            'test_cs_text_index'=>'czech',
            'test_da_text_index'=>'danish',
            'test_nl_text_index'=>'dutch',
            'test_en_text_index'=>'english',
            'test_et_text_index'=>'estonian',
            'test_fi_text_index'=>'finnish',
            'test_de_text_index'=>'german',
            'test_fr_text_index'=>'french',
            'test_el_text_index'=>'greek',
            'test_it_text_index'=>'italian',
            'test_no_text_index'=>'norwegian',
            'test_pl_text_index'=>'standard',
            'test_pt_text_index'=>'portuguese',
            'test_sl_text_index'=>'standard',
            'test_es_text_index'=>'spanish',
            'test_tr_text_index'=>'turkish',
            'test_ru_text_index'=>'russian',
            'test_sv_text_index' =>'swedish',
        ];

        if(!env('ES_HOSTS')){
            $output->writeln("<info>ES is not configured. Aborting.</info>");
            return;
        }

        do {
            $output->writeln("<info>Check if ES is ready to accept requests...</info>");
            $response = Http::withBasicAuth(env('ES_USERNAME'), env('ES_PASSWORD'))->get(env('ES_HOSTS'));
            sleep(1);
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
