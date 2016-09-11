<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-12-03
 */

namespace OnPhp\Services\Base;

interface IServiceAccessChecker
{
    /**
     * @param ProtoServiceAction $action
     * @param IServiceEnvironment $environment
     * @return bool
     */
    public function isActionAllowed(ProtoServiceAction $action, IServiceEnvironment $environment = null);
}