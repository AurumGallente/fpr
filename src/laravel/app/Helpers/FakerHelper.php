<?php

namespace App\Helpers;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class FakerHelper
{
    /**
     * @var string
     */
    private string $language;

    private string $text;

    /**
     * @var array|string[]
     */
    public static array $map = [
        'cs' => 'cs_CZ',
        'da' => 'da_DK',
        'nl' => 'nl_NL',
        'en' => 'en_US',
        'et' => 'et_EE',
        'fi' => 'fi_FI',
        'fr' => 'fr_FR',
        'de' => 'de_DE',
        'el' => 'el_GR',
        'it' => 'it_IT',
        'no' => 'nl_NL',
        'pl' => 'pl_PL',
        'pt' => 'pt_PT',
        'ru' => 'ru_RU',
        'sl' => 'sl_SI',
        'es' => 'es_ES',
        'sv' => 'sv_SE',
        'tr' => 'tr_TR',
    ];
    public function __construct(string $language = 'en')
    {
        if(!array_key_exists($language, self::$map))
        {
            throw new \Exception("Language '{$language}' does not exist.");
        }

        $this->language = $language;

        return $this;
    }

    /**
     * @param string $locale
     * @return string
     */
    private function processText(string $locale = 'en_US'): string
    {
        $process = new Process(['python', base_path().'/python_scripts/fake_text.py', $locale]);
        $process->run(NULL);
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $process->getOutput();
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->processText(self::$map[$this->language]);
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }
}
