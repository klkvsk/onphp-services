<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-12-01
 */

namespace OnPhp\Services\Base;


class ProtoServiceActionParam
{
    /** @var \BasePrimitive */
    protected $primitive;
    /** @var ServiceActionParamSource */
    protected $from;

    /**
     * @param ServiceActionParamSource $from
     * @param \BasePrimitive $primitive
     * @return ProtoServiceActionParam
     */
    public static function create(ServiceActionParamSource $from, \BasePrimitive $primitive)
    {
        $self = new self;
        $self->from = $from;
        $self->primitive = $primitive;
        return $self;
    }

    /**
     * @return \BasePrimitive
     */
    public function getPrimitive()
    {
        return $this->primitive;
    }

    /**
     * @return ServiceActionParamSource
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return null
     */
    public function getName()
    {
        return $this->primitive->getName();
    }

}