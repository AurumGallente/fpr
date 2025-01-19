<?php

namespace App\Helpers;


class WordCounterHelper
{
    /**
     * @var string
     */
    private string $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    /**
     * @return int
     */
    public function countWords(): int
    {
        // change later when business logic will be more sophisticated
        return str_word_count($this->text);
    }
}
