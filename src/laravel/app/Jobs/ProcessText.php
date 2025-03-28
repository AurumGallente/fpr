<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Helpers\ReadabilityHelper;
use App\Helpers\WordCounterHelper;
use App\Models\Chunk;
use App\Models\Text;
use App\Models\ESChunk;
use App\Models\EStext;

class ProcessText implements ShouldQueue
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
        if($this->text->words > ReadabilityHelper::LIMIT)
        {
            $helper = new ReadabilityHelper($this->text->content, $this->text->project->language->code);
            if($helper->isValid())
            {
                $this->text->metrics = json_encode($helper->getResult());
            }

        }

        $chunks = $this->text->chunking();
        $ids = Chunk::insertBatchReturnIds($chunks);

        $this->text->chunks()->attach($ids);
        $this->text->chunks_ids = $ids;
        $this->text->save();
        if(env('ES_HOSTS')){
            $esText = new EStext;
            $esText->created_at = $this->text->created_at;
            $esText->original_content = $this->text->content;
            $esText->normalized_content = $this->text->content;
            $esText->external_id = $this->text->id;
            $esText->project_id = $this->text->project->id;
            $esText->setIndex($this->text->project->language->code.'_text_index');
            $esText->save();

            $existingESChunks = [];
            foreach(ESChunk::whereIn('external_id', $ids)->get() as $esChunk)
            {
                $existingESChunks[] = $esChunk->external_id;
            }
            $array = [];

            $chunks = $this->text->chunks()->whereNotIn('chunks.id', $existingESChunks)->get();

            foreach($chunks as $chunk)
            {
                $array[] = [
                    'original_content' => $chunk->content,
                    'external_id' => $chunk->id,
                    'created_at' => Carbon::now()
                ];
            }
            ESChunk::insertWithoutRefresh($array);
        }

        $this->text->processed = true;
        $this->text->save();
    }
}
