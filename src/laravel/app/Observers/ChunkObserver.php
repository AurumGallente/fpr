<?php

namespace App\Observers;

use App\Jobs\PutChunkToES;
use App\Models\Chunk;

class ChunkObserver
{
    /**
     * Handle the Chunk "created" event.
     */
    public function created(Chunk $chunk): void
    {

    }

    /**
     * Handle the Chunk "updated" event.
     */
    public function updated(Chunk $chunk): void
    {
        //
    }

    /**
     * Handle the Chunk "deleted" event.
     */
    public function deleted(Chunk $chunk): void
    {
        //
    }

    /**
     * Handle the Chunk "restored" event.
     */
    public function restored(Chunk $chunk): void
    {
        //
    }

    /**
     * Handle the Chunk "force deleted" event.
     */
    public function forceDeleted(Chunk $chunk): void
    {
        //
    }
}
