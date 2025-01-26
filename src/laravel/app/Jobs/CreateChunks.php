<?php

namespace App\Jobs;

use App\Models\Chunk;
use App\Models\Text;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CreateChunks implements ShouldQueue
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
        $chunks = $this->text->chunking();
        $ids = [];
        foreach ($chunks as $chunk)
        {
            $row = Chunk::firstOrCreate([
                'content' => $chunk,
                'hash' => md5($chunk),
            ]);
            $ids[] = $row->id;
        }
        $this->text->chunks()->attach($ids)->save();
    }
}
