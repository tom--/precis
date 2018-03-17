<?php
/**
 * @copyright Copyright (c) 2015 Spinitron LLC
 * @license ISC https://opensource.org/licenses/ISC
 */

namespace spinitron\precis\tests\unit;

use spinitron\precis\tests\Precis;

class DerivedPropertyValueTest extends BaseUnicodeTest
{
    protected $dpvLocalFile;
    protected $dpvFileUrl = 'https://www.iana.org/assignments/precis-tables-6.3.0/precis-tables-6.3.0.csv';

    protected function setUp()
    {
        parent::setUp();

        if (!$this->dpvLocalFile) {
            $this->dpvLocalFile = __DIR__ . '/' . basename($this->dpvFileUrl);
        }

        if (!file_exists($this->dpvLocalFile)) {
            file_put_contents($this->dpvLocalFile, file_get_contents($this->dpvFileUrl));
        }
    }

    public function testAllChars()
    {
        $map = [
            "UNASSIGNED" => Precis::CPROP_UNASSIGNED,
            "DISALLOWED" => Precis::CPROP_DISALLOWED,
            "ID_DIS or FREE_PVAL" => Precis::CPROP_FREE_PVAL,
            "PVALID" => Precis::CPROP_PVALID,
            "CONTEXTO" => Precis::CPROP_CONTEXTO,
            "CONTEXTJ" => Precis::CPROP_CONTEXTJ,
        ];

        $lookup = [
            Precis::CPROP_UNASSIGNED => 'CPROP_UNASSIGNED',
            Precis::CPROP_DISALLOWED => 'CPROP_DISALLOWED',
            Precis::CPROP_FREE_PVAL => 'CPROP_FREE_PVAL',
            Precis::CPROP_PVALID => 'CPROP_PVALID',
            Precis::CPROP_CONTEXTO => 'CPROP_CONTEXTO',
            Precis::CPROP_CONTEXTJ => 'CPROP_CONTEXTJ',
        ];

        $input = fopen($this->dpvLocalFile, 'r');

        // discard header line
        fgets($input);

        while (!feof($input)) {
            $line = fgets($input);
            if ($line === false) {
                break;
            }

            $fields = explode(',', trim($line));
            preg_match('/^([0-9A-F]{4,6})(?:-([0-9A-F]{4,6}))?$/', $fields[0], $matches);
            $from = hexdec(str_pad($matches[1], 8, '0', STR_PAD_LEFT));
            $to = isset($matches[2]) ? hexdec(str_pad($matches[2], 8, '0', STR_PAD_LEFT)) : $from;

            for ($cp = $from; $cp < $to; $cp += 1) {
                $expected = $map[$fields[1]];
                $char = Precis::utf8chr($cp);
                // surrogates can't be converted to characters and are disallowed
                $actual = $char === null ? Precis::CPROP_DISALLOWED : Precis::derivedProperty($char);
                // IANA PRECIS tables are stuck on Unicode 6.3.0 so there are lots of assigned
                // characters that this table lists as unassigned.
                if ($expected !== Precis::CPROP_UNASSIGNED) {
                    if ($actual !== $expected) {
                        printf("%04X  %16s  %10s  %20s  %s\n", $cp, $lookup[$actual], $fields[0], $fields[1],
                            $fields[2]);
                    }
                    $this->assertEquals($expected, $actual);
                }
            }
        }
    }

    public function testContextJSecondTestReturnsPVALID()
    {
        $string = "\xEA\xA1\xB2\xE2\x80\x8C\xD8\xA2";
        $this->assertEquals(Precis::CPROP_PVALID, Precis::getPrecisProperty($string, 1));
    }
}
