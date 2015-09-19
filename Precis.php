<?php
/**
 * @copyright Copyright (c) 2015 Spinitron LLC
 * @license ISC https://opensource.org/licenses/ISC
 */

namespace spinitron\precis;

/**
 * Class Precis provides constants and static methods for working with PRECIS Framework RFC 7564.
 */
class Precis
{
    /*
     * SCLASS_ prefix is mnemonic for string class (PRECIS Section 4).
     */
    /**
     * Strings that belong to neither PRECIS FreeformClass nor PRECIS IdentifierClass
     */
    const SCLASS_NUL = 0;

    /**
     * Strings that belong to PRECIS FreeformClass
     */
    const SCLASS_FREEFORM = 1;

    /**
     * Strings that belong to PRECIS IdentifierClass
     */
    const SCLASS_IDENTIFIER = 2;

    /**
     * @var array Lookup a string class name from its SCLASS_ constant.
     */
    protected static $sclassLookup = [
        self::SCLASS_NUL => null,
        self::SCLASS_FREEFORM => 'FreeformClass',
        self::SCLASS_IDENTIFIER => 'IdentifierClass',
    ];

    /*
     * CPROP_ prefix is mnemonic for code point property (PRECIS Section 8).
     */
    /**
     * PRECIS UNASSIGNED Code Point Property
     */
    const CPROP_UNASSIGNED = 1;

    /**
     * PRECIS DISALLOWED Code Point Property
     */
    const CPROP_DISALLOWED = 2;

    /**
     * PRECIS FREE_PVAL Code Point Property
     */
    const CPROP_FREE_PVAL = 3;

    /**
     * PRECIS PVALID Code Point Property
     */
    const CPROP_PVALID = 4;

    /**
     * @var array Lookup a string class name from its SCLASS_ constant.
     */
    protected static $cpropLookup = [
        self::CPROP_UNASSIGNED => 'UNASSIGNED',
        self::CPROP_DISALLOWED => 'DISALLOWED',
        self::CPROP_FREE_PVAL => 'FREE_PVAL',
        self::CPROP_PVALID => 'PVALID',
    ];

    /*
     * CC_ prefix is mnemonic for regular expression character class.
     *
     * They are used mostly for testing if a character belongs to one of the 18
     * character definitions in PRECIS Section 9.
     *
     * Where a Unicode version-independent way to get the necessary character property was
     * not available, UCD 8.0.0 was used.
     */
    /**
     * PRECIS Exceptions that RFC 5892 lists as PVALID
     */
    const CC_EXCEPTIONS_PVALID = '\x{00DF}\x{03C2}\x{06FD}\x{06FE}\x{0F0B}\x{3007}';

    /**
     * PRECIS Exceptions that RFC 5892 lists as CONTAXTO
     */
    const CC_EXCEPTIONS_CONTEXTO = '\x{00B7}\x{0375}\x{05F3}\x{05F4}\x{30FB}\x{0660}-\x{0669}\x{06F0}-\x{06F9}';

    /**
     * PRECIS Exceptions that RFC 5892 lists as DISALLOWED
     */
    const CC_EXCEPTIONS_DISALLOWED = '\x{0640}\x{07FA}\x{302E}\x{302F}\x{3031}-\x{3035}\x{303B}';

    /**
     * PRECIS BackwardCompatible category is empty at present
     */
    const CC_BACKWARD_COMPATIBLE = '';

    /**
     * Codepoints unassigned in Unicode
     */
    const CC_UNASSIGNED = '\p{Cn}';

    /**
     * PRECIS ASCII7 (printable)
     */
    const CC_ASCII7 = '\x21-\x7E';

    /**
     * PRECIS JoinControl
     */
    const CC_JOIN_CONTROL = '\x{200C}\x{200D}';

    /**
     * PRECIS OldHangulJamo.
     * In PHP 7 IntlChar provides an alternative more robust to different Unicode versions.
     */
    const CC_OLD_HANGUL_JAMO = '\x{1100}-\x{115F}\x{A960}-\x{A97C}\x{1160}-\x{11A7}\x{D7B0}-\x{D7C6}\x{11A8}-\x{11FF}\x{D7CB}-\x{D7FB}';

    /**
     * PRECIS PrecisIgnorableProperties :=
     *      Noncharacter_Code_Point, which RFC 5892 says is Cn, + Default_Ignorable_Code_Point.
     * PHP 7 IntlChar can replace the long CC.
     */
    const CC_IGNORABLE = '\p{Cn}\x{00AD}\x{034F}\x{061C}\x{115F}-\x{1160}\x{17B4}-\x{17B5}\x{180B}-\x{180D}\x{180E}\x{200B}-\x{200F}\x{202A}-\x{202E}\x{2060}-\x{2064}\x{2065}\x{2066}-\x{206F}\x{3164}\x{FE00}-\x{FE0F}\x{FEFF}\x{FFA0}\x{FFF0}-\x{FFF8}\x{1BCA0}-\x{1BCA3}\x{1D173}-\x{1D17A}\x{E0000}\x{E0001}\x{E0002}-\x{E001F}\x{E0020}-\x{E007F}\x{E0080}-\x{E00FF}\x{E0100}-\x{E01EF}\x{E01F0}-\x{E0FFF}';

