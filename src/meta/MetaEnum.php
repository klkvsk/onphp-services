<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-11-20
 */
namespace OnPhp\Services\Meta;

use \OnPhp\Services\Codegen\SourceCodeClassReference;

class MetaEnum
{
    /** @var string */
    protected $name;
    /** @var MetaEnumValue[] */
    protected $values = [];
    /** @var MetaProperty[] */
    protected $properties = [];


    protected function __construct($name)
    {
        $this->name = $name;
    }

    public static function create($name)
    {
        return new self($name);
    }

    public function getName()
    {
        return $this->name;
    }

    public function addValue(MetaEnumValue $value)
    {
        $this->values [] = $value;
        return $this;
    }

    public function getValues()
    {
        return $this->values;
    }

    public function addProperty(MetaProperty $property)
    {
        $this->properties [] = $property;
        return $this;
    }

    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @return SourceCodeClassReference
     */
    public function getClassRef()
    {
        return SourceCodeClassReference::create($this->name);
    }

    /**
     * @return SourceCodeClassReference
     */
    public function getValuesClassRef()
    {
        return SourceCodeClassReference::create($this->name . 'Values');
    }

    /**
     * @return SourceCodeClassReference
     */
    public function getTraitClassRef()
    {
        return SourceCodeClassReference::create($this->name . 'Trait');
    }

}