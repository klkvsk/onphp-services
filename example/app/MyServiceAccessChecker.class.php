<?php

/**
 * Реализуем проверку прав доступа к сервису
 * Этот класс создается вручную.
 */
class MyServiceAccessChecker implements \OnPhp\Services\Base\IServiceAccessChecker
{

    /** @return static */
    public static function create()
    {
        return new static;
    }

    /**
     * @param \OnPhp\Services\Base\ProtoServiceAction $action
     * @param \OnPhp\Services\Base\IServiceEnvironment|MyServiceEnvironment $environment
     * @return bool
     */
    public function isActionAllowed(
        \OnPhp\Services\Base\ProtoServiceAction $action,
        \OnPhp\Services\Base\IServiceEnvironment $environment = null
    )
    {
        MyServiceEnvironment::assertIsInstance($environment);

        $types = $action->getAccessList();
        $user = $environment->getLoggedUser();

        return empty($types)
            || (!$user && in_array('Visitor', $types))
            || ( $user && in_array('User', $types))
            || ( $user && $user->isAdmin() && in_array('Admin', $types));
    }

}