    /**
     * PRECIS Controls, which I assume are the controls in Basic Latin and Latin-1 Supplement
     */
    const CC_CONTROLS = '\p{Cc}';

    /**
     * PRECIS LetterDigits
     */
    const CC_LETTER_DIGITS = '\p{Ll}\p{Lu}\p{Lo}\p{Nd}\p{Lm}\p{Mn}\p{Mc}';

    /**
     * PRECIS OtherLetterDigits
     */
    const CC_OTHER_LETTER_DIGITS = '\p{Lt}\p{Nl}\p{No}\p{Me}';

    /**
     * PRECIS Spaces
     */
    const CC_SPACES = '\p{Zs}';

    /**
     * PRECIS Symbols
     */
    const CC_SYMBOLS = '\p{Sm}\p{Sc}\p{Sk}\p{So}';

    /**
     * PRECIS Punctuation
     */
    const CC_PUNCTUATION = '\p{Pc}\p{Pd}\p{Ps}\p{Pe}\p{Pi}\p{Pf}\p{Po}';

    /**
     * Unicode Canonical_Combining_Class=Virama.
     * PHP 7 IntlChar can replace the long CC.
     */
    const CC_CCC_VIRAMA = '\x{094D}\x{09CD}\x{0A4D}\x{0ACD}\x{0B4D}\x{0BCD}\x{0C4D}\x{0CCD}\x{0D4D}\x{0DCA}\x{0E3A}\x{0F84}\x{1039}-\x{103A}\x{1714}\x{1734}\x{17D2}\x{1A60}\x{1B44}\x{1BAA}\x{1BAB}\x{1BF2}-\x{1BF3}\x{2D7F}\x{A806}\x{A8C4}\x{A953}\x{A9C0}\x{AAF6}\x{ABED}\x{10A3F}\x{11046}\x{1107F}\x{110B9}\x{11133}-\x{11134}\x{111C0}\x{11235}\x{112EA}\x{1134D}\x{114C2}\x{115BF}\x{1163F}\x{116B6}\x{1172B}';

    /**
     * Unicode Joining_Type=Join_Causing
     * PHP 7 IntlChar can replace all the CC_JT_s.
     */
    const CC_JT_CAUSE = '\x{0640}\x{07FA}\x{180A}\x{200D}';

    /**
     * Unicode Joining_Type=Dual_Joining
     */
    const CC_JT_DUAL = '\x{0620}\x{0626}\x{0628}\x{062A}-\x{062E}\x{0633}-\x{063F}\x{0641}-\x{0647}\x{0649}-\x{064A}\x{066E}-\x{066F}\x{0678}-\x{0687}\x{069A}-\x{06BF}\x{06C1}-\x{06C2}\x{06CC}\x{06CE}\x{06D0}-\x{06D1}\x{06FA}-\x{06FC}\x{06FF}\x{0712}-\x{0714}\x{071A}-\x{071D}\x{071F}-\x{0727}\x{0729}\x{072B}\x{072D}-\x{072E}\x{074E}-\x{0758}\x{075C}-\x{076A}\x{076D}-\x{0770}\x{0772}\x{0775}-\x{0777}\x{077A}-\x{077F}\x{07CA}-\x{07EA}\x{0841}-\x{0845}\x{0848}\x{084A}-\x{0853}\x{0855}\x{08A0}-\x{08A9}\x{08AF}-\x{08B0}\x{08B3}-\x{08B4}\x{1807}\x{1820}-\x{1842}\x{1843}\x{1844}-\x{1877}\x{1887}-\x{18A8}\x{18AA}\x{A840}-\x{A871}\x{10AC0}-\x{10AC4}\x{10AD3}-\x{10AD6}\x{10AD8}-\x{10ADC}\x{10ADE}-\x{10AE0}\x{10AEB}-\x{10AEE}\x{10B80}\x{10B82}\x{10B86}-\x{10B88}\x{10B8A}-\x{10B8B}\x{10B8D}\x{10B90}\x{10BAD}-\x{10BAE}';

