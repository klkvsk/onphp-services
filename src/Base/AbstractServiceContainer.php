<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-12-02
 */

namespace OnPhp\Services\Base;

abstract class AbstractServiceContainer extends \Singleton implements IServiceContainer
{
    /**
     * @key string service name
     * @var \Closure[]
     */
    protected $initializers = [];
    /**
     * @key string service name
     * @var ProtoService[]
     */
    protected $protoServiceIdentityMap = [];
    /**
     * @key string http path
     * @var string[] service names
     */
    protected $httpMap = [];

    abstract protected function makeInitializers();
    abstract protected function makeHttpMap();

    protected function __construct()
    {
        $this->initializers = $this->makeInitializers();
        $this->httpMap = $this->makeHttpMap();
    }

    /**
     * @return static
     */
    public static function me()
    {
        return self::getInstance(static::class);
    }

    /**
     * @param string $name
     * @return null|ProtoService
     */
    public function getProtoServiceByName($name)
    {
        if (!isset($this->protoServiceIdentityMap[$name])) {
            if (!isset($this->initializers[$name])) {
                return null;
            }
            $initializer = $this->initializers[$name];
            /** @var $protoService ProtoService */
            $protoService = $initializer();
            $protoService->setContainer($this);
            $this->protoServiceIdentityMap[$name] = $protoService;
        }
        return $this->protoServiceIdentityMap[$name];
    }

    /**
     * @param string $httpPath
     * @return null|ProtoService
     */
    public function getProtoServiceByHttpPath($httpPath)
    {
        if (!isset($this->httpMap[$httpPath])) {
            return null;
        }
        return $this->getProtoServiceByName($this->httpMap[$httpPath]);
    }

    /**
     * @return string[]
     */
    public function getServiceNames()
    {
        return array_keys($this->initializers);
    }

}