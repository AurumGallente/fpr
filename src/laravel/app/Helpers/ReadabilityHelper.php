<?php

namespace App\Helpers;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use \stdClass as stdClass;


class ReadabilityHelper
{
    /**
     * @var mixed|string
     */
    private string $language;

    /**
     * @var string
     */
    private string $text;

    /**
     * @var bool
     */
    private bool $isValid;

    /**
     *
     */
    const LIMIT = 200;

    /**
     * @param string $text
     * @param string $language
     *
     * @return $this
     */
    public function __construct(string $text, string $language = 'en')
    {
        $this->language = $language;
        $this->text = $text;

        $this->checkWordsNumber();

        return $this;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @return void
     */
    private function checkWordsNumber(): void
    {

        if((new WordCounterHelper($this->text))->countWords() < self::LIMIT)
        {
            $this->isValid = false;
        } else
        {
            $this->isValid = true;
        }

    }

    /**
     * @return string
     */
    private function processText(): string
    {
        $process = new Process(['python', base_path().'/python_scripts/index.py', $this->text, $this->language]);
        $process->run(NULL);
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $process->getOutput();
    }

    /**
     * @return stdClass
     */
    public function getResult(): stdClass
    {
        if($this->isValid)
        {
            $rawResult = $this->processText();
            $rawResult = trim($rawResult);
            $result = json_decode($rawResult);

        } else
        {
            $result = new stdClass();
        }
        return $result;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->isValid;
    }
}