    /**
     * Unicode Joining_Type=Right_Joining
     */
    const CC_JT_RIGHT = '\x{0622}-\x{0625}\x{0627}\x{0629}\x{062F}-\x{0632}\x{0648}\x{0671}-\x{0673}\x{0675}-\x{0677}\x{0688}-\x{0699}\x{06C0}\x{06C3}-\x{06CB}\x{06CD}\x{06CF}\x{06D2}-\x{06D3}\x{06D5}\x{06EE}-\x{06EF}\x{0710}\x{0715}-\x{0719}\x{071E}\x{0728}\x{072A}\x{072C}\x{072F}\x{074D}\x{0759}-\x{075B}\x{076B}-\x{076C}\x{0771}\x{0773}-\x{0774}\x{0778}-\x{0779}\x{0840}\x{0846}-\x{0847}\x{0849}\x{0854}\x{08AA}-\x{08AC}\x{08AE}\x{08B1}-\x{08B2}\x{10AC5}\x{10AC7}\x{10AC9}-\x{10ACA}\x{10ACE}-\x{10AD2}\x{10ADD}\x{10AE1}\x{10AE4}\x{10AEF}\x{10B81}\x{10B83}-\x{10B85}\x{10B89}\x{10B8C}\x{10B8E}-\x{10B8F}\x{10B91}\x{10BA9}-\x{10BAC}';

    /**
     * Unicode Joining_Type=Left_Joining
     */
    const CC_JT_LEFT = '\x{A872}\x{10ACD}\x{10AD7}';

