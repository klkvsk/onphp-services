<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-12-03
 */
namespace OnPhp\Services\Codegen\Php;

use \OnPhp\Services\Codegen\ISourceCodeGenerator;
use \OnPhp\Services\Meta\MetaEnum;
use \OnPhp\Services\Meta\MetaServiceContainer;
use \OnPhp\Services\Meta\MetaService;
use \OnPhp\Services\Meta\MetaStructure;

class PhpSourceCodeGenerator implements ISourceCodeGenerator
{

    /**
     * Set this to function or method name, that you use to translate strings
     * Enum names and other translatable strings would be wrapped in this call
     * @var string|null
     */
    public static $localizationFunction = '/* _*/';
    public static $enumerationDefaultType = PhpSourceCodeTypeReference::TYPE_ENUM; // or Enumeration

    protected $oncepath;
    protected $autopath;

    /**
     * @return string
     */
    public function getOncePath()
    {
        return $this->oncepath;
    }

    /**
     * @param string $oncepath
     * @return $this
     */
    public function setOncePath($oncepath)
    {
        $this->oncepath = $oncepath;
        return $this;
    }

    /**
     * @return string
     */
    public function getAutoPath()
    {
        return $this->autopath;
    }

    /**
     * @param string $autopath
     * @return $this
     */
    public function setAutoPath($autopath)
    {
        $this->autopath = $autopath;
        return $this;
    }

    public function buildContainer(MetaServiceContainer $container)
    {
        $containerBuilder = PhpServiceContainerSourceCodeBuilder::create($container);
        $containerBuilder->build();
        $classRef = PhpSourceCodeClassReference::wrap($container->getClassRef());
        $filepath = $this->autopath . 'Services/' . $classRef->getFilePath();
        $isUpdated = $containerBuilder->saveTo($filepath, true);
        return [$filepath => $isUpdated];
    }

    public function buildService(MetaService $service)
    {
        $interfaceBuilder = PhpServiceInterfaceSourceCodeBuilder::create($service);
        $interfaceBuilder->build();
        $interfaceClassRef = PhpSourceCodeClassReference::wrap($service->getClassRef());
        $interfaceFilepath = $this->autopath . 'Services/' . $interfaceClassRef->getFilePath();
        $interfaceFileUpdated = $interfaceBuilder->saveTo($interfaceFilepath, true);

        $dependencyTraitBuilder = PhpServiceDependenceTraitSourceCodeBuilder::create($service);
        $dependencyTraitBuilder->build();
        $dependencyTraitClassRef = PhpSourceCodeClassReference::wrap($service->getDependencyClassRef());
        $dependencyTraitFilepath = $this->autopath . 'Services/' . $dependencyTraitClassRef->getFilePath();
        $dependencyTraitFileUpdated = $dependencyTraitBuilder->saveTo($dependencyTraitFilepath, true);

        $proxyClassBuilder = PhpServiceProxySourceCodeBuilder::create($service);
        $proxyClassBuilder->build();
        $proxyClassRef = PhpSourceCodeClassReference::wrap($service->getProxyClassRef());
        $proxyClassFilepath = $this->autopath . 'Services/' . $proxyClassRef->getFilePath();
        $proxyClassFileUpdated = $proxyClassBuilder->saveTo($proxyClassFilepath, true);

        $implClassBuilder = PhpServiceImplSourceCodeBuilder::create($service);
        $implClassBuilder->build();
        $implClassRef = PhpSourceCodeClassReference::wrap($service->getImplClassRef());
        $implClassFilepath = $this->oncepath . 'Services/' . $implClassRef->getFilePath();
        $implClassFileUpdated = $implClassBuilder->saveTo($implClassFilepath, false);

        return [
            $interfaceFilepath => $interfaceFileUpdated,
            $dependencyTraitFilepath => $dependencyTraitFileUpdated,
            $proxyClassFilepath => $proxyClassFileUpdated,
            $implClassFilepath => $implClassFileUpdated,
        ];
    }

