<?php
/**
 * @copyright Copyright (c) 2015 Spinitron LLC
 * @license ISC https://opensource.org/licenses/ISC
 */

namespace spinitron\precis\tests\unit;

use spinitron\precis\Precis;

class PrecisBaseUnicodeTest extends BaseUnicodeTest
{
    public function testUtf8utils()
    {
        $php7 = method_exists('\IntlChar', 'chr');
        for ($ord = 0; $ord < 1114112; $ord += 1) {
            // Surrogates
            if ($ord >= 55296 && $ord <= 57343) {
                continue;
            }

            if ($php7) {
                $expectedChar = \IntlChar::chr($ord);
            } else {
                if ($ord < 65536) {
                    $json = sprintf('"\u%04X"', $ord);
                } else {
                    $twenty = $ord - 65536;
                    $low = $twenty % 1024 + 56320;
                    $high = ((int) floor($twenty / 1024)) % 1024 + 55296;
                    $json = sprintf('"\u%04X\u%04X"', $high, $low);
                }
                $expectedChar = json_decode($json);
            }

            $this->assertSame($expectedChar, Precis::utf8chr($ord));

            $actualOrd = Precis::utf8ord($expectedChar);
            $this->assertSame($ord, $actualOrd);

            $actualChar2 = Precis::codePoint2utf8(sprintf('\x{%08X}', $ord));
            $this->assertSame($expectedChar, $actualChar2);

            $actualCodePoint = Precis::utf82CodePoint($expectedChar);
            $this->assertSame(sprintf('U+%04X', $ord), $actualCodePoint);
        }
    }

    public function testMiddleDot()
    {
        $this->assertFalse(Precis::isFreeform('·'));
        $this->assertFalse(Precis::isFreeform('l·'));
        $this->assertFalse(Precis::isFreeform('·l'));
        $this->assertFalse(Precis::isFreeform('l··l'));
        $this->assertFalse(Precis::isFreeform('a·b'));
        $this->assertFalse(Precis::isFreeform('l·b'));
        $this->assertFalse(Precis::isFreeform('a·l'));
        $this->assertTrue(Precis::isFreeform('l·l'));
    }

    public function testKeraia()
    {
        $this->assertFalse(Precis::isFreeform('͵'));
        $this->assertFalse(Precis::isFreeform('͵a'));
        $this->assertFalse(Precis::isFreeform('α͵'));
        $this->assertTrue(Precis::isFreeform('͵α'));
    }

    public function testGeresh()
    {
        $this->assertFalse(Precis::isFreeform('׳'));
        $this->assertFalse(Precis::isFreeform('׳ה'));
        $this->assertFalse(Precis::isFreeform('a׳b'));
        $this->assertTrue(Precis::isFreeform('ש׳'));
    }

    public function testGereshayim()
    {
        $this->assertFalse(Precis::isFreeform('״'));
        $this->assertFalse(Precis::isFreeform('״ה'));
        $this->assertFalse(Precis::isFreeform('a״b'));
        $this->assertTrue(Precis::isFreeform('ש״'));
    }

    public function testKatakanaMiddleDot()
    {
        $this->assertFalse(Precis::isFreeform('abc・def'));
        $this->assertTrue(Precis::isFreeform('aヅc・def'));
        $this->assertTrue(Precis::isFreeform('abc・dぶf'));
        $this->assertTrue(Precis::isFreeform('⺐bc・def'));
    }

    public function testArabicIndicDigit()
    {
        $this->assertTrue(Precis::isFreeform('١٢٣٤٥'));
        $this->assertTrue(Precis::isFreeform('۱۲۳۴۵'));
        $this->assertFalse(Precis::isFreeform('١٢٣٤٥۶'));
        $this->assertFalse(Precis::isFreeform('۱۲۳۴۵٦'));
    }

