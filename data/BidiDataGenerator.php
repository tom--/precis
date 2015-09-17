<?php
/**
 * @copyright Copyright (c) 2015 Spinitron LLC
 * @license ISC https://opensource.org/licenses/ISC
 */

namespace spinitron\precis\data;

class BidiDataGenerator extends DataGenerator
{
    public $ucdFileUrl = 'http://www.unicode.org/Public/UCD/latest/ucd/extracted/DerivedBidiClass.txt';

    protected $header;
    protected $comments;
    protected $points = [];
    protected $ranges = [];
    protected $unassigned = [];

    public function run()
    {
        $this->read();
        $this->unassignedRanges();
        $this->compress();
        $this->write();
    }

    protected function read()
    {
        $this->comments = '';
        do {
            $buffer = fgets($this->input);
            $this->comments .= $buffer;
        } while(strpos($buffer, '====================') === false);

        $pattern = '{^([0-9A-F]{4,6})(?:\.\.([0-9A-F]{4,6}))? *; *([A-Z]{1,3}) *# }';
        while (!feof($this->input)) {
            $buffer = fgets($this->input);
            if ($buffer === false) {
                break;
            }

            if (!preg_match($pattern, $buffer, $matches)) {
                continue;
            }

            /** @noinspection PhpUnusedLocalVariableInspection */
            list($ignore, $first, $last, $class) = $matches;

            if ($class === 'L') {
                // L is default so if search finds nothing, right result.
                continue;
            }

            $first = hexdec($first);
            if ($last) {
                $last = hexdec($last);
                $this->ranges[] = [$first, $last, $class];
            } else {
                $this->points[$first] = $class;
            }
        }
    }

    protected function unassignedRanges()
    {
        $pattern = 'unassigned code points that default to (AL|R|ET) are in the ranges?:\s*#\s*\[([^\]]+)\]';
        preg_match_all("%$pattern%u", $this->comments, $matches, PREG_SET_ORDER);

        $pattern = '%\\\\[uU]([0-9A-F]{4,8})-\\\\[uU]([0-9A-F]{4,8})%u';
        foreach ($matches as $match) {
            preg_match_all($pattern, $match[2], $ranges, PREG_SET_ORDER);
            foreach ($ranges as $range) {
                $this->unassigned[] = [hexdec($range[1]), hexdec($range[2]), $match[1]];
            }
        }
    }

    protected function compress()
    {
        ksort($this->points);

        $lastCp = -10;
        $lastClass = false;
        $run = [];
        foreach ($this->points as $cp => $class) {
            if ($cp !== $lastCp + 1 || $class !== $lastClass) {
                if (count($run) > 1) {
                    $this->ranges[] = [$run[0], end($run), $lastClass];
                    foreach ($run as $runCp) {
                        unset($this->points[$runCp]);
                    }
                }
                $run = [];
            }
            $run[] = $cp;
            $lastCp = $cp;
            $lastClass = $class;
        }

        sort($this->ranges);

        $lastCp = -10;
        $lastClass = false;
        $runStart = -10;
        foreach ($this->ranges as $i => $range) {
            if ($range[0] !== $lastCp + 1 || $range[2] !== $lastClass) {
                $runStart = $range[0];
            } else {
                $this->ranges[$i][0] = $runStart;
                unset($this->ranges[$i - 1]);
            }
            $lastCp = $range[1];
            $lastClass = $range[2];
        }
    }

    protected function write()
    {
        $namespace = $this->namespace ? 'namespace ' . $this->namespace . ';' : '';
        $points = var_export($this->points, true);

        $ranges = "[\n";
        foreach ($this->ranges as $range) {
            $ranges .= "        [{$range[0]}, {$range[1]}, '{$range[2]}'],\n";
        }
        $ranges .= '    ]';

        $unassigned = "[\n";
        foreach ($this->unassigned as $range) {
            $unassigned .= "        [{$range[0]}, {$range[1]}, '{$range[2]}'],\n";
        }
        $unassigned .= '    ]';

        echo <<<PHP
<?php
/**
 * @copyright Copyright (c) 2015 Spinitron LLC
 * @license ISC https://opensource.org/licenses/ISC
 */

$namespace

trait BidiDataTrait
{
    public static \$ucdSourceFileHeader = <<<'TEXT'
$this->header
TEXT;

    public static \$codePoints = $points;

    public static \$codePointRanges = $ranges;

    public static \$unassignedDefaultRanges = $unassigned;
}

PHP;
    }
}
