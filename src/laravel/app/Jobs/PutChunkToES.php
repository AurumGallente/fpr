<?php

namespace App\Jobs;

use App\Models\Chunk;
use App\Models\ESChunk;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class PutChunkToES implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    private Chunk $chunk;

    public function __construct(Chunk $chunk)
    {
        $this->chunk = $chunk;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $ESchunk = new ESChunk();
        $ESchunk->created_at = Carbon::now();
        $ESchunk->original_content = $this->chunk->content;
        $ESchunk->external_id = $this->chunk->id;
        $ESchunk->save();
    }
}
