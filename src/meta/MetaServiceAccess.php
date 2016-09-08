<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-12-03
 */
namespace OnPhp\Services\Meta;

class MetaServiceAccess
{
    /** @var string[] */
    protected $types;
    /** @var bool */
    protected $override;

    /**
     * @param array $types
     * @param bool $override
     * @return MetaServiceAccess
     */
    public static function create(array $types, $override = false)
    {
        $self = new self;
        $self->types = $types;
        $self->override = $override;
        return $self;
    }

    public function getTypes()
    {
        return $this->types;
    }

    public function isOverride()
    {
        return $this->override;
    }
}