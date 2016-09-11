<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-12-03
 */
namespace OnPhp\Services\Meta;

use \OnPhp\Services\Codegen\SourceCodeClassReference;
use OnPhp\Services\Codegen\SourceCodeTypeReference;

class MetaStructure
{
    /** @var string */
    protected $name;
    /** @var MetaStructure */
    protected $parent;
    /** @var MetaProperty[] */
    protected $properties = [];
    /** @var string[] */
    protected $templateVars = [];
    /** @var self[] */
    protected $templateCases = [];

    protected function __construct($name, self $parent = null)
    {
        $this->parent = $parent;
        list ($this->name, $this->templateVars) = SourceCodeTypeReference::parseTypename($name);
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
     * @return bool
     */
    public function isTemplated()
    {
        return !empty($this->templateVars);
    }

    /**
     * @return bool
     */
    public function isTemplatedVar($name)
    {
        return in_array($name, $this->templateVars);
    }

    /**
     * @param SourceCodeTypeReference[] $types
     */
    public function addTemplateCase(array $types)
    {
        $typeNames = [];
        foreach ($types as $type) {
            $typeNames []= $type->getTypeName();
        }
        $signature = implode('_', $typeNames);
        if (!isset($this->templateCases[$signature])) {
            $case = self::create($this->name . '_' . $signature, $this->parent);
            foreach ($this->properties as $metaProperty) {
                $index = array_search($metaProperty->getType()->getTypeName(), $this->templateVars);
                if ($index !== false) {
                    $type = SourceCodeTypeReference::create(
                        $types[$index]->getTypeName() . ($metaProperty->getType()->isArray() ? '[]' : '')
                    );
                    $clone = (clone $metaProperty)->setType($type);
                    $case->addProperty($clone);
                } else {
                    $case->addProperty($metaProperty);
                }
            }
            $this->templateCases[$signature] = $case;
        }
        return $this->templateCases[$signature];
    }

    /**
     * @return MetaStructure[]
     */
    public function getTemplateCases()
    {
        return $this->templateCases;
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