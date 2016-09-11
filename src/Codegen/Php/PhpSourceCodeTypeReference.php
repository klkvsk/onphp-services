<?php
namespace OnPhp\Services\Codegen\Php;

use \OnPhp\Services\Codegen\SourceCodeTypeReference;

class PhpSourceCodeTypeReference extends SourceCodeTypeReference
{
    protected static $primitivesMapping = [
        self::TYPE_BOOLEAN         => 'ternary',
        self::TYPE_STRING          => 'string',
        self::TYPE_FLOAT           => 'float',
        self::TYPE_INTEGER         => 'integer',
        self::TYPE_DATE            => 'date',
        self::TYPE_DATETIME        => 'timestamp',
        self::TYPE_ENUM            => 'enum',
        self::TYPE_ENUMERATION     => 'enumeration',
        self::TYPE_STRUCTURE       => 'form',
        self::TYPE_IDENTIFIER      => 'identifier',
    ];

    protected static $scalarTypes = [
        self::TYPE_INTEGER  => 'int',
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
        $typeDoc = $this->getTypeName();
        if (!$this->isScalar()) {
            $typeDoc = PhpSourceCodeClassReference::create($typeDoc)->getFullName();
        } else {
            $typeDoc = self::$scalarTypes[$this->typeId];
        }
        if ($this->isArray()) {
            $typeDoc .= '[]';
        }
        return $typeDoc;
    }

    public function getTypeHint()
    {
        if ($this->isArray()) {
            return 'array';
        } else if ($this->isScalar()) {
            $typeHint = self::$scalarTypes[$this->typeId];
            if (PHP_MAJOR_VERSION < 7) {
                $typeHint = '/*' . $typeHint . '*/';
            }
            return $typeHint;
        } else {
            return PhpSourceCodeClassReference::create($this->getTypeName())->getFullName();
        }
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
            return PhpSourceCodeClassReference::create($this->getTypeName())->getSelfName();
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