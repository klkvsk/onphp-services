<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-12-03
 */

namespace OnPhp\Services\Base;

class ServiceProxy implements IService
{
    protected $environment;

    public function __construct(IServiceEnvironment $environment = null)
    {
        $this->environment = $environment;
    }

    public function proxy($methodName, $args)
    {
        throw new \UnimplementedFeatureException();
    }

}
