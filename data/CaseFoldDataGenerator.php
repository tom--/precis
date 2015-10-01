<?php
/**
 * @copyright Copyright (c) 2015 Spinitron LLC
 * @license ISC https://opensource.org/licenses/ISC
 */

namespace spinitron\precis\data;

use spinitron\precis\Precis;

class CaseFoldDataGenerator extends DataGenerator
{
    public $ucdFileUrl = 'http://www.unicode.org/Public/UCD/latest/ucd/CaseFolding.txt';
    // public $ucdFileUrl = 'http://www.unicode.org/Public/7.0.0/ucd/CaseFolding.txt';

    protected $points = [];

    public function run()
    {
        $this->read();
        $this->write();
    }

    /**
     * @param string $char
     *
     * @return string Unicode core property of the class, e.g.
     */
    public static function getCoreProperty($char)
    {
        static $php7;

        if ($php7) {
            return static::getCorePropertyPhp7($char);
        } elseif ($php7 === null) {
            $php7 = method_exists('\IntlChar', 'getPropertyValueName');
        }

        static $props;
        static $propPattern;

        if ($propPattern === null) {
            $props = str_split('LlLuLtLmLoZsZlZpNdNlNoPcPdPePfPiPoPsMcMeMnScSkSmSoCcCfCnCoCsLl', 2);
            $classes = [];
            foreach ($props as $prop) {
                $classes[] = '(\p{' . $prop . '})';
            }
            $propPattern = '%(?:' . implode('|', $classes) . ')%u';
        }

        preg_match($propPattern, $char, $matches);
        $matches = array_flip($matches);

        return $props[$matches[$char] - 1];
    }

    public static function getCorePropertyPhp7($char)
    {
        return \IntlChar::getPropertyValueName(
            \IntlChar::PROPERTY_GENERAL_CATEGORY,
            \IntlChar::charType($char),
            \IntlChar::LONG_PROPERTY_NAME
        );
    }

    protected function read()
    {

        // 0041; C; 0061; # LATIN CAPITAL LETTER A
        $pattern = '{^([0-9A-F]{4,5}); [CS]; ([0-9A-F]{4,5});}';
        while (!feof($this->input)) {
            $buffer = fgets($this->input);
            if ($buffer === false) {
                break;
            }

            if (!preg_match($pattern, $buffer, $matches)) {
                continue;
            }

            $from = Precis::codePoint2utf8($matches[1]);
            $this->points[$from] = Precis::codePoint2utf8($matches[2]);

            // if (!preg_match('%[\p{Lt}\p{Lu}]%u', $from)) {
            //     echo(static::getCorePropertyPhp7($from) . '; ' . $buffer);
            // }
        }
    }

    protected function write()
    {
        $namespace = $this->namespace ? 'namespace ' . $this->namespace . ';' : '';
        $points = var_export($this->points, true);

        echo <<<PHP
<?php
/**
 * @copyright Copyright (c) 2015 Spinitron LLC
 * @license ISC https://opensource.org/licenses/ISC
 */

$namespace

trait CaseFoldDataTrait
{
    public static \$ucdSourceFileHeader = <<<'TEXT'
$this->header
TEXT;

    public static \$codePoints = $points;
}

PHP;
    }
}