    /**
     * Unicode Joining_Type=Transparent
     */
    const CC_JT_TRANSPARENT = '\x{00AD}\x{0300}-\x{036F}\x{0483}-\x{0487}\x{0488}-\x{0489}\x{0591}-\x{05BD}\x{05BF}\x{05C1}-\x{05C2}\x{05C4}-\x{05C5}\x{05C7}\x{0610}-\x{061A}\x{061C}\x{064B}-\x{065F}\x{0670}\x{06D6}-\x{06DC}\x{06DF}-\x{06E4}\x{06E7}-\x{06E8}\x{06EA}-\x{06ED}\x{070F}\x{0711}\x{0730}-\x{074A}\x{07A6}-\x{07B0}\x{07EB}-\x{07F3}\x{0816}-\x{0819}\x{081B}-\x{0823}\x{0825}-\x{0827}\x{0829}-\x{082D}\x{0859}-\x{085B}\x{08E3}-\x{0902}\x{093A}\x{093C}\x{0941}-\x{0948}\x{094D}\x{0951}-\x{0957}\x{0962}-\x{0963}\x{0981}\x{09BC}\x{09C1}-\x{09C4}\x{09CD}\x{09E2}-\x{09E3}\x{0A01}-\x{0A02}\x{0A3C}\x{0A41}-\x{0A42}\x{0A47}-\x{0A48}\x{0A4B}-\x{0A4D}\x{0A51}\x{0A70}-\x{0A71}\x{0A75}\x{0A81}-\x{0A82}\x{0ABC}\x{0AC1}-\x{0AC5}\x{0AC7}-\x{0AC8}\x{0ACD}\x{0AE2}-\x{0AE3}\x{0B01}\x{0B3C}\x{0B3F}\x{0B41}-\x{0B44}\x{0B4D}\x{0B56}\x{0B62}-\x{0B63}\x{0B82}\x{0BC0}\x{0BCD}\x{0C00}\x{0C3E}-\x{0C40}\x{0C46}-\x{0C48}\x{0C4A}-\x{0C4D}\x{0C55}-\x{0C56}\x{0C62}-\x{0C63}\x{0C81}\x{0CBC}\x{0CBF}\x{0CC6}\x{0CCC}-\x{0CCD}\x{0CE2}-\x{0CE3}\x{0D01}\x{0D41}-\x{0D44}\x{0D4D}\x{0D62}-\x{0D63}\x{0DCA}\x{0DD2}-\x{0DD4}\x{0DD6}\x{0E31}\x{0E34}-\x{0E3A}\x{0E47}-\x{0E4E}\x{0EB1}\x{0EB4}-\x{0EB9}\x{0EBB}-\x{0EBC}\x{0EC8}-\x{0ECD}\x{0F18}-\x{0F19}\x{0F35}\x{0F37}\x{0F39}\x{0F71}-\x{0F7E}\x{0F80}-\x{0F84}\x{0F86}-\x{0F87}\x{0F8D}-\x{0F97}\x{0F99}-\x{0FBC}\x{0FC6}\x{102D}-\x{1030}\x{1032}-\x{1037}\x{1039}-\x{103A}\x{103D}-\x{103E}\x{1058}-\x{1059}\x{105E}-\x{1060}\x{1071}-\x{1074}\x{1082}\x{1085}-\x{1086}\x{108D}\x{109D}\x{135D}-\x{135F}\x{1712}-\x{1714}\x{1732}-\x{1734}\x{1752}-\x{1753}\x{1772}-\x{1773}\x{17B4}-\x{17B5}\x{17B7}-\x{17BD}\x{17C6}\x{17C9}-\x{17D3}\x{17DD}\x{180B}-\x{180D}\x{18A9}\x{1920}-\x{1922}\x{1927}-\x{1928}\x{1932}\x{1939}-\x{193B}\x{1A17}-\x{1A18}\x{1A1B}\x{1A56}\x{1A58}-\x{1A5E}\x{1A60}\x{1A62}\x{1A65}-\x{1A6C}\x{1A73}-\x{1A7C}\x{1A7F}\x{1AB0}-\x{1ABD}\x{1ABE}\x{1B00}-\x{1B03}\x{1B34}\x{1B36}-\x{1B3A}\x{1B3C}\x{1B42}\x{1B6B}-\x{1B73}\x{1B80}-\x{1B81}\x{1BA2}-\x{1BA5}\x{1BA8}-\x{1BA9}\x{1BAB}-\x{1BAD}\x{1BE6}\x{1BE8}-\x{1BE9}\x{1BED}\x{1BEF}-\x{1BF1}\x{1C2C}-\x{1C33}\x{1C36}-\x{1C37}\x{1CD0}-\x{1CD2}\x{1CD4}-\x{1CE0}\x{1CE2}-\x{1CE8}\x{1CED}\x{1CF4}\x{1CF8}-\x{1CF9}\x{1DC0}-\x{1DF5}\x{1DFC}-\x{1DFF}\x{200B}\x{200E}-\x{200F}\x{202A}-\x{202E}\x{2060}-\x{2064}\x{206A}-\x{206F}\x{20D0}-\x{20DC}\x{20DD}-\x{20E0}\x{20E1}\x{20E2}-\x{20E4}\x{20E5}-\x{20F0}\x{2CEF}-\x{2CF1}\x{2D7F}\x{2DE0}-\x{2DFF}\x{302A}-\x{302D}\x{3099}-\x{309A}\x{A66F}\x{A670}-\x{A672}\x{A674}-\x{A67D}\x{A69E}-\x{A69F}\x{A6F0}-\x{A6F1}\x{A802}\x{A806}\x{A80B}\x{A825}-\x{A826}\x{A8C4}\x{A8E0}-\x{A8F1}\x{A926}-\x{A92D}\x{A947}-\x{A951}\x{A980}-\x{A982}\x{A9B3}\x{A9B6}-\x{A9B9}\x{A9BC}\x{A9E5}\x{AA29}-\x{AA2E}\x{AA31}-\x{AA32}\x{AA35}-\x{AA36}\x{AA43}\x{AA4C}\x{AA7C}\x{AAB0}\x{AAB2}-\x{AAB4}\x{AAB7}-\x{AAB8}\x{AABE}-\x{AABF}\x{AAC1}\x{AAEC}-\x{AAED}\x{AAF6}\x{ABE5}\x{ABE8}\x{ABED}\x{FB1E}\x{FE00}-\x{FE0F}\x{FE20}-\x{FE2F}\x{FEFF}\x{FFF9}-\x{FFFB}\x{101FD}\x{102E0}\x{10376}-\x{1037A}\x{10A01}-\x{10A03}\x{10A05}-\x{10A06}\x{10A0C}-\x{10A0F}\x{10A38}-\x{10A3A}\x{10A3F}\x{10AE5}-\x{10AE6}\x{11001}\x{11038}-\x{11046}\x{1107F}-\x{11081}\x{110B3}-\x{110B6}\x{110B9}-\x{110BA}\x{110BD}\x{11100}-\x{11102}\x{11127}-\x{1112B}\x{1112D}-\x{11134}\x{11173}\x{11180}-\x{11181}\x{111B6}-\x{111BE}\x{111CA}-\x{111CC}\x{1122F}-\x{11231}\x{11234}\x{11236}-\x{11237}\x{112DF}\x{112E3}-\x{112EA}\x{11300}-\x{11301}\x{1133C}\x{11340}\x{11366}-\x{1136C}\x{11370}-\x{11374}\x{114B3}-\x{114B8}\x{114BA}\x{114BF}-\x{114C0}\x{114C2}-\x{114C3}\x{115B2}-\x{115B5}\x{115BC}-\x{115BD}\x{115BF}-\x{115C0}\x{115DC}-\x{115DD}\x{11633}-\x{1163A}\x{1163D}\x{1163F}-\x{11640}\x{116AB}\x{116AD}\x{116B0}-\x{116B5}\x{116B7}\x{1171D}-\x{1171F}\x{11722}-\x{11725}\x{11727}-\x{1172B}\x{16AF0}-\x{16AF4}\x{16B30}-\x{16B36}\x{16F8F}-\x{16F92}\x{1BC9D}-\x{1BC9E}\x{1BCA0}-\x{1BCA3}\x{1D167}-\x{1D169}\x{1D173}-\x{1D17A}\x{1D17B}-\x{1D182}\x{1D185}-\x{1D18B}\x{1D1AA}-\x{1D1AD}\x{1D242}-\x{1D244}\x{1DA00}-\x{1DA36}\x{1DA3B}-\x{1DA6C}\x{1DA75}\x{1DA84}\x{1DA9B}-\x{1DA9F}\x{1DAA1}-\x{1DAAF}\x{1E8D0}-\x{1E8D6}\x{E0001}\x{E0020}-\x{E007F}\x{E0100}-\x{E01EF}';

