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
        return count(preg_split('~[\p{Z}\p{P}]+~u', $this->text, -1, PREG_SPLIT_NO_EMPTY));
    }
}
