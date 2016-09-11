<?php
namespace OnPhp\Services\Meta;

use \OnPhp\Services\Base\ServiceActionParamSource;
use \OnPhp\Services\Meta\Exceptions\MetaConfigurationException;

class MetaConfigurationParser
{
    protected $onphpCorePlugin = null;

    /** @var MetaServiceContainer[] */
    protected $containers = [];
    /** @var MetaStructure[] */
    protected $structures = [];
    /** @var MetaEnum[] */
    protected $enums = [];

    /**
     * @return \MetaConfigurationCorePlugin
     */
    public function getOnPhpCorePlugin()
    {
        return $this->onphpCorePlugin;
    }

    public function setOnPhpCorePlugin($onphpCorePlugin)
    {
        \Assert::isInstance($onphpCorePlugin, \MetaConfigurationCorePlugin::class);
        $this->onphpCorePlugin = $onphpCorePlugin;
        return $this;
    }

    public function getContainers()
    {
        return $this->containers;
    }

    public function getStructures()
    {
        return $this->structures;
    }

    public function getEnums()
    {
        return $this->enums;
    }

    public function getContainerByName($name)
    {
        foreach ($this->getContainers() as $container) {
            if ($container->getName() == $name) {
                return $container;
            }
        }
        return null;
    }

    public function getEnumByName($name)
    {
        foreach ($this->getEnums() as $enum) {
            if ($enum->getName() == $name) {
                return $enum;
            }
        }
        return null;
    }

    public function getStructureByName($name)
    {
        foreach ($this->getStructures() as $structure) {
            if ($structure->getName() == $name) {
                return $structure;
            }
        }
        return null;
    }

    public function parseFile($filename)
    {
        $xml = \simplexml_load_file($filename);
        $this->parseXmlNode($xml);

        return $this;
    }

    protected function parseAccess(\SimpleXMLElement $accessXmlNode)
    {
        $types = [];
        if ($accessXmlNode->allow[0]) {
            foreach ($accessXmlNode->allow as $accessAllowXml) {
                $types [] = (string)$accessAllowXml['type'];
            }
        } else {
            throw new \Exception('access without rules');
        }
        $isOverride = ($accessXmlNode['override'] == 'true');
        return MetaServiceAccess::create($types, $isOverride);
    }

    protected function parseProperty(\SimpleXMLElement $propertyXmlNode)
    {
        return MetaProperty::create(
            (string)$propertyXmlNode['name'],
            (string)$propertyXmlNode['type'],
            (bool)($propertyXmlNode['required'] != 'false'),
            (string)$propertyXmlNode['default'] ?: null
        );
    }

    protected function parseServices(\SimpleXMLElement $serviceContainerXml)
    {
        $metaServiceContainer = MetaServiceContainer::create(
            (string)$serviceContainerXml['name']
        );


        if ($this->getEnumByName($metaServiceContainer->getName())) {
            throw new MetaConfigurationException(
                'container "' . $metaServiceContainer->getName() . '" is already defined', $serviceContainerXml
            );
        }

        if ($serviceContainerXml->service[0]) {
            foreach ($serviceContainerXml->service as $serviceXml) {
                $metaService = MetaService::create(
                    (string)$serviceXml['name'],
                    (string)$serviceXml['http-path']
                );

                if ($serviceXml->access) {
                    $metaServiceAccess = $this->parseAccess($serviceXml->access[0]);
                    $metaService->setAccess($metaServiceAccess);
                }

                if ($serviceXml->action[0]) {
                    foreach ($serviceXml->action as $serviceActionXml) {
                        $metaServiceAction = MetaServiceAction::create(
                            (string)$serviceActionXml['name'],
                            (string)$serviceActionXml['http-method'],
                            (string)$serviceActionXml['http-path']
                        );

                        if ($serviceActionXml->access) {
                            $metaServiceActionAccess = $this->parseAccess($serviceActionXml->access[0]);
                            $metaServiceAction->setAccess($metaServiceActionAccess);
                        }

                        if ($serviceActionXml->param[0]) {
                            foreach ($serviceActionXml->param as $serviceActionParamXml) {
                                $metaServiceActionParamProperty = $this->parseProperty($serviceActionParamXml);
                                $metaServiceActionParamSource = ServiceActionParamSource::create(
                                    (string)$serviceActionParamXml['from'] ?: ServiceActionParamSource::_DEFAULT
                                );
                                $metaServiceActionParam = MetaServiceActionParam::create(
                                    $metaServiceActionParamProperty,
                                    $metaServiceActionParamSource
                                );
                                $metaServiceAction->addParam($metaServiceActionParam);
                            }
                        }

                        $metaService->addAction($metaServiceAction);

                        if ($serviceActionXml->return[0]) {
                            $metaServiceActionReturnProperty = $this->parseProperty($serviceActionXml->return[0]);
                            $metaServiceActionReturn = MetaServiceActionReturn::create($metaServiceActionReturnProperty);
                            $metaServiceAction->setReturn($metaServiceActionReturn);
                        } else {
                            throw new \Exception('action without return');
                        }
                    }
                } else {
                    throw new \Exception('service without actions');
                }
                $metaServiceContainer->addService($metaService);
            }

        } else {
            throw new \Exception('container without services');
        }
        $this->containers [] = $metaServiceContainer;

        return $this;
    }

