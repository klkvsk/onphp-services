<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-12-01
 */

namespace OnPhp\Services\Base;


class ProtoServiceActionReturn
{
    /** @var \BasePrimitive */
    protected $primitive;

    public static function create(\BasePrimitive $primitive)
    {
        $self = new self;
        $self->primitive = $primitive;
        $self->primitive->setName('return');
        return $self;
    }

    public function getPrimitive()
    {
        return $this->primitive;
    }

    public function verify($return)
    {
        // TODO: check that returned data matches defined return type
        if (false) {
            throw new \Exception;
        }
    }

}