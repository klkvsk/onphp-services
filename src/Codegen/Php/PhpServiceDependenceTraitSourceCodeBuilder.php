<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-12-03
 */
namespace OnPhp\Services\Codegen\Php;

use \OnPhp\Services\Meta\MetaService;

class PhpServiceDependenceTraitSourceCodeBuilder extends AbstractPhpSourceCodeBuilder
{
    /** @var MetaService */
    protected $metaService;

    public static function create(MetaService $metaService)
    {
        $self = new static;
        $self->metaService = $metaService;
        return $self;
    }

    public function build()
    {
        $s = $this->metaService;
        parent::build();
        $cref = $this->classRef($s->getDependencyClassRef());
        if ($cref->getNamespace()) {
            $this->addLine('namespace ' . $cref->getNamespace() . ';');
        }
        $iServiceContainerCref = $this->classRef(\OnPhp\Services\Base\IServiceContainer::class);
        $this
            ->newLine()
            ->add('trait ' . $cref->getName())
            ->begin()
            ->newLine()
            ->doc([
                ['@param', $iServiceContainerCref->getFullName(), '$container', 'requested service container'],
                ['@param', 'string', '$serviceName', 'requested service name']
            ])
            ->add('abstract public function resolveService(' . $iServiceContainerCref->getFullName() . ' $container, $serviceName);')
            ->newLine()
            ->newLine()
            ->doc([
                ['@return', $this->classRef($s->getClassRef())->getFullName()],
                ['@throws', $this->classRef(\WrongStateException::class)->getFullName()]
            ])
            ->add('public function get' . $s->getNameForClass() . '()')
            ->begin();

        // method code
        $this
            ->add('$this->resolveService(')
            ->add(
                $this->classRef($s->getContainer()->getClassRef())
                    ->getStatic('me()')
            )
            ->add(', ')
            ->addString($s->getName())
            ->add(');');

        $this->end();
        $this->end();
    }

}