    /**
     * Determine if a character is in the given regex character class.
     *
     * @param string $char the character to test
     * @param string $charset the regex character class, without enclosing []
     *
     * @return bool
     */
    protected static function inCharset($char, $charset)
    {
        if (mb_strlen($char, 'UTF-8') !== 1) {
            throw new \InvalidArgumentException('$char must be one character');
        }

        $pattern = '%[' . $charset . ']%u';

        return (bool) preg_match($pattern, $char);
    }

    /**
     * Implements the test for the PRECIS HasCompat (Q) category in RFC 7564.
     *
     * @param string $char
     *
     * @return bool
     */
    public static function getHasCompat($char)
    {
        return \Normalizer::normalize($char, \Normalizer::FORM_KC) !== $char;
    }

    /**
     * Placeholder for BackwardCompatible look up table. There are not yet code points
     * in the BackwardCompatible character category so this method won't be called.
     * If and when BackwardCompatible charactrs are defined, this method will return the
     * derived property for any of them.
     *
     * @param $char
     *
     * @return int One of the CPROP_ derived property constants
     */
    /*public static function getBackwardCompatible($char)
    {
        return self::CPROP_PVALID;
    }*/

    /**
     * Tests CONTEXTO exception characters in their string context according to RFC 5892.
     *
     * @param string $string string containing the character to test
     * @param int $pos position in the string of the character to test
     *
     * @return int either Precis::CPROP_DISALLOWED or Precis::CPROP_PVALID
     * @throws \Exception if the character is not a CONTEXTO exception
     */
    public static function getContextO($string, $pos)
    {
        $char = mb_substr($string, $pos, 1, 'UTF-8');
        $before = $pos > 0 ? mb_substr($string, $pos - 1, 1, 'UTF-8') : null;
        $after = $pos < mb_strlen($string, 'UTF-8') ? mb_substr($string, $pos + 1, 1, 'UTF-8') : null;
        $cp = static::utf82CodePoint($char);
        $propMap = [false => self::CPROP_DISALLOWED, true => self::CPROP_PVALID];

        if ($cp === 'U+00B7') {
            // RFC 5892 Appendix A.3 MIDDLE DOT
            return $propMap[$before === 'l' && $after === 'l'];
        }

        if ($cp === 'U+0375') {
            // RFC 5892 A.4 GREEK LOWER NUMERAL SIGN (KERAIA)
            return $propMap[$after && preg_match('%\p{Greek}%u', $after)];
        }

        if ($cp === 'U+05F3' || $cp === 'U+05F4') {
            // RFC 5892 A.5 HEBREW PUNCTUATION GERESH and A.6 HEBREW PUNCTUATION GERSHAYIM
            return $propMap[$before && preg_match('%\p{Hebrew}%u', $before)];
        }

        if ($cp === 'U+30FB') {
            // RFC 5892 A.7 KATAKANA MIDDLE DOT
            return $propMap[preg_match('%[\p{Hiragana}\p{Katakana}\p{Han}]%u', $string)];
        }

        $cpInt = static::utf8ord($char);
        if ($cpInt >= hexdec('0660') && $cpInt <= hexdec('0669')) {
            // RFC 5892 A.8 ARABIC-INDIC DIGITS
            return $propMap[!preg_match('%[\x{06F0}-\x{06F9}]%u', $string)];
        }

        if ($cpInt >= hexdec('06F0') && $cpInt <= hexdec('06F9')) {
            // RFC 5892 A.9 EXTENDED ARABIC-INDIC DIGITS
            return $propMap[!preg_match('%[\x{0660}-\x{0669}]%u', $string)];
        }

        throw new \InvalidArgumentException("Unexpected character '$char' at position $pos in string");
    }