    public function testPrecisProperty()
    {
        $this->assertSame(Precis::CPROP_PVALID, Precis::getPrecisProperty('ß', 0));
        $this->assertSame(Precis::CPROP_PVALID, Precis::getPrecisProperty('ς', 0));
        $this->assertSame(Precis::CPROP_PVALID, Precis::getPrecisProperty('۽', 0));
        $this->assertSame(Precis::CPROP_PVALID, Precis::getPrecisProperty('་〇', 0));
        $this->assertSame(Precis::CPROP_PVALID, Precis::getPrecisProperty('་', 0));

        $this->assertSame(Precis::CPROP_DISALLOWED, Precis::getPrecisProperty('ـ', 0));
        $this->assertSame(Precis::CPROP_DISALLOWED, Precis::getPrecisProperty('〮', 0));
        $this->assertSame(Precis::CPROP_DISALLOWED, Precis::getPrecisProperty('〯', 0));
        $this->assertSame(Precis::CPROP_DISALLOWED, Precis::getPrecisProperty('〱', 0));
        $this->assertSame(Precis::CPROP_DISALLOWED, Precis::getPrecisProperty('〲', 0));
        $this->assertSame(Precis::CPROP_DISALLOWED, Precis::getPrecisProperty('〳', 0));
        $this->assertSame(Precis::CPROP_DISALLOWED, Precis::getPrecisProperty('〴', 0));
        $this->assertSame(Precis::CPROP_DISALLOWED, Precis::getPrecisProperty('〵', 0));
        $this->assertSame(Precis::CPROP_DISALLOWED, Precis::getPrecisProperty('〻', 0));

        $this->assertSame(Precis::CPROP_UNASSIGNED, Precis::getPrecisProperty('ࣞ', 0));
        $this->assertSame(Precis::CPROP_UNASSIGNED, Precis::getPrecisProperty('৙', 0));
        $this->assertSame(Precis::CPROP_UNASSIGNED, Precis::getPrecisProperty('੻', 0));

        $this->assertSame(Precis::CPROP_PVALID, Precis::getPrecisProperty('!', 0));
        $this->assertSame(Precis::CPROP_PVALID, Precis::getPrecisProperty('~', 0));
        $this->assertSame(Precis::CPROP_PVALID, Precis::getPrecisProperty('x', 0));
        $this->assertSame(Precis::CPROP_PVALID, Precis::getPrecisProperty('Y', 0));

        $this->assertSame(Precis::CPROP_DISALLOWED, Precis::getPrecisProperty('ᄋ', 0));
        $this->assertSame(Precis::CPROP_DISALLOWED, Precis::getPrecisProperty('ᆖ', 0));
        $this->assertSame(Precis::CPROP_DISALLOWED, Precis::getPrecisProperty('ᇰ', 0));
        $this->assertSame(Precis::CPROP_DISALLOWED, Precis::getPrecisProperty('­', 0));
        $this->assertSame(Precis::CPROP_DISALLOWED, Precis::getPrecisProperty('᠎', 0));
        $this->assertSame(Precis::CPROP_DISALLOWED, Precis::getPrecisProperty('​', 0));

        $this->assertSame(Precis::CPROP_DISALLOWED, Precis::getPrecisProperty("\x04", 0));

        $this->assertSame(Precis::CPROP_FREE_PVAL, Precis::getPrecisProperty('１', 0));
        $this->assertSame(Precis::CPROP_FREE_PVAL, Precis::getPrecisProperty('ǅ', 0));
        $this->assertSame(Precis::CPROP_FREE_PVAL, Precis::getPrecisProperty('Ⅳ', 0));
        $this->assertSame(Precis::CPROP_FREE_PVAL, Precis::getPrecisProperty('³', 0));
        $this->assertSame(Precis::CPROP_FREE_PVAL, Precis::getPrecisProperty('⃝', 0));

        $this->assertSame(Precis::CPROP_FREE_PVAL, Precis::getPrecisProperty(' ', 0));

        $this->assertSame(Precis::CPROP_FREE_PVAL, Precis::getPrecisProperty('×', 0));
        $this->assertSame(Precis::CPROP_FREE_PVAL, Precis::getPrecisProperty('¥', 0));
        $this->assertSame(Precis::CPROP_FREE_PVAL, Precis::getPrecisProperty('¨', 0));
        $this->assertSame(Precis::CPROP_FREE_PVAL, Precis::getPrecisProperty('©', 0));

        $this->assertSame(Precis::CPROP_FREE_PVAL, Precis::getPrecisProperty('‿', 0));
        $this->assertSame(Precis::CPROP_FREE_PVAL, Precis::getPrecisProperty('־', 0));
        $this->assertSame(Precis::CPROP_FREE_PVAL, Precis::getPrecisProperty('⁅', 0));
        $this->assertSame(Precis::CPROP_FREE_PVAL, Precis::getPrecisProperty('⁆', 0));
        $this->assertSame(Precis::CPROP_FREE_PVAL, Precis::getPrecisProperty('«', 0));
        $this->assertSame(Precis::CPROP_FREE_PVAL, Precis::getPrecisProperty('»', 0));
    }

