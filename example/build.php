<?php
/// Standalone builder (alternative to using onPHP builder plugin)

define('BUILDER', 1);
require dirname(__FILE__) . '/config.php';
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 'On');

$parser = new \OnPhp\Services\Meta\MetaConfigurationParser;
$parser
//    ->parseFile(dirname(__FILE__) . '/services.xml')
//    ->parseFile(dirname(__FILE__) . '/enums.xml')
//    ->parseFile(dirname(__FILE__) . '/structures.xml')
    ->parseFile(dirname(__FILE__) . '/templates.xml')
    ->finalize()
    ->selfCheck();

$phpGen = new \OnPhp\Services\Codegen\Php\PhpSourceCodeGenerator();
$phpGen
    ->setAutoPath(dirname(__FILE__) . '/out/Auto/')
    ->setOncePath(dirname(__FILE__) . '/out/');
foreach ($parser->getContainers() as $container) {
    echo 'Building ' . $container->getNameForClass() . PHP_EOL;
    $generated = $phpGen->buildContainer($container);
    foreach ($generated as $file => $isUpdated) {
        echo ' - ' . $file . ': ' . ($isUpdated ? 'UPDATED' : 'OK') . PHP_EOL;
    }
    foreach ($container->getServices() as $service) {
        echo 'Building ' . $service->getNameForClass() . PHP_EOL;
        $generated = $phpGen->buildService($service);
        foreach ($generated as $file => $isUpdated) {
            echo ' - ' . $file . ': ' . ($isUpdated ? 'UPDATED' : 'OK') . PHP_EOL;
        }
    }
}
foreach ($parser->getEnums() as $enum) {
    echo 'Building ' . $enum->getName() . PHP_EOL;
    $generated = $phpGen->buildEnum($enum);
    foreach ($generated as $file => $isUpdated) {
        echo ' - ' . $file . ': ' . ($isUpdated ? 'UPDATED' : 'OK') . PHP_EOL;
    }
}

$buildStructure = function (\OnPhp\Services\Meta\MetaStructure $structure) use ($phpGen, &$buildStructure) {
    if ($structure->isTemplated()) {
        echo 'Template for ' . $structure->getName() . PHP_EOL;
        foreach ($structure->getTemplateCases() as $case) {
            $buildStructure($case);
        }
        return;
    }
    echo 'Building ' . $structure->getName() . PHP_EOL;
    $generated = $phpGen->buildStructure($structure);
    foreach ($generated as $file => $isUpdated) {
        echo ' - ' . $file . ': ' . ($isUpdated ? 'UPDATED' : 'OK') . PHP_EOL;
    }
};

foreach ($parser->getStructures() as $structure) {
    $buildStructure($structure);
}

echo "done!\n";