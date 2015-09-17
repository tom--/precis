<?php
/**
 * @copyright Copyright (c) 2015 Spinitron LLC
 * @license ISC https://opensource.org/licenses/ISC
 */

namespace spinitron\precis\tests\unit;

use spinitron\precis\Precis;

class PrecisBaseUnicodeTest extends BaseUnicodeTest
{
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
        $this->assertEquals('a b', Precis::enforceNickname('   A   B   '));

        $this->assertEquals(' a B ', Precis::enforceOpaqueString(' a B '));
        $this->assertEquals(' a B ', Precis::enforceOpaqueString(' a B '));
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