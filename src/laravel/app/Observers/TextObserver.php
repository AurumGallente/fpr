<?php

namespace App\Observers;

use App\Jobs\CreateChunks;
use App\Jobs\ProcessReadability;
use App\Jobs\ProcessWordCount;
use App\Models\Text;

class TextObserver
{
    /**
     * Handle the Text "created" event.
     */
    public function created(Text $text): void
    {
        ProcessWordCount::dispatch($text)->onQueue('text_processing');
        ProcessReadability::dispatch($text)->onQueue('text_processing');
        CreateChunks::dispatch($text)->onQueue('text_processing');
    }

    /**
     * Handle the Text "updated" event.
     */
    public function updated(Text $text): void
    {
        //
    }

    /**
     * Handle the Text "deleted" event.
     */
    public function deleted(Text $text): void
    {
        //
    }

    /**
     * Handle the Text "restored" event.
     */
    public function restored(Text $text): void
    {
        //
    }

    /**
     * Handle the Text "force deleted" event.
     */
    public function forceDeleted(Text $text): void
    {
        //
    }
}
