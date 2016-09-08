<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-12-03
 */
namespace OnPhp\Services\Meta;

use \OnPhp\Services\Codegen\SourceCodeClassReference;

class MetaStructure
{
    /** @var string */
    protected $name;
    /** @var MetaStructure */
    protected $parent;
    /** @var MetaProperty[] */
    protected $properties = [];

    protected function __construct($name, self $parent = null)
    {
        $this->name = $name;
        $this->parent = $parent;
    }

    /**
     * @param $name
     * @param MetaStructure|null $parent
     * @return static
     */
    public static function create($name, self $parent = null)
    {
        return new static($name, $parent);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return MetaStructure|null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param MetaProperty $metaProperty
     * @return $this
     */
    public function addProperty(MetaProperty $metaProperty)
    {
        $this->properties [] = $metaProperty;
        return $this;
    }

    /**
     * @return MetaProperty[]
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @return SourceCodeClassReference
     */
    public function getClassRef()
    {
        return SourceCodeClassReference::create($this->getName());
    }
}