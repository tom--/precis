<?php

namespace spinitron\precis\data;

require(__DIR__ . '/../vendor/autoload.php');
$generator = new BidiDataGenerator();
$generator->run();