    /**
     * Tests CONTEXTJ exception characters in their string context according to RFC 5892.
     *
     * @param string $string string containing the character to test
     * @param int $pos position in the string of the character to test
     *
     * @return int either Precis::CPROP_DISALLOWED or Precis::CPROP_PVALID
     * @throws \Exception if the character is not a CONTEXTJ exception
     */
    public static function getContextJ($string, $pos)
    {
        if ($pos < 1) {
            return self::CPROP_DISALLOWED;
        }

        // RFC 5892 Appendix A.1. and A.2. first test
        $withPrev = mb_substr($string, $pos - 1, 2, 'UTF-8');
        if (preg_match('%^[' . self::CC_CCC_VIRAMA . '][\x{200C}\x{200D}]$%u', $withPrev)) {
            return self::CPROP_PVALID;
        }

        // RFC 5892 Appendix A.1. second test
        $pattern = '[' . self::CC_JT_RIGHT . self::CC_JT_DUAL . ']';
        $pattern .= '[' . self::CC_JT_TRANSPARENT . ']*';
        $pattern .= '\x{200C}';
        $pattern .= '[' . self::CC_JT_TRANSPARENT . ']*';
        $pattern .= '[' . self::CC_JT_RIGHT . self::CC_JT_DUAL . ']';

        if (preg_match("%$pattern%u", $string)) {
            return self::CPROP_PVALID;
        }

        throw new \InvalidArgumentException("Unexpected character at position $pos in string");
    }

    /**
     * Determines the "derived property" of a character in the context of a string.
     * Implements the algorithm specified in RFC 7564 8. Code Point Properties.
     *
     * @param string $string string containing the character to test
     * @param int $pos position in the string of the character to test
     *
     * @return int the character's property: Precis::CPROP_PVALID, Precis::CPROP_FREE_PVAL,
     * Precis::CPROP_DISALLOWED or Precis::CPROP_UNASSIGNED
     */
    public static function getPrecisProperty($string, $pos)
    {
        $ifelse = [
            [self::CC_EXCEPTIONS_PVALID, self::CPROP_PVALID],
            [self::CC_EXCEPTIONS_DISALLOWED, self::CPROP_DISALLOWED],
            [self::CC_EXCEPTIONS_CONTEXTO, [__CLASS__, 'getContextO']],
            [self::CC_UNASSIGNED, self::CPROP_UNASSIGNED],
            [self::CC_ASCII7, self::CPROP_PVALID],
            [self::CC_JOIN_CONTROL, [__CLASS__, 'getContextJ']],
            [self::CC_OLD_HANGUL_JAMO, self::CPROP_DISALLOWED],
            [self::CC_IGNORABLE, self::CPROP_DISALLOWED],
            [self::CC_CONTROLS, self::CPROP_DISALLOWED],
            [[__CLASS__, 'getHasCompat'], self::CPROP_FREE_PVAL],
            [self::CC_LETTER_DIGITS, self::CPROP_PVALID],
            [self::CC_OTHER_LETTER_DIGITS, self::CPROP_FREE_PVAL],
            [self::CC_SPACES, self::CPROP_FREE_PVAL],
            [self::CC_SYMBOLS, self::CPROP_FREE_PVAL],
            [self::CC_PUNCTUATION, self::CPROP_FREE_PVAL],
        ];

        $char = mb_substr($string, $pos, 1, 'UTF-8');
        foreach ($ifelse as $case) {
            list($test, $prop) = $case;
            $testResult = is_string($test) ? static::inCharset($char, $test) : call_user_func($test, $char);
            if ($testResult) {
                return is_int($prop) ? $prop : call_user_func($prop, $string, $pos);
            }
        }

        return self::CPROP_DISALLOWED;
    }

    /**
     * UTF-8 version of PHP's chr() builtin.
     *
     * > Note: Does not validate input.
     *
     * @param int $ord Unicode codepoint as a PHP intger
     *
     * @return null|string Single-character UTF-8 string or null if $ord is a surrogate
     */
    public static function utf8chr($ord)
    {
        if ($ord >= 55296 && $ord <= 57343) {
            return null;
        }

        return mb_convert_encoding(hex2bin(sprintf('%08X', $ord)), 'UTF-8', 'UTF-32BE');
    }

    /**
     * UTF-8 version of PHP's ord() builtin.
     *
     * @param string $string a UTF-8 character or string
     * @param int $pos position in th string of the character to decode
     *
     * @return string Unicode code point as a string of 8 hex digits
     */
    public static function utf8ord($string, $pos = 0)
    {
        $char = mb_substr($string, $pos, 1, 'UTF-8');

        return hexdec(bin2hex(mb_convert_encoding($char, 'UTF-32BE', 'UTF-8')));
    }

    /**
     * Returns the utf8 character of a hex codepoint.
     *
     * @param string $codePoint Hex-based Unicode codepoint, e.g. "6D", "U+261E", "\u{1D15F}", "\u1f595"
     *
     * @return string|null|false single-character UTF-8 string, null if $ord is a surrogate, false on failure
     */
    public static function codePoint2utf8($codePoint)
    {
        // Extract 2 to 8 hex digits starting from the end.
        if (!preg_match('{([0-9A-F]{2,8}).*?$}i', $codePoint, $matches)) {
            return false;
        }

        $hex = $matches[1];

        // Surrogates D800..DFFF are not allowed to be encoded
        if (preg_match('{^0*D[8-F][0-9A-F]{2}$}i', $hex)) {
            return null;
        }

        return mb_convert_encoding(hex2bin(sprintf('%08s', $hex)), 'UTF-8', 'UTF-32BE');
    }

