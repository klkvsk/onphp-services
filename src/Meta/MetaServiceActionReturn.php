<?php
namespace OnPhp\Services\Meta;

class MetaServiceActionReturn
{
    /** @var MetaProperty */
    protected $property;

    /**
     * @param MetaProperty $property
     * @return MetaServiceActionReturn
     */
    public static function create(MetaProperty $property)
    {
        $self = new self;
        $self->property = $property;
        return $self;
    }

    /**
     * @return MetaProperty
     */
    public function getProperty()
    {
        return $this->property;
    }

}