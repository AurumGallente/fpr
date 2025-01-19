<?php

namespace App\Jobs;

use App\Helpers\WordCounterHelper;
use App\Models\Text;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessWordCount implements ShouldQueue
{
    use Queueable;

    /**
     * @var Text
     */
    private Text $text;

    /**
     * Create a new job instance.
     */
    public function __construct(Text $text)
    {
        $this->text = $text;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->text->words = (new WordCounterHelper($this->text->content))->countWords();
        $this->text->save();
    }
}
