<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-11-12
 */

namespace OnPhp\Services\Codegen\Typescript;

class TypescriptSourceCodeGenerator implements \OnPhp\Services\Codegen\ISourceCodeGenerator
{

    public function buildContainer(\OnPhp\Services\Meta\MetaServiceContainer $container)
    {
        return [];
    }

    public function buildService(\OnPhp\Services\Meta\MetaService $service)
    {
        return [];
    }

    public function buildStructure(\OnPhp\Services\Meta\MetaStructure $structure)
    {
        return [];
    }

    public function buildEnum(\OnPhp\Services\Meta\MetaEnum $enum)
    {
        return [];
    }

}