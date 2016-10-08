<?php
namespace OnPhp\Services\Codegen\Typescript;

use \OnPhp\Services\Codegen\SourceCodeTypeReference;

class TypescriptSourceCodeTypeReference extends SourceCodeTypeReference
{
    protected static $primitivesMapping = [
        self::TYPE_BOOLEAN         => 'PrimitiveBoolean',
        self::TYPE_STRING          => 'PrimitiveString',
        self::TYPE_FLOAT           => 'PrimitiveFloat',
        self::TYPE_INTEGER         => 'PrimitiveInteger',
        self::TYPE_DATE            => 'date',
        self::TYPE_DATETIME        => 'timestamp',
        self::TYPE_ENUM            => 'enum',
        self::TYPE_ENUMERATION     => 'enumeration',
        self::TYPE_STRUCTURE       => 'PrimitiveStructure',
        self::TYPE_IDENTIFIER      => 'PrimitiveIdentifier',
    ];

    protected static $scalarTypes = [
        self::TYPE_INTEGER  => 'number',
        self::TYPE_FLOAT    => 'float',
        self::TYPE_BOOLEAN  => 'bool',
        self::TYPE_STRING   => 'string',
    ];

    public function isScalar()
    {
        return array_key_exists($this->typeId, self::$scalarTypes);
    }

    public function getTypeDoc()
    {
        return $this->getTypeHint();
    }

    public function getTypeHint()
    {
        $typeHint = $this->getTypeName();
        if ($this->isScalar()) {
            $typeHint = self::$scalarTypes[$this->typeId];
        } else {
            $typeHint = TypescriptSourceCodeClassReference::create($typeHint)->getFullName();
        }
        if ($this->isArray()) {
            $typeHint .= '[]';
        }
        return $typeHint;
    }

    public function getPrimitiveName()
    {
        \Assert::isNotNull($this->typeId, 'can not get primitiveName for unresolved type ' . $this->getTypeName());
        \Assert::isIndexExists(self::$primitivesMapping, $this->typeId, 'dont have a primitive for type ' . $this->typeId);
        return self::$primitivesMapping[$this->typeId];
    }

    public function toString()
    {
        if ($this->isScalar()) {
            return '\'' . strtolower($this->getTypeName()) . '\'';
        } else {
            return TypescriptSourceCodeClassReference::create($this->getTypeName())->getSelfName();
        }
    }

    public function __toString()
    {
        return $this->toString();
    }

    public function getTypeName()
    {
        $typeName = parent::getTypeName();
        foreach ($this->getTemplateTypes() as $templateType) {
            $typeName .= '_' . $templateType->getTypeName();
        }
        return $typeName;
    }


}