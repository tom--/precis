<?php
/**
 * @copyright Copyright (c) 2015 Spinitron LLC
 * @license ISC https://opensource.org/licenses/ISC
 */

namespace spinitron\precis\tests\unit;

use spinitron\precis\CaseFold;

class CaseFoldBaseUnicodeTest extends BaseUnicodeTest
{
    /**
     * @dataProvider everythingProvider
     *
     * @param string $name
     * @param string $string
     */
    public function testCaseFold($name, $string)
    {
        if (!class_exists('\IntlChar')) {
            $this->markTestSkipped('\IntlChar required, i.e. PHP 7.0+');
        }

        $expected = preg_replace_callback('%.%u', function ($matches) {
            return \IntlChar::foldCase($matches[0]);
        }, $string);

        $actual = CaseFold::fold($string);

        $this->assertSame($expected, $actual);
    }
}