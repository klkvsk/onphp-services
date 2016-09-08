<?php
// main config
require dirname(__FILE__) . '/../vendor/klkvsk/onphp-framework/config.inc.php';
// plugin config
require dirname(__FILE__) . '/../include.php';

\OnPhp\Services\Codegen\Php\PhpSourceCodeGenerator::$localizationFunction = '__';
