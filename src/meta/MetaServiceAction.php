<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-12-03
 */
namespace OnPhp\Services\Meta;

use \HttpMethod;
use \Assert;

class MetaServiceAction
{

    /** @var MetaService */
    protected $service;
    /** @var HttpMethod */
    protected $httpMethod;
    /** @var string */
    protected $httpPath;
    /** @var string */
    protected $name;
    /** @var MetaServiceAccess */
    protected $access;
    /** @var MetaServiceActionParam[] */
    protected $params = [];
    /** @var MetaServiceActionReturn */
    protected $return;

    /**
     * @param string $httpMethod
     * @param        $httpPath
     * @param        $name
     * @return MetaServiceAction
     * @throws \WrongArgumentException
     */
    public static function create($name, $httpMethod = 'GET', $httpPath)
    {
        $self = new self;
        foreach (HttpMethod::makeObjectList() as $method) {
            if ($httpMethod == $method->getName()) {
                $self->httpMethod = $method;
                break;
            }
        }
        Assert::isInstance($self->httpMethod, HttpMethod::class, 'defined method is not valid: ' . $httpMethod);
        $self->httpPath = trim($httpPath, '/');
        $self->name = $name;
        return $self;
    }

    /**
     * @return HttpMethod
     */
    public function getHttpMethod()
    {
        return $this->httpMethod;
    }

    /**
     * @return string
     */
    public function getHttpPath()
    {
        return $this->httpPath;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param MetaService $service
     * @return $this
     */
    public function setService(MetaService $service)
    {
        $this->service = $service;
        return $this;
    }

    /**
     * @return MetaService
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param MetaServiceAccess $access
     * @return $this
     */
    public function setAccess(MetaServiceAccess $access)
    {
        $this->access = $access;
        return $this;
    }

    /**
     * @return MetaServiceAccess
     */
    public function getAccess()
    {
        return $this->access;
    }

    public function getAccessTypesFull()
    {
        $types = [];
        if ($this->getAccess()) {
            $types = $this->getAccess()->getTypes();
        }
        if ((!$this->getAccess() || !$this->getAccess()->isOverride()) && $this->getService()->getAccess()) {
            $types = array_merge($types, $this->getService()->getAccess()->getTypes());
        }
        return $types;
    }

    /**
     * @param MetaServiceActionParam $param
     * @return $this
     */
    public function addParam(MetaServiceActionParam $param)
    {
        $this->params[] = $param;
        return $this;
    }

    /**
     * @return MetaServiceActionParam[]
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param MetaServiceActionReturn $return
     * @return $this
     */
    public function setReturn(MetaServiceActionReturn $return)
    {
        $this->return = $return;
        return $this;
    }

    /**
     * @return MetaServiceActionReturn
     */
    public function getReturn()
    {
        return $this->return;
    }
}