    public function parseEnum(\SimpleXMLElement $enumXml)
    {
        $metaEnum = MetaEnum::create(
            (string)$enumXml['name']
        );

        if ($this->getEnumByName($metaEnum->getName())) {
            throw new MetaConfigurationException('enum "' . $metaEnum->getName() . '" is already defined', $enumXml);
        }

        foreach ($enumXml->value as $enumValueXml) {
            $metaEnum->addValue(
                MetaEnumValue::create(
                    (string)$enumValueXml['id'],
                    (string)$enumValueXml['const'],
                    (string)$enumValueXml['name']
                )
            );
        }

        $this->enums [] = $metaEnum;

        return $this;
    }

    public function parseStructure(\SimpleXMLElement $structureXml)
    {
        $parentName = (string)$structureXml['extends'];
        $parent = null;
        if ($parentName) {
            $parent = $this->getStructureByName($parentName);
            if ($parent == null) {
                throw new MetaConfigurationException(
                    'could not find parent structure "' . $parentName . '"', $structureXml
                );
            }
        }
        $metaStructure = MetaStructure::create(
            (string)$structureXml['name'],
            $parent
        );

        if ($this->getStructureByName($metaStructure->getName())) {
            throw new MetaConfigurationException(
                'structure "' . $metaStructure->getName() . '" is already defined',
                $structureXml);
        }

        foreach ($structureXml->property as $structurePropertyXml) {
            $metaStructure->addProperty(
                $this->parseProperty($structurePropertyXml)
            );
        }

        $this->structures [] = $metaStructure;

        return $this;
    }

    public function parseXmlNode(\SimpleXMLElement $xml)
    {
        if ($xml->servicecontainer[0]) {
            foreach ($xml->servicecontainer as $serviceContainerXml) {
                $this->parseServices($serviceContainerXml);
            }
        }

        if ($xml->enum[0]) {
            foreach ($xml->enum as $enumXml) {
                $this->parseEnum($enumXml);
            }
        }

        if ($xml->structure[0]) {
            foreach ($xml->structure as $structureXml) {
                $this->parseStructure($structureXml);
            }
        }

        return $this;
    }

    public function finalize()
    {
        foreach ($this->getStructures() as $structure) {
            if ($structure->isTemplated()) {
                continue;
            }
            foreach ($structure->getProperties() as $property) {
                $property->getType()->resolve($this);
            }
        }
        foreach ($this->getContainers() as $container) {
            foreach ($container->getServices() as $service) {
                foreach ($service->getActions() as $action) {
                    foreach ($action->getParams() as $param) {
                        $param->getProperty()->getType()->resolve($this);
                    }
                    $action->getReturn()->getProperty()->getType()->resolve($this);
                }
            }
        }
        return $this;
    }

    public function selfCheck()
    {
        return $this;
    }

}