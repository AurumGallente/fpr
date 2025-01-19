<?php

namespace App\Jobs;

use App\Helpers\ReadabilityHelper;
use App\Models\Text;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessReadability implements ShouldQueue
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
        if($this->text->words > ReadabilityHelper::LIMIT)
        {
            $helper = new ReadabilityHelper($this->text->content, $this->text->project->language->code);
            if($helper->isValid())
            {
                $this->text->metrics = json_encode($helper->getResult());
            }

        }
        $this->text->processed = true;
        $this->text->save();
    }
}
