<?php

namespace App\Observers;

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
        ProcessWordCount::dispatch($text);
        ProcessReadability::dispatch($text);
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
