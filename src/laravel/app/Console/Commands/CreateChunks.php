<?php

namespace App\Console\Commands;

use App\Models\Text;
use Illuminate\Console\Command;
use App\Jobs\CreateChunks as CreateChunksJob;

class CreateChunks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-chunks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $texts = Text::all();

        foreach ($texts as $text)
        {
            CreateChunksJob::dispatch($text)->onQueue('text_processing');
        }
    }
}
