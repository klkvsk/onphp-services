<?php
// main config
require dirname(__FILE__) . '/../../vendor/onphp/onphp-framework/config.inc.php';

// plugin config
require dirname(__FILE__) . '/../../include.php';

//\OnPhp\Services\Codegen\Php\PhpSourceCodeGenerator::$localizationFunction = '__';
\OnPhp\Services\Codegen\Php\PhpSourceCodeGenerator::$enumerationDefaultType = 'Enum';