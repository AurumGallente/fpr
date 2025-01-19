<?php

namespace App\Console\Commands;

use App\Models\Text;
use Illuminate\Console\Command;
use App\Jobs\ProcessWordCount;

class CountWords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:count-words';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates jobs for all texts where count of words is NULL';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $texts = Text::whereNull('words')->get();
        foreach ($texts as $text)
        {
            ProcessWordCount::dispatch($text);
        }
    }
}
