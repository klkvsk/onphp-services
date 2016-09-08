<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-12-03
 */
namespace OnPhp\Services\Meta;

use OnPhp\Services\Codegen\SourceCodeClassReference;

class MetaService
{
    /**    @var MetaServiceContainer */
    protected $container;
    /** @var string */
    protected $name;
    /** @var string */
    protected $location;
    /** @var string */
    protected $httpPath;
    /** @var MetaServiceAction[] */
    protected $actions = [];
    /** @var MetaServiceAccess */
    protected $access;

    /**
     * @param $name
     * @param $httpPath
     * @return self
     */
    public static function create($name, $httpPath)
    {
        $self = new self;
        $self->name = $name;
        $self->httpPath = trim($httpPath, '/');
        return $self;
    }

    /** @return string */
    public function getHttpPath()
    {
        return $this->httpPath;
    }

    public function getName()
    {
        return $this->name;
    }

    /** @return string */
    public function getNameForClass()
    {
        $serviceName = ucfirst($this->name);
        $serviceName .= 'Service';
        return $serviceName;
    }

    /**
     * @return SourceCodeClassReference
     */
    public function getClassRef()
    {
        return SourceCodeClassReference::create(
            $this->getContainer()->getName(),
            $this->getNameForClass()
        );
    }

    /**
     * @return SourceCodeClassReference
     */
    public function getImplClassRef()
    {
        return SourceCodeClassReference::create(
            $this->getContainer()->getName(),
            'Implementations',
            $this->getNameForClass() . 'Impl'
        );
    }

    /**
     * @return SourceCodeClassReference
     */
    public function getDependencyClassRef()
    {
        return SourceCodeClassReference::create(
            $this->getContainer()->getName(),
            'Dependencies',
            $this->getNameForClass() . 'Dependency'
        );
    }

    /**
     * @return SourceCodeClassReference
     */
    public function getProxyClassRef()
    {
        return SourceCodeClassReference::create(
            $this->getContainer()->getName(),
            'Proxies',
            $this->getNameForClass() . 'Proxy'
        );
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

    public function setLocation($location)
    {
        $this->location = $location;
        return $this;
    }

    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param MetaServiceAction $action
     * @return $this
     */
    public function addAction(MetaServiceAction $action)
    {
        $action->setService($this);
        $this->actions[] = $action;
        return $this;
    }

    /**
     * @return MetaServiceAction[]
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @param MetaServiceContainer $container
     * @return $this
     */
    public function setContainer(MetaServiceContainer $container)
    {
        $this->container = $container;
        return $this;
    }

    /**
     * @return MetaServiceContainer
     */
    public function getContainer()
    {
        return $this->container;
    }

}