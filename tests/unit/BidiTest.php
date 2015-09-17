<?php
/**
 * @copyright Copyright (c) 2015 Spinitron LLC
 * @license ISC https://opensource.org/licenses/ISC
 */

namespace spinitron\precis\tests\unit;

use spinitron\precis\Bidi;
use spinitron\precis\Precis;

class BidiBaseUnicodeTest extends BaseUnicodeTest
{
    protected $ucdLocalFile;
    protected $ucdFileUrl = 'http://www.unicode.org/Public/UCD/latest/ucd/UnicodeData.txt';

    protected function setUp()
    {
        parent::setUp();

        if (!$this->ucdLocalFile) {
            $this->ucdLocalFile = __DIR__ . '/' . basename($this->ucdFileUrl);
        }

        if (!file_exists($this->ucdLocalFile)) {
            file_put_contents($this->ucdLocalFile, fopen($this->ucdFileUrl, 'r'));
        }
    }

    public function testAllChars()
    {
        $input = fopen($this->ucdLocalFile, 'r');
        while (!feof($input)) {
            $buffer = fgets($input);
            if ($buffer === false) {
                break;
            }

            // 0047;LATIN CAPITAL LETTER G;Lu;0;L;;;;;N;;;;0067;
            $pattern = '%^([0-9A-F]{4,6});[^;]+;[^;]+;[^;]+;([^;]+)%';
            if (preg_match($pattern, $buffer, $matches) && $matches[1] && $matches[2]) {
                $hex = $matches[1];
                $char = Precis::hex2utf8($hex);
                if ($char === null) {
                    continue;
                }

                $expected = $matches[2];
                $actual = Bidi::getClass($char);
                $this->assertSame($expected, $actual);
            }
        }
    }

    /**
     * @dataProvider everythingProvider
     *
     * @param string $name
     * @param string $string
     */
    public function testBidiRuleTypeLength($name, $string)
    {
        $actual = Bidi::rule($string);
        $this->assertInternalType('bool', $actual);
    }
}
