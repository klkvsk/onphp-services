<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-11-13
 */
namespace OnPhp\Services\Codegen;

use \OnPhp\Services\Codegen\Php\PhpSourceCodeGenerator;
use OnPhp\Services\Meta\Exceptions\MetaConfigurationException;
use \OnPhp\Services\Meta\MetaConfigurationParser;

class SourceCodeTypeReference
{
    const REGEX_TYPENAME    = '[a-zA-Z_](?:[a-zA-Z_0-9\\.\\\\]*[a-zA-Z_0-9])?';

    const TYPE_STRING       = 'String';
    const TYPE_BOOLEAN      = 'Boolean';
    const TYPE_FLOAT        = 'Float';
    const TYPE_INTEGER      = 'Integer';
    const TYPE_DATE         = 'Date';
    const TYPE_DATETIME     = 'Timestamp';

    const TYPE_ENUM         = 'Enum';
    const TYPE_ENUMERATION  = 'Enumeration';
    const TYPE_STRUCTURE    = 'Structure';
    const TYPE_IDENTIFIER   = 'Identifier';

    protected static $internalTypes = [
        self::TYPE_STRING,
        self::TYPE_BOOLEAN,
        self::TYPE_FLOAT,
        self::TYPE_INTEGER,
        self::TYPE_DATE,
        self::TYPE_DATETIME,
    ];

    protected $typeId;
    protected $typeName;
    protected $array = false;
    private   $templateTypes = [];

    public static function parseTypename($typeName)
    {
        $reType = self::REGEX_TYPENAME;
        preg_match("/^({$reType})(?:\\(({$reType}(?:\\[\\])?[, ]?)+\\))?$/", $typeName, $match);

        if (!$match) {
            throw new \Exception('wrong type format: ' . $typeName);
        }

        $typeName = $match[1];
        if (isset($match[2])) {
            $template = preg_split('/\s*,\s*/', $match[2]);
        } else {
            $template = [];
        }

        return [ $typeName, $template ];
    }
    /**
     * @param string $typeName
     * @return static
     * @throws \Exception
     */
    public static function create($typeName)
    {
        $self = new static;
        $isArray = false;
        if (substr($typeName, -2) == '[]') {
            $typeName = substr($typeName, 0, -2);
            $isArray = true;
        }
        $self->array = $isArray;

        list ($typeName, $templateTypes) = self::parseTypename($typeName);

        $self->typeName = $typeName;
        foreach ($templateTypes as $templateType) {
            $self->templateTypes []= self::create($templateType);
        }

        return $self;
    }

    /**
     * @param SourceCodeTypeReference $typeRef
     * @return static
     */
    public static function wrap(self $typeRef)
    {
        $self = new static;
        $self->typeName = $typeRef->typeName;
        $self->typeId = $typeRef->typeId;
        $self->array = $typeRef->array;
        $self->templateTypes = $typeRef->templateTypes;

        return $self;
    }

    /**
     * @return string
     */
    public function getTypeName()
    {
        return $this->typeName;
    }

    /**
     * @return bool
     */
    public function isArray()
    {
        return $this->array;
    }

    /**
     * @return static[]
     */
    public function getTemplateTypes()
    {
        return $this->templateTypes;
    }

    /**
     * @param MetaConfigurationParser $metaConfig
     */
    final public function resolve(MetaConfigurationParser $metaConfig)
    {
        if (in_array($this->typeName, self::$internalTypes)) {
            $this->typeId = $this->typeName;
            return;
        }

        if ($metaConfig->getOnPhpCorePlugin()) {
            try {
                $coreClass = $metaConfig->getOnPhpCorePlugin()->getClassByName($this->typeName);
            } catch (\MissingElementException $e) {
                $coreClass = null;
            }
            if ($coreClass != null) {
                $coreClassPattern = $coreClass->getPattern();
                if ($coreClassPattern instanceof \EnumClassPattern) {
                    $this->typeId = self::TYPE_ENUM;
                    return;
                } else if ($coreClassPattern instanceof \EnumerationClassPattern) {
                    $this->typeId = self::TYPE_ENUMERATION;
                    return;
                } else if ($coreClassPattern instanceof \StraightMappingPattern) {
                    $this->typeId = self::TYPE_IDENTIFIER;
                    return;
                }
            }
        }

        $metaEnum = $metaConfig->getEnumByName($this->typeName);
        if ($metaEnum) {
            $this->typeId = PhpSourceCodeGenerator::$enumerationDefaultType;
            return;
        }

        $metaStructure = $metaConfig->getStructureByName($this->typeName);
        if ($metaStructure) {
            $this->typeId = self::TYPE_STRUCTURE;
            if ($this->getTemplateTypes()) {
                \Assert::isTrue($metaStructure->isTemplated(), 'trying to use template on non-templated structure');
                $case = $metaStructure->addTemplateCase($this->getTemplateTypes());
                foreach ($case->getProperties() as $metaProperty) {
                    $metaProperty->getType()->resolve($metaConfig);
                }
            }
            return;
        }

        throw new \UnexpectedValueException('can not resolve type ' . $this->typeName);
    }

}