    public function testProfilesSimple()
    {
        $this->assertFalse(Precis::prepareUsernameCaseMapped('a b'));
        $this->assertEquals('ab', Precis::prepareUsernameCaseMapped('ab'));
        $this->assertNotEquals('ab', Precis::prepareUsernameCaseMapped('AB'));

        $this->assertEquals('ab', Precis::enforceUsernameCaseMapped('ab'));
        $this->assertEquals('ab', Precis::enforceUsernameCaseMapped('AB'));

        $this->assertEquals('ab', Precis::enforceUsernameCasePreserved('ab'));
        $this->assertEquals('AB', Precis::enforceUsernameCasePreserved('AB'));

        $this->assertEquals('ab', Precis::enforceNickname('ab'));
        $this->assertEquals('a b', Precis::enforceNickname('   a   b   '));

        $this->assertEquals(' a B ', Precis::enforceOpaqueString(' a B '));
        $this->assertEquals(' a B ', Precis::enforceOpaqueString(' a B '));

        $this->assertEquals('ab', Precis::enforceNickname('ab'));
        $this->assertEquals('a b', Precis::enforceNickname('   a   b   '));

        $this->assertFalse(Precis::prepareNickname("\x19"));
        $this->assertFalse(Precis::enforceNickname(''));
        $this->assertSame(0, Precis::compareNicknames('ab', '  AB  '));
        $this->assertNotSame(0, Precis::compareNicknames('a b', '  AB  '));
    }

    /**
     * @dataProvider alphabetProvider
     *
     * @param string $string
     */
    public function testTypesUsernameCaseMapped($string)
    {
        $string = preg_replace('{ }u', '', $string);

        $actual = Precis::prepareUsernameCaseMapped($string);
        $this->assertTrue($actual === $string || $actual === false);

        $actual = Precis::enforceUsernameCaseMapped($string);
        $this->assertTrue(is_string($actual) || $actual === false);
    }

    /**
     * @dataProvider alphabetProvider
     *
     * @param string $string
     */
    public function testTypesUsernameCasePreserved($string)
    {
        $string = preg_replace('{ }u', '', $string);

        $actual = Precis::prepareUsernameCasePreserved($string);
        $this->assertTrue($actual === $string || $actual === false);

        $actual = Precis::enforceUsernameCasePreserved($string);
        $this->assertTrue(is_string($actual) || $actual === false);
    }

    /**
     * @dataProvider alphabetProvider
     *
     * @param string $string
     */
    public function testTypesOpaqueString($string)
    {
        $actual = Precis::prepareOpaqueString($string);
        $this->assertTrue($actual === $string || $actual === false);

        $actual = Precis::enforceOpaqueString($string);
        $this->assertTrue(is_string($actual) || $actual === false);
    }

    /**
     * @dataProvider alphabetProvider
     *
     * @param string $string
     */
    public function testTypesNickname($string)
    {
        $actual = Precis::prepareNickname($string);
        $this->assertTrue($actual === $string || $actual === false);

        $actual = Precis::enforceNickname($string);
        $this->assertTrue(is_string($actual) || $actual === false);
    }
}
