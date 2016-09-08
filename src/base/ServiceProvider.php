<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-12-03
 */
namespace OnPhp\Services\Base;

class ServiceProvider implements IServiceProvider
{
    /** @var string */
    protected $ownLocation;

    /**
     * @param string $location
     * @return $this
     */
    public function setOwnLocation($location)
    {
        $this->ownLocation = $location;
        return $this;
    }

    /**
     * @return string
     */
    public function getOwnLocation()
    {
        return $this->ownLocation;
    }

    /**
     * @param ProtoService $service
     * @param IServiceEnvironment $environment
     * @return IService
     * @throws \WrongArgumentException
     */
    public function getInstance(ProtoService $service, IServiceEnvironment $environment)
    {
        $isLocal = $this->ownLocation === null
            || $service->getLocation() === null
            || $this->ownLocation === $service->getLocation();

        if ($isLocal) {
            $serviceClassName = $service->getImplClassName();
        } else {
            $serviceClassName = $service->getProxyClassName();
        }

        \Assert::isInstance($serviceClassName, IService::class);
        $service = new $serviceClassName($environment);
        return $service;
    }
}