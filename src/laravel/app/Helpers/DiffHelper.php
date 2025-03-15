<?php

namespace App\Helpers;

use Jfcherng\Diff\Differ;
use Jfcherng\Diff\DiffHelper as JDiffHelper;
use Jfcherng\Diff\Factory\RendererFactory;
use Jfcherng\Diff\Renderer\RendererConstant;

class DiffHelper
{
    /**
     * @var array
     */
    private array $differOptions = [
        // show how many neighbor lines
        // Differ::CONTEXT_ALL can be used to show the whole file
        'context' => 3,
        // ignore case difference
        'ignoreCase' => false,
        // ignore line ending difference
        'ignoreLineEnding' => false,
        // ignore whitespace difference
        'ignoreWhitespace' => false,
        // if the input sequence is too long, it will just gives up (especially for char-level diff)
        'lengthLimit' => 12000,
        // if truthy, when inputs are identical, the whole inputs will be rendered in the output
        'fullContextIfIdentical' => false,
    ];

    /**
     * @var array
     */
    private array $rendererOptions = [
        // how detailed the rendered HTML in-line diff is? (none, line, word, char)
        'detailLevel' => 'word',
        // renderer language: eng, cht, chs, jpn, ...
        // or an array which has the same keys with a language file
        'language' => 'eng',
        // show line numbers in HTML renderers
        'lineNumbers' => false,
        // show a separator between different diff hunks in HTML renderers
        'separateBlock' => true,
        // show the (table) header
        'showHeader' => false,
        // the frontend HTML could use CSS "white-space: pre;" to visualize consecutive whitespaces
        // but if you want to visualize them in the backend with "&nbsp;", you can set this to true
        'spacesToNbsp' => true,
        // HTML renderer tab width (negative = do not convert into spaces)
        'tabSize' => 4,
        // this option is currently only for the Combined renderer.
        // it determines whether a replace-type block should be merged or not
        // depending on the content changed ratio, which values between 0 and 1.
        'mergeThreshold' => 0.8,
        // this option is currently only for the Unified and the Context renderers.
        // RendererConstant::CLI_COLOR_AUTO = colorize the output if possible (default)
        // RendererConstant::CLI_COLOR_ENABLE = force to colorize the output
        // RendererConstant::CLI_COLOR_DISABLE = force not to colorize the output
        'cliColorization' => RendererConstant::CLI_COLOR_AUTO,
        // this option is currently only for the Json renderer.
        // internally, ops (tags) are all int type but this is not good for human reading.
        // set this to "true" to convert them into string form before outputting.
        'outputTagAsString' => false,
        // this option is currently only for the Json renderer.
        // it controls how the output JSON is formatted.
        // see available options on https://www.php.net/manual/en/function.json-encode.php
        'jsonEncodeFlags' => \JSON_UNESCAPED_SLASHES | \JSON_UNESCAPED_UNICODE,
        // this option is currently effective when the "detailLevel" is "word"
        // characters listed in this array can be used to make diff segments into a whole
        // for example, making "<del>good</del>-<del>looking</del>" into "<del>good-looking</del>"
        // this should bring better readability but set this to empty array if you do not want it
        'wordGlues' => [],
        // change this value to a string as the returned diff if the two input strings are identical
        'resultForIdenticals' => null,
        // extra HTML classes added to the DOM of the diff container
        'wrapperClasses' => ['diff-wrapper'],
    ];

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
     * @return string
     */
    public static function longestCommonSubstring($str1, $str2): string
    {
        $str1 = self::cleanString($str1);
        $str2 = self::cleanString($str2);

        $len1 = strlen($str1);
        $len2 = strlen($str2);
        $longest = 0;
        $endIndex = 0;

        // Initialize an array to store lengths of common suffixes
        $current = array_fill(0, $len2 + 1, 0);

        // Loop through both strings
        for ($i = 1; $i <= $len1; $i++) {
            $prev = 0; // This variable stores the value from the previous row in the table
            for ($j = 1; $j <= $len2; $j++) {
                $temp = $current[$j]; // Store the current suffix length
                if ($str1[$i - 1] === $str2[$j - 1]) {
                    $current[$j] = $prev + 1;
                    if ($current[$j] > $longest) {
                        $longest = $current[$j];
                        $endIndex = $i; // Track the end index of the substring
                    }
                } else {
                    $current[$j] = 0; // Reset if characters don't match
                }
                $prev = $temp; // Move to the next character
            }
        }

        // Extract the longest common substring
        return $longest > 0 ? substr($str1, $endIndex - $longest, $longest) : '';
    }
}
