<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-12-03
 */
namespace OnPhp\Services\Base;

interface IServiceProvider
{

    /**
     * @param ProtoService $service
     * @param IServiceEnvironment $environment
     * @return IService
     */
    public function getInstance(ProtoService $service, IServiceEnvironment $environment);

}