<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-11-13
 */
namespace OnPhp\Services\Codegen;

use \OnPhp\Services\Codegen\Php\PhpSourceCodeGenerator;
use \OnPhp\Services\Meta\MetaConfigurationParser;

class SourceCodeTypeReference
{
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


    public static function create($typeName)
    {
        $self = new static;
        $isArray = false;
        if (substr($typeName, -2) == '[]') {
            $typeName = substr($typeName, 0, -2);
            $isArray = true;
        }

        $self->typeName = $typeName;
        $self->array = $isArray;

        return $self;
    }

    public static function wrap(self $typeRef)
    {
        $self = new static;
        $self->typeName = $typeRef->typeName;
        $self->typeId = $typeRef->typeId;
        $self->array = $typeRef->array;

        return $self;
    }

    public function getTypeName()
    {
        return $this->typeName;
    }

    public function isArray()
    {
        return $this->array;
    }

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

        if ($metaConfig->getEnumByName($this->typeName)) {
            $this->typeId = PhpSourceCodeGenerator::$enumerationDefaultType;
            return;
        }

        if ($metaConfig->getStructureByName($this->typeName)) {
            $this->typeId = self::TYPE_STRUCTURE;
            return;
        }

        throw new \UnexpectedValueException('can not resolve type ' . $this->typeName);
    }

}