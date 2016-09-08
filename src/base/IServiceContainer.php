<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-12-03
 */

namespace OnPhp\Services\Base;

interface IServiceContainer
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     * @return ProtoService
     */
    public function getProtoServiceByName($name);

    /**
     * @param string $httpPath
     * @return ProtoService
     */
    public function getProtoServiceByHttpPath($httpPath);
}