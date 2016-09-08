<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-12-03
 */
namespace OnPhp\Services\Codegen\Php;

class PhpServiceImplSourceCodeBuilder extends PhpServiceInterfaceSourceCodeBuilder
{

    public function build()
    {
        $s = $this->metaService;

        $this
            ->clean()
            ->addLine('<?php')
            ->addHeaderDocForGeneratedOnce()
            ->newLine();

        $cref = $this->classRef($s->getImplClassRef());
        if ($cref->getNamespace()) {
            $this->addLine('namespace ' . $cref->getNamespace() . ';');
        }
        $this
            ->newLine()
            ->add('class ' . $cref->getName() . ' implements ' . $this->classRef($s->getClassRef())->getFullName())
            ->begin()
            ->newLine()
            ->add('public function __construct(')
            ->add($this->typeRef(\OnPhp\Services\Base\IServiceEnvironment::class)->getTypeHint() . ' $environment = null')
            ->add(')')
            ->begin()
            ->add('/* your code goes here */')
            ->end();

        foreach ($s->getActions() as $a) {
            $this
                ->newLine()
                ->addActionSignature($a)
                ->begin()
                ->add('/* your code goes here */')
                ->end();
        }

        $this->end();
    }


}