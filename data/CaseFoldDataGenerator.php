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

    protected $points = [];

    public function run()
    {
        $this->read();
        $this->write();
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

            $this->points[Precis::codePoint2utf8($matches[1])] = Precis::codePoint2utf8($matches[2]);
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
