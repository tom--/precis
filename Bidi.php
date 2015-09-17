<?php
/**
 * @copyright Copyright (c) 2015 Spinitron LLC
 * @license ISC https://opensource.org/licenses/ISC
 */

namespace spinitron\precis;

class Bidi
{
    use BidiDataTrait;

    /**
     * Returns the Bidi class of a character.
     *
     * @param string $char One UTF-8 character
     *
     * @return string A Bidi class aliase, e.g. 'L', 'R', 'ON', etc.
     */
    public static function getClass($char)
    {
        // Ensure input is only one character.
        $char = mb_substr($char, 0, 1, 'UTF-8');

        // Get the char's Unicode code point as PHP integer.
        $ord = Precis::utf8ord($char);

        // Lookup the individual code points.
        if (isset(static::$codePoints[$ord])) {
            return static::$codePoints[$ord];
        }

        $class = static::searchRanges(static::$codePointRanges, $ord);
        if ($class) {
            return $class;
        }

        if (preg_match('%\p{Cu}%u', $char)) {
            $class = static::searchRanges(static::$unassignedDefaultRanges, $ord);
            if ($class) {
                return $class;
            }

            // DerivedBidiClass.txt says Default_Ignorable_Code_Point and Noncharacter_Code_Point
            // (the same as Precis::CC_IGNORABLE) are BR.
            if (preg_match('%[' . Precis::CC_IGNORABLE . ']%u', $char)) {
                return 'BR';
            }
        }

        return 'L';
    }

    protected static function searchRanges($ranges, $ord)
    {
        // Binary search the code point ranges.
        $low = 0;
        $high = count($ranges) - 1;
        do {
            $mid = (int) floor($low + ($high - $low) / 2);
            $range = $ranges[$mid];
            if ($range[0] <= $ord) {
                if ($ord <= $range[1]) {
                    return $range[2];
                }
                if ($ord < $ranges[$mid + 1][0]) {
                    return 'L';
                }
                $low = $mid;
            } else {
                $high = $mid;
            }
        } while ($high - $low > 0);

        return null;
    }

    /**
     * Applies the RFC 5893 Bidi rule to a string.
     *
     * @param string $string
     *
     * @return bool Whether the string passes the Bidi rule or not.
     */
    public static function rule($string)
    {
        $chars = preg_split('//u', $string, null, PREG_SPLIT_NO_EMPTY);

        // 1. RTL or LTR
        // Remove the first char. It is guaranteed to pass test 2/5 after passing test 1.
        $firstClass = static::getClass(array_shift($chars));

        if ($firstClass === 'R' || $firstClass === 'AL') {
            // 3. RTL End char
            if (!in_array(static::getClass(end($chars)), ['R', 'AL', 'EN', 'AN'])) {
                return false;
            }

            // Keep state for test 4.
            $en = false;

            // 2. Classes allowed in RTL
            foreach ($chars as $char) {
                $class = static::getClass($char);
                if (!in_array($class, ['R', 'AL', 'AN', 'EN', 'ES', 'CS', 'ET', 'ON', 'BN', 'NSM'])) {
                    return false;
                }

                if ($class === 'EN') {
                    $en = true;
                }
            }

            // 4. If EN then no AN
            if ($en) {
                foreach ($chars as $char) {
                    if (static::getClass($char) === 'AN') {
                        return false;
                    }
                }
            }

            return true;
        }

        if ($firstClass === 'L') {
            // 6. LTR End char.
            // Last char passes test 5 if it passes test 6, so remove it.
            if (!in_array(static::getClass(array_pop($chars)), ['L', 'EN'])) {
                return false;
            }

            // 5. Classes allowed in LTR.
            foreach ($chars as $char) {
                if (!in_array(static::getClass($char), ['L', 'EN', 'ES', 'CS', 'ET', 'ON', 'BN', 'NSM'])) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }
}
