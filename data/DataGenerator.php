<?php
/**
 * @copyright Copyright (c) 2015 Spinitron LLC
 * @license ISC https://opensource.org/licenses/ISC
 */

namespace spinitron\precis\data;

class DataGenerator
{
    public $ucdFileUrl;
    public $ucdLocalFile;
    public $namespace = 'spinitron\precis';

    protected $input;
    protected $header;

    public function __construct()
    {
        if (!$this->ucdLocalFile && $this->ucdFileUrl) {
            $this->ucdLocalFile = __DIR__ . '/' . basename($this->ucdFileUrl);
        }

        if ($this->ucdLocalFile) {
            if (!file_exists($this->ucdLocalFile)) {
                file_put_contents($this->ucdLocalFile, fopen($this->ucdFileUrl, 'r'));
            }

            $this->input = fopen($this->ucdLocalFile, 'r');
            if ($this->input === false) {
                throw new \ErrorException("Cannot open file: '$this->ucdLocalFile'\n");
            }
        } else {
            $this->input = STDIN;
        }

        $this->header = '';
        do {
            $buffer = fgets($this->input);
            $this->header .= $buffer;
        } while(strpos($buffer, '====================') === false);

    }
}
