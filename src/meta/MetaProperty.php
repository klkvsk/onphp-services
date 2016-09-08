<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-11-20
 */

namespace OnPhp\Services\Meta;

use \OnPhp\Services\Codegen\SourceCodeTypeReference;

class MetaProperty
{
    /** @var string */
    protected $name;
    /** @var SourceCodeTypeReference */
    protected $type;
    /** @var bool */
    protected $isRequired = false;
    /** @var string */
    protected $defaultValue = null;

    protected function __construct($name, $type, $isRequired, $defaultValue = null)
    {
        $this->name = $name;
        $this->isRequired = $isRequired;
        $this->type = SourceCodeTypeReference::create($type);
        $this->defaultValue = $defaultValue;
    }

    public static function create($name, $type, $isRequired, $defaultValue = null)
    {
        return new static($name, $type, $isRequired, $defaultValue);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return SourceCodeTypeReference
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isRequired()
    {
        return $this->isRequired;
    }

}