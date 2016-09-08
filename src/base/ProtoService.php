<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-11-30
 */

namespace OnPhp\Services\Base;

class ProtoService
{
    /** @var AbstractServiceContainer */
    protected $container;
    /** @var string */
    protected $name;
    /** @var string */
    protected $location;
    /** @var string */
    protected $httpPath;
    /** @var ProtoServiceAction[] */
    protected $actions = [];
    /** @var string */
    protected $implClassName;
    /** @var string */
    protected $proxyClassName;

    /**
     * @param $serviceName
     * @return ProtoService
     */
    public static function create($serviceName)
    {
        $self = new self;
        $self->name = $serviceName;
        return $self;
    }

    /**
     * @param AbstractServiceContainer $container
     * @return $this
     */
    public function setContainer(AbstractServiceContainer $container)
    {
        $this->container = $container;
        return $this;
    }

    /**
     * @return AbstractServiceContainer
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $location
     * @return $this
     */
    public function setLocation($location)
    {
        $this->location = $location;
        return $this;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
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

    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @param ProtoServiceAction[] $actions
     * @return $this
     */
    public function setActions(array $actions)
    {
        $this->actions = [];
        foreach ($actions as $action) {
            $this->addAction($action);
        }
        return $this;
    }

    /**
     * @param ProtoServiceAction $action
     * @return $this
     */
    public function addAction(ProtoServiceAction $action)
    {
        $action->setService($this);
        $this->actions [] = $action;
        return $this;
    }

    /**
     * @return string
     */
    public function getImplClassName()
    {
        return $this->implClassName;
    }

    /**
     * @param string $implClassName
     * @return ProtoService
     */
    public function setImplClassName($implClassName)
    {
        $this->implClassName = $implClassName;
        return $this;
    }

    /**
     * @return string
     */
    public function getProxyClassName()
    {
        return $this->proxyClassName;
    }

    /**
     * @param string $proxyClassName
     * @return ProtoService
     */
    public function setProxyClassName($proxyClassName)
    {
        $this->proxyClassName = $proxyClassName;
        return $this;
    }


}