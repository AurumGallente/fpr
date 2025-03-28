<?php

namespace App\Helpers;

use Jfcherng\Diff\Differ;
use Jfcherng\Diff\DiffHelper as JDiffHelper;
use Jfcherng\Diff\Factory\RendererFactory;
use Jfcherng\Diff\Renderer\RendererConstant;
use JsonException;
use stdClass;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class DiffHelper
{
    /**
     * @param $string
     * @return array|string|null
     */
    private static function cleanString($string): array|string|null
    {
        // Remove punctuation, whitespace, and new lines
        return preg_replace('/[^\w]/', ' ', strtolower($string));
    }

    /**
     * @param $str1
     * @param $str2
     * @return stdClass
     * @throws JsonException
     */
    public function longestCommonSubstring($text1, $text2): stdClass
    {
        $text1 = preg_replace("#[[:punct:]]#", "", $text1);
        $text1 = preg_replace('/\s+/', ' ', $text1);

        $text2 = preg_replace("#[[:punct:]]#", "", $text2);
        $text2 = preg_replace('/\s+/', ' ', $text2);

        return $this->getResult($text1, $text2);
    }

    private function processText(string $text1, string $text2): string
    {
        $process = new Process(['python', base_path().'/python_scripts/diff.py', $text1, $text2]);
        $process->run(NULL);
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $process->getOutput();
    }

    /**
     * @param string $text1
     * @param string $text2
     * @return stdClass
     * @throws JsonException
     */
    private function getResult(string $text1, string $text2): stdClass
    {
        $rawResult = $this->processText($text1, $text2);
        $rawResult = trim($rawResult);
        return json_decode($rawResult, false, 512, JSON_THROW_ON_ERROR);
    }
}