    public function buildStructure(MetaStructure $structure)
    {
        $autoProtoBuilder = PhpStructureAutoEntityProtoClassSourceCodeBuilder::create($structure);
        $autoProtoBuilder->build();
        $autoProtoClassRef = PhpSourceCodeClassReference::wrap($structure->getClassRef()->addClassNamePrefix('AutoEntityProto'));
        $autoProtoFilepath = $this->autopath . 'Structures/Proto/' . $autoProtoClassRef->getFilePath();
        $autoProtoFileUpdated = $autoProtoBuilder->saveTo($autoProtoFilepath, true);

        $autoClassBuilder = PhpStructureAutoClassSourceCodeBuilder::create($structure);
        $autoClassBuilder->build();
        $autoClassRef = PhpSourceCodeClassReference::wrap($structure->getClassRef())->addClassNamePrefix('Auto');
        $autoClassFilepath = $this->autopath . 'Structures/' . $autoClassRef->getFilePath();
        $autoClassFileUpdated = $autoClassBuilder->saveTo($autoClassFilepath, true);

        $entityProtoClassBuilder = PhpStructureEntityProtoClassSourceCodeBuilder::create($structure);
        $entityProtoClassBuilder->build();
        $entityProtoClassRef = PhpSourceCodeClassReference::wrap($structure->getClassRef())->addClassNamePrefix('EntityProto');
        $entityProtoFilepath = $this->oncepath . 'Structures/Proto/' . $entityProtoClassRef->getFilePath();
        $entityProtoFileUpdated = $entityProtoClassBuilder->saveTo($entityProtoFilepath, false);

        $structureClassBuilder = PhpStructureClassSourceCodeBuilder::create($structure);
        $structureClassBuilder->build();
        $structureClassRef = PhpSourceCodeClassReference::wrap($structure->getClassRef());
        $structureFilepath = $this->oncepath . 'Structures/' . $structureClassRef->getFilePath();
        $structureFileUpdated = $structureClassBuilder->saveTo($structureFilepath, false);

        return [
            $autoProtoFilepath => $autoProtoFileUpdated,
            $autoClassFilepath => $autoClassFileUpdated,
            $entityProtoFilepath => $entityProtoFileUpdated,
            $structureFilepath => $structureFileUpdated,
        ];
    }

    public function buildEnum(MetaEnum $enum, $type = null, $spooked = false)
    {
        $type = $type ?: self::$enumerationDefaultType;

        $enumInterfaceBuilder = PhpEnumInterfaceSourceCodeBuilder::create($enum);
        $enumInterfaceBuilder->build();
        $enumInterfaceClassRef = PhpSourceCodeClassReference::wrap($enum->getValuesClassRef());
        $enumInterfaceFilepath = $this->autopath . 'Enums/' . $enumInterfaceClassRef->getFilePath();
        $enumInterfaceFileUpdated = $enumInterfaceBuilder->saveTo($enumInterfaceFilepath, true);

        $enumTraitBuilder = PhpEnumTraitSourceCodeBuilder::create($enum, $type);
        $enumTraitBuilder->build();
        $enumTraitClassRef = PhpSourceCodeClassReference::wrap($enum->getTraitClassRef());
        $enumTraitFilepath = $this->autopath . 'Enums/' . $enumTraitClassRef->getFilePath();
        $enumTraitFileUpdated = $enumTraitBuilder->saveTo($enumTraitFilepath, true);

        $result = [
            $enumInterfaceFilepath => $enumInterfaceFileUpdated,
            $enumTraitFilepath => $enumTraitFileUpdated,
        ];

        if (!$spooked) {
            $enumClassBuilder = PhpEnumClassSourceCodeBuilder::create($enum, $type);
            $enumClassBuilder->build();
            $enumClassClassRef = PhpSourceCodeClassReference::wrap($enum->getClassRef());
            $enumClassFilepath = $this->oncepath . 'Enums/' . $enumClassClassRef->getFilePath();
            $enumClassFileUpdated = $enumClassBuilder->saveTo($enumClassFilepath, false);

            $result[$enumClassFilepath] = $enumClassFileUpdated;
        }

        return $result;
    }

}