    /**
     * Returns the Unicode code point of a UTF-8 character.
     *
     * @param string $string a UTF-8 character or string
     * @param int $pos position in th string of the character to decode
     * @param string $style output style, e.g. 'U+', '\u', '\x{}'
     *
     * @return string Unicode cope point, e.g. 'U+0065'
     */
    public static function utf82CodePoint($string, $pos = 0, $style = 'U+')
    {
        $format = preg_match('%^(.+?\p{Ps})(\p{Pe})$%u', $style, $matches)
            ? $matches[1] . '%04X' . $matches[2]
            : $style . '%04X';

        return sprintf($format, static::utf8ord($string, $pos));
    }

    /**
     * Analyzes a string determining the PRECIS code point property of each character.
     *
     * @param string $string the string to analyze
     *
     * @return array A numeric array with one element for each character in the input string.
     * Each element is an array of thr form
     *      [
     *          'char' => <the UTF-8 encoded character>,
     *          'cp' => <Unicode code point un U+XXXX notation>,
     *          '' => <PRECIS code point property name>,
     */
    public static function analyzeString($string)
    {
        $chars = preg_split('//u', $string, null, PREG_SPLIT_NO_EMPTY);
        $cpDetails = [];
        foreach ($chars as $pos => $char) {
            $cpDetails[$pos] = [
                'char' => $char,
                'cp' => static::utf82CodePoint($char),
                'prop' => static::$cpropLookup[static::getPrecisProperty($string, $pos)],
            ];
        }

        return $cpDetails;
    }

    /**
     * Returns the PRECIS String Class of a given string.
     *
     * @param string $string the string to test
     *
     * @return int the string's class: Precis::SCLASS_IDENTIFIER, Precis::SCLASS_FREEFORM
     * or Precis::SCLASS_NUL
     */
    public static function getStringClass($string)
    {
        $length = mb_strlen($string, 'UTF-8');
        $class = self::SCLASS_IDENTIFIER;
        for ($pos = 0; $pos < $length; $pos += 1) {
            $prop = self::getPrecisProperty($string, $pos);
            if ($prop === self::CPROP_UNASSIGNED || $prop === self::CPROP_DISALLOWED) {
                return self::SCLASS_NUL;
            }

            if ($prop === self::CPROP_FREE_PVAL) {
                $class = self::SCLASS_FREEFORM;
            }
        }

        return $class;
    }

