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

/** @var \OnPhp\Services\Codegen\ISourceCodeGenerator[] $generators */
$generators = [
    (new \OnPhp\Services\Codegen\Php\PhpSourceCodeGenerator())
        ->setAutoPath(dirname(__FILE__) . '/../server/src/out/Auto/')
        ->setOncePath(dirname(__FILE__) . '/../server/src/out/'),
    (new \OnPhp\Services\Codegen\Typescript\TypescriptSourceCodeGenerator())

];

foreach ($generators as $gen) {
    echo 'GENERATOR: ' . get_class($gen) . PHP_EOL;
    foreach ($parser->getContainers() as $container) {
        echo 'Building ' . $container->getNameForClass() . PHP_EOL;
        $generated = $gen->buildContainer($container);
        foreach ($generated as $file => $isUpdated) {
            echo ' - ' . $file . ': ' . ($isUpdated ? 'UPDATED' : 'OK') . PHP_EOL;
        }
        foreach ($container->getServices() as $service) {
            echo 'Building ' . $service->getNameForClass() . PHP_EOL;
            $generated = $gen->buildService($service);
            foreach ($generated as $file => $isUpdated) {
                echo ' - ' . $file . ': ' . ($isUpdated ? 'UPDATED' : 'OK') . PHP_EOL;
            }
        }
    }
}

foreach ($generators as $gen) {
    echo 'GENERATOR: ' . get_class($gen) . PHP_EOL;
    foreach ($parser->getEnums() as $enum) {
        echo 'Building ' . $enum->getName() . PHP_EOL;
        $generated = $gen->buildEnum($enum);
        foreach ($generated as $file => $isUpdated) {
            echo ' - ' . $file . ': ' . ($isUpdated ? 'UPDATED' : 'OK') . PHP_EOL;
        }
    }
}

$buildStructure = function (
    \OnPhp\Services\Codegen\ISourceCodeGenerator    $gen,
    \OnPhp\Services\Meta\MetaStructure              $structure
) use (
    &$buildStructure
) {
    if ($structure->isTemplated()) {
        echo 'Template for ' . $structure->getName() . PHP_EOL;
        foreach ($structure->getTemplateCases() as $case) {
            $buildStructure($gen, $case);
        }
        return;
    }
    echo 'Building ' . $structure->getName() . PHP_EOL;
    $generated = $gen->buildStructure($structure);
    foreach ($generated as $file => $isUpdated) {
        echo ' - ' . $file . ': ' . ($isUpdated ? 'UPDATED' : 'OK') . PHP_EOL;
    }
};

foreach ($generators as $gen) {
    echo 'GENERATOR: ' . get_class($gen) . PHP_EOL;
    foreach ($parser->getStructures() as $structure) {
        $buildStructure($gen, $structure);
    }
}

echo "done!\n";