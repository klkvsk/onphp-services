<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-12-01
 */

namespace OnPhp\Services\Base;


class ProtoServiceAction
{
    /** @var ProtoService */
    protected $service;
    /** @var string */
    protected $name;
    /** @var \HttpMethod */
    protected $httpMethod;
    /** @var string */
    protected $httpPath;
    /** @var string[] */
    protected $accessList = [];
    /** @var ProtoServiceActionParam[] */
    protected $params = [];
    /** @var ProtoServiceActionReturn */
    protected $return = null;

    /**
     * @param $actionName
     * @return ProtoServiceAction
     */
    public static function create($actionName)
    {
        $self = new self;
        $self->name = $actionName;
        return $self;
    }

    /**
     * @param ProtoService $service
     * @return $this
     */
    public function setService(ProtoService $service)
    {
        $this->service = $service;
        return $this;
    }

    /**
     * @return ProtoService
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param int $httpMethodId
     * @return $this
     */
    public function setHttpMethod($httpMethodId)
    {
        $this->httpMethod = new \HttpMethod($httpMethodId);
        return $this;
    }

    /**
     * @return \HttpMethod
     */
    public function getHttpMethod()
    {
        return $this->httpMethod;
    }

    /**
     * @param string $httpPath
     * @return $this
     */
    public function setHttpPath($httpPath)
    {
        $this->httpPath = $httpPath;
        return $this;
    }

    /**
     * @return string
     */
    public function getHttpPath()
    {
        return $this->httpPath;
    }

    /**
     * @param string[] $accessList
     * @return $this
     */
    public function setAccessList(array $accessList)
    {
        $this->accessList = $accessList;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getAccessList()
    {
        return $this->accessList;
    }

    /**
     * @param ProtoServiceActionParam[] $params
     * @return $this
     */
    public function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @param ProtoServiceActionParam $param
     * @return $this
     */
    public function addParam(ProtoServiceActionParam $param)
    {
        $this->params []= $param;
        return $this;
    }

    /**
     * @return ProtoServiceActionParam[]
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param ServiceActionParamSource $from filter by "from" attribute
     * @return ProtoServiceActionParam[]
     */
    public function getParamsFrom(ServiceActionParamSource $from) {
        return array_filter(
            $this->getParams(),
            function (ProtoServiceActionParam $param) use ($from) {
                return $param->getFrom()->is($from);
            }
        );
    }

    /**
     * @param ProtoServiceActionReturn $return
     * @return $this
     */
    public function setReturn(ProtoServiceActionReturn $return)
    {
        $this->return = $return;
        return $this;
    }

    /**
     * @return ProtoServiceActionReturn
     */
    public function getReturn()
    {
        return $this->return;
    }

}