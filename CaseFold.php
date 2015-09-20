<?php
/**
 * CaseFold class file
 *
 * @package spinitron/precis
 * @copyright Copyright (c) 2015 Spinitron LLC
 * @license ISC https://opensource.org/licenses/ISC
 */

namespace spinitron\precis;

/**
 * Provides Unicode case folding (simple version) of strings.
 *
 * @package spinitron\precis
 */
class CaseFold
{
    use CaseFoldDataTrait;

    /**
     * Folds one character
     *
     * @param string[] $matches preg_replace callback arg
     *
     * @return string the case-folded character
     */
    private static function foldChar($matches)
    {
        $replacement = array_key_exists($matches[0], static::$codePoints)
            ? static::$codePoints[$matches[0]]
            : $matches[0];

        return $replacement;
    }

    /**
     * Case folds a string using Unicode algorithm, simple case.
     *
     * > Note: PRECIS Username and Nickname profiles require case folding only for
     * characters with properties uppercase or titlecase but Unicode case folding
     * changes more characters than that.
     *
     * @param string $string the string to case fold
     * @param bool $upperTitleOnly Set true to only fold characters with Unicode uppercase
     * or title-case properties.
     *
     * @return string the case-folded string
     */
    public static function fold($string, $upperTitleOnly = false)
    {
        $pattern = $upperTitleOnly ? '%[\p{Lu}\p{Lt}]%u' : '%.%u';
        return preg_replace_callback($pattern, [__CLASS__, 'foldChar'], $string);
    }
}
