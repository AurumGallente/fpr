<?php

namespace App\Console\Commands;

use App\Jobs\ProcessReadability;
use Illuminate\Console\Command;
use App\Models\Text;

class CountReadability extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:count-readability';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates jobs for all unprocessed texts to calculate readability';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $texts = Text::where('processed', false)->get();

        foreach ($texts as $text)
        {
            ProcessReadability::dispatch($text);
        }
    }
}