    /**
     * Tests a string for membership in a String Class using an anonymous function.
     *
     * @param string $string the string to test
     * @param \Closure $test an anonymous function with one argument (one of Precis::CPROP_PVALID,
     * Precis::CPROP_FREE_PVAL, Precis::CPROP_DISALLOWED or Precis::CPROP_UNASSIGNED) and
     * that returns bool.
     *
     * @return bool true if all characters in the string pass the given test
     */
    protected static function isClass($string, \Closure $test)
    {
        $length = mb_strlen($string, 'UTF-8');
        for ($pos = 0; $pos < $length; $pos += 1) {
            $prop = self::getPrecisProperty($string, $pos);
            if (!$test($prop)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Tests if a string conforms to PRECIS IdentifierClass
     *
     * @param string $string the string to test
     *
     * @return bool true if the string conforms to PRECIS IdentifierClass
     */
    public static function isIdentifier($string)
    {
        return static::isClass($string, function ($prop) {
            return $prop === self::CPROP_PVALID;
        });
    }

    /**
     * Tests if a string conforms to PRECIS FreeformClass
     *
     * @param string $string the string to test
     *
     * @return bool true if the string conforms to PRECIS FreeformClass
     */
    public static function isFreeform($string)
    {
        return static::isClass($string, function ($prop) {
            return $prop === self::CPROP_PVALID || $prop === self::CPROP_FREE_PVAL;
        });
    }

    /**
     * Map fullwidth and halfwidth characters to their compatibility replacements.
     *
     * @param string $string the string to perform mapping on
     *
     * @return string the mapped string
     */
    public static function mapFullwidthHalfwidthToCompat($string)
    {
        return preg_replace_callback(
            '%[\x{FF00}-\x{FFEF}]%u',
            function ($matches) {
                return \Normalizer::normalize($matches[0], \Normalizer::FORM_KD);
            },
            $string
        );
    }

    /**
     * Prepares a string according to the PRECIS Username profles.
     *
     * @param string $string the string to prepare
     *
     * @return bool|string the prepared string or false if the string does not conform to the profile
     */
    protected static function prepareUsername($string)
    {
        if (!mb_check_encoding($string, 'UTF-8')) {
            return false;
        }

        $string = static::mapFullwidthHalfwidthToCompat($string);

        return static::isIdentifier($string) ? $string : false;
    }

    /**
     * Enforces one of the PRECIS Username profiles on a string.
     *
     * @param string $string the string to enforce
     * @param bool $caseMapped set true to enforce UsernameCaseMapped or false for UsernameCasePreserved
     *
     * @return bool|string the enforced string or false if the string does not conform to the profile
     */
    protected static function enforceUsername($string, $caseMapped)
    {
        // Prepare.
        $string = static::prepareUsername($string);
        if ($string === false) {
            return false;
        }

        // 1. Width-Mapping Rule: done with prepare.
        // 2. No additional mapping.

        // 3. Case-Mapping
        if ($caseMapped) {
            $string = CaseFold::fold($string, true);
        }

        // 4. Normalize: Normal Form C
        $string = \Normalizer::normalize($string, \Normalizer::FORM_C);

        // 5. Bidi
        if (!Bidi::rule($string)) {
            return false;
        }

        return $string;
    }

    /**
     * Prepares a string according to PRECIS UsernameCaseMapped profle.
     *
     * @param string $string the string to prepare
     *
     * @return bool|string the prepared string or false if the string does not conform to the profile
     */
    public static function prepareUsernameCaseMapped($string)
    {
        return static::prepareUsername($string);
    }

    /**
     * Prepares a string according to PRECIS UsernameCasePreserved profle.
     *
     * @param string $string the string to prepare
     *
     * @return bool|string the prepared string or false if the string does not conform to the profile
     */
    public static function prepareUsernameCasePreserved($string)
    {
        return static::prepareUsername($string);
    }

    /**
     * Prepares a string according to PRECIS OpaqueString profle for passwords.
     *
     * @param string $string the string to prepare
     *
     * @return bool|string the prepared string or false if the string does not conform to the profile
     */
    public static function prepareOpaqueString($string)
    {
        // Check encoding first because class check depends on valid UTF-8
        return mb_check_encoding($string, 'UTF-8') && static::isFreeform($string)
            ? $string
            : false;
    }

    /**
     * Prepares a string according to PRECIS Nickname profle.
     *
     * @param string $string the string to prepare
     *
     * @return bool|string the prepared string or false if the string does not conform to the profile
     */
    public static function prepareNickname($string)
    {
        return static::prepareOpaqueString($string);
    }

    /**
     * Enforces the PRECIS enforce UsernameCaseMapped on a string.
     *
     * @param string $string the string to enforce
     *
     * @return bool|string the enforced string or false if the string does not conform to the profile
     */
    public static function enforceUsernameCaseMapped($string)
    {
        return static::enforceUsername($string, true);
    }

    /**
     * Enforces the PRECIS UsernameCasePreserved on a string.
     *
     * @param string $string the string to enforce
     *
     * @return bool|string the enforced string or false if the string does not conform to the profile
     */
    public static function enforceUsernameCasePreserved($string)
    {
        return static::enforceUsername($string, false);
    }

    /**
     * Enforces the PRECIS OpaqueString profle for passwords on a string.
     *
     * @param string $string the string to enforce
     *
     * @return bool|string the enforced string or false if the string does not conform to the profile
     */
    public static function enforceOpaqueString($string)
    {
        // Prepare
        $string = static::prepareOpaqueString($string);
        if ($string === false) {
            return false;
        }

        // 1. No width mapping for OpaqueString

        // 2. Additional Mapping Rule: map all spaces to ascii space
        $string = preg_replace('%\p{Zs}%u', ' ', $string);

        // 3. No case mapping for OpaqueString

        // 4. Normalize: Normal Form C
        $string = \Normalizer::normalize($string, \Normalizer::FORM_C);

        // 5. No directionality rule

        return $string;
    }

    /**
     * Enforces the PRECIS Nickname profle on a string.
     *
     * @param string $string the string to enforce
     *
     * @return bool|string the enforced string or false if the string does not conform to the profile
     */
    public static function enforceNickname($string)
    {
        // Prepare
        $string = static::prepareNickname($string);
        if ($string === false) {
            return false;
        }

        // 1. No width mapping for Nickname

        // 2. Additional Mapping Rules
        $string = trim(preg_replace('%\p{Zs}+%u', ' ', $string));

        // 3. Case-Mapping
        $string = CaseFold::fold($string, true);

        // 4. Normalize: Normal Form KC
        $string = \Normalizer::normalize($string, \Normalizer::FORM_KC);

        // 5. No directionality rule

        return $string;
    }
}
