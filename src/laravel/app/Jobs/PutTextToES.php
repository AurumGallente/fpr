<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Text;
use App\Models\EStext;

class PutTextToES implements ShouldQueue
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
        if(!env('ES_HOSTS')){
            return;
        }
        $esText = new EStext;
        $esText->created_at = $this->text->created_at;
        $esText->content = $this->text->content;
        $esText->external_id = $this->text->id;
        $esText->setIndex($this->text->project->language->code.'_text_index');
        $esText->save();
    }
}
