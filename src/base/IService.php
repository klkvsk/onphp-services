<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-12-03
 */

namespace OnPhp\Services\Base;

interface IService
{
    public function __construct(IServiceEnvironment $environment = null);
}