<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-12-03
 */
namespace OnPhp\Services\Codegen;

interface ISourceCodeGenerator
{
    /**
     * @param \OnPhp\Services\Meta\MetaServiceContainer $container
     * @return array (string)filepath => (bool)isUpdated
     */
    public function buildContainer(\OnPhp\Services\Meta\MetaServiceContainer $container);

    /**
     * @param \OnPhp\Services\Meta\MetaService $service
     * @return array (string)filepath => (bool)isUpdated
     */
    public function buildService(\OnPhp\Services\Meta\MetaService $service);

    /**
     * @param \OnPhp\Services\Meta\MetaStructure $structure
     * @return array (string)filepath => (bool)isUpdated
     */
    public function buildStructure(\OnPhp\Services\Meta\MetaStructure $structure);

    /**
     * @param \OnPhp\Services\Meta\MetaEnum $enum
     * @return array (string)filepath => (bool)isUpdated
     */
    public function buildEnum(\OnPhp\Services\Meta\MetaEnum $enum);

}