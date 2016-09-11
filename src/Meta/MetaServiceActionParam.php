<?php
namespace OnPhp\Services\Meta;

use \OnPhp\Services\Base\ServiceActionParamSource;

class MetaServiceActionParam
{
    /** @var ServiceActionParamSource */
    protected $from;
    /** @var MetaProperty */
    protected $property;

    /**
     * @param MetaProperty $property
     * @param ServiceActionParamSource $from
     * @return MetaServiceActionParam
     */
    public static function create(MetaProperty $property, ServiceActionParamSource $from)
    {
        $self = new self;
        $self->property = $property;
        $self->from = $from;
        return $self;
    }

    /**
     * @return ServiceActionParamSource
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->property->getName();
    }

    /**
     * @return MetaProperty
     */
    public function getProperty()
    {
        return $this->property;
    }
}