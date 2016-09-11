<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-12-03
 */
namespace OnPhp\Services\Codegen\Php;

use \OnPhp\Services\Base\ServiceProxy;

class PhpServiceProxySourceCodeBuilder extends PhpServiceInterfaceSourceCodeBuilder
{

    public function build()
    {
        $s = $this->metaService;
        AbstractPhpSourceCodeBuilder::build();
        $cref = $this->classRef($s->getProxyClassRef());
        if ($cref->getNamespace()) {
            $this->addLine('namespace ' . $cref->getNamespace() . ';');
        }
        $this
            ->newLine()
            ->add('class ' . $cref->getName()
                . ' extends ' . $this->classRef(ServiceProxy::class)->getFullName()
                . ' implements ' . $this->classRef($s->getClassRef())->getFullName()
            )
            ->begin();

        foreach ($s->getActions() as $a) {
            $this
                ->newLine()
                ->addActionSignature($a)
                ->begin()
                ->add('$this->proxy(__FUNCTION__, func_get_args());')
                ->end();
        }

        $this->end();
    }


}