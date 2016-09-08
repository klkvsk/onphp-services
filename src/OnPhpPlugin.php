<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-11-11
 */

namespace OnPhp\Services;

use OnPhp\Services\Codegen\ISourceCodeGenerator;
use OnPhp\Services\Codegen\Php\PhpSourceCodeGenerator;

class OnPhpPlugin implements \MetaConfigurationPluginInterface
{
    /** @var $meta      \MetaConfiguration MetaConfigurationParser */
    protected $meta;
    /** @var $parser     Meta\MetaConfigurationParser */
    protected $parser;
    /** @var $generators Codegen\ISourceCodeGenerator[] */
    protected $generators = [];

    public function __construct(\MetaConfiguration $metaConfiguration)
    {
        $this->meta = $metaConfiguration;
        $this->parser = new Meta\MetaConfigurationParser();
        $this->parser->setOnPhpCorePlugin($this->meta->getCorePlugin());
        $this->generators = [
            'PHP' =>
                (new Codegen\Php\PhpSourceCodeGenerator())
                    ->setAutoPath(ONPHP_META_AUTO_DIR)
                    ->setOncePath(PATH_CLASSES)
            ,
            'TypeScript' =>
                (new Codegen\Typescript\TypescriptSourceCodeGenerator())
            ,
        ];
    }

    public function getDtdMapping()
    {
        $servicesDtdPath = realpath(dirname(__FILE__) . '/../services.dtd');
        \Assert::isNotFalse($servicesDtdPath, 'could not resolve "services.dtd" path');
        return [
            'services.dtd' => $servicesDtdPath
        ];
    }

    public function loadConfig(\SimpleXMLElement $config, $metafile, $generate)
    {
        $this->parser->parseXmlNode($config);
    }

    public function checkConfig()
    {
        $this->parser
            ->finalize()
            ->selfCheck();
    }

    public function buildFiles()
    {
        $this->meta->getOutput()->infoLine("Building service containers: ");

        foreach ($this->parser->getContainers() as $container) {
            $this->getLogger()->infoLine("\t" . $container->getName() . ': ');

            $this->generate(
                function (ISourceCodeGenerator $generator) use ($container) {
                    return $generator->buildContainer($container);
                }
            );

            $this->meta->getOutput()->infoLine("\t Building services in {$container->getName()} container: ");
            foreach ($container->getServices() as $service) {
                $this->getLogger()->infoLine("\t\t" . $service->getNameForClass() . ': ');

                $this->generate(
                    function (ISourceCodeGenerator $generator) use ($service) {
                        return $generator->buildService($service);
                    }
                );
            }
        }

        $this->meta->getOutput()->infoLine("Building enumerations: ");
        foreach ($this->parser->getEnums() as $enum) {
            $this->getLogger()->infoLine("\t" . $enum->getName() . ': ');
            $this->generate(
                function (ISourceCodeGenerator $generator) use ($enum) {
                    if ($generator instanceof PhpSourceCodeGenerator) {
                        // If Enum or Enumeration is already defined in core plugin's metaconfiguration
                        // then we should not build another class with same name
                        // Also, we should check existing class' pattern to generate proper trait for it to use
                        try {
                            $coreClass = \MetaConfiguration::me()->getCorePlugin()->getClassByName($enum->getName());
                            if ($coreClass->getPattern() instanceof \EnumerationClassPattern) {
                                $type = 'Enumeration';
                            } else if ($coreClass->getPattern() instanceof \EnumClassPattern) {
                                $type = 'Enum';
                            } else {
                                throw new \WrongStateException($enum->getName()
                                    . ' is defined in core meta, but its pattern is unknown enum pattern'
                                );
                            }
                            return $generator->buildEnum($enum, $type, true);
                        } catch (\MissingElementException $e) {
                            // not defined in core meta
                        }
                    }

                    return $generator->buildEnum($enum);
                }
            );
        }

        $this->meta->getOutput()->infoLine("Building structures: ");
        foreach ($this->parser->getStructures() as $structure) {
            $this->getLogger()->infoLine("\t" . $structure->getName() . ': ');
            $this->generate(
                function (ISourceCodeGenerator $generator) use ($structure) {
                    return $generator->buildStructure($structure);
                }
            );
        }

    }

    public function checkIntegrity()
    {
        // TODO: Implement checkIntegrity() method.
    }

    public function checkForStaleFiles($drop)
    {
        // TODO: Implement checkForStaleFiles() method.
    }


    public function getLogger()
    {
        return $this->meta->getOutput();
    }

    protected function generate(callable $generatorCall)
    {
        foreach ($this->generators as $generatorType => $generator) {
            $this->getLogger()->infoLine("\t -> [" . $generatorType . ']: ');
            $files = $generatorCall($generator);
            foreach ($files as $filepath => $isUpdated) {
                ($isUpdated)
                    ? $this->getLogger()->warningLine("\t\t - " . $filepath)
                    : $this->getLogger()->infoLine("\t\t - " . $filepath);
            }
            if (!$files) {
                $this->getLogger()->infoLine("\t\t - (no files)");
            }
        }
    }

}