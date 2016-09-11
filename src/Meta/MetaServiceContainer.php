<?php
namespace OnPhp\Services\Meta;

use \OnPhp\Services\Codegen\SourceCodeClassReference;

class MetaServiceContainer
{
    /** @var string */
    protected $name;
    /** @var MetaService[] */
    protected $services = [];

    /**
     * @param $name
     */
    protected function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @param $name
     * @return static
     */
    public static function create($name)
    {
        return new static($name);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getNameForClass()
    {
        return ucfirst($this->getName()) . 'Container';
    }

    /**
     * @return SourceCodeClassReference
     */
    public function getClassRef()
    {
        return SourceCodeClassReference::create($this->getNameForClass());
    }

    /**
     * @param MetaService $service
     * @return $this
     */
    public function addService(MetaService $service)
    {
        $service->setContainer($this);
        $this->services[] = $service;
        return $this;
    }

    /**
     * @return MetaService[]
     */
    public function getServices()
    {
        return $this->services;
    }

}