<?php
namespace OnPhp\Services\Codegen\Php;

use \OnPhp\Services\Base\AbstractServiceContainer;
use \OnPhp\Services\Base\ProtoService;
use \OnPhp\Services\Base\ProtoServiceAction;
use \OnPhp\Services\Base\ProtoServiceActionParam;
use \OnPhp\Services\Base\ProtoServiceActionReturn;
use \OnPhp\Services\Base\ServiceActionParamSource;
use \OnPhp\Services\Meta\MetaServiceContainer;

class PhpServiceContainerSourceCodeBuilder extends AbstractPhpSourceCodeBuilder
{
    /** @var MetaServiceContainer */
    protected $metaServiceContainer;

    public static function create(MetaServiceContainer $metaServiceContainer)
    {
        $self = new static;
        $self->metaServiceContainer = $metaServiceContainer;
        return $self;
    }

    public function build()
    {
        $c = $this->metaServiceContainer;
        $cref = $this->classRef($c->getClassRef());
        parent::build();
        if ($cref->getNamespace()) {
            $this->addLine('namespace ' . $cref->getNamespace() . ';');
        }
        $this
            ->newLine()
            ->add('class ' . $cref->getName() . ' extends ' . $this->classRef(AbstractServiceContainer::class)->getFullName())
            ->begin()
            ->newLine()
            ->add('public function getName()')
            ->begin()
            ->addLine('return \'' . $c->getName() . '\';')
            ->end()
            ->newLine();

        // makeMetaContainer()
        $this
            ->add('protected function makeHttpMap()')
            ->begin()
            ->begin('return [');
        foreach ($c->getServices() as $s) {
            $this
                ->addString($s->getHttpPath())
                ->add(' => ')
                ->addString($s->getName())
                ->add(',')
                ->newLine();
        }
        $this
            ->end('];')
            ->end()
            ->newLine();

        // makeInitializers()
        $this
            ->add('protected function makeInitializers()')
            ->begin()
            ->begin('return [');
        foreach ($c->getServices() as $s) {
            $this
                ->addString($s->getName())
                ->begin(' => function () {')
                ->add('return ')
                ->add($this->classRef(ProtoService::class)->getStatic('create'))
                ->addArguments([$s->getName()])
                ->newLine()
                ->indent()
                ->add('->setLocation')->addArguments([$s->getLocation()])->newLine()
                ->add('->setHttpPath')->addArguments([$s->getHttpPath()])->newLine()
                ->add('->setImplClassName')->addArguments([$this->classRef($s->getImplClassRef())])->newLine()
                ->add('->setProxyClassName')->addArguments([$this->classRef($s->getProxyClassRef())])->newLine();

            foreach ($s->getActions() as $a) {
                $this
                    ->add('->addAction')->begin('(')
                    ->add($this->classRef(ProtoServiceAction::class)->getStatic('create'))
                    ->addArguments([$a->getName()])
                    ->newLine()
                    ->indent();

                $this
                    ->add('->setAccessList')->addArguments([$a->getAccessTypesFull()])->newLine()
                    ->add('->setHttpMethod(')
                    ->add($this->classRef(\HttpMethod::class)->getStatic($a->getHttpMethod()->getName()))
                    ->add(')')->newLine()
                    ->add('->setHttpPath')->addArguments([$a->getHttpPath()])->newLine();

                foreach ($a->getParams() as $p) {
                    $this
                        ->add('->addParam')
                        ->begin('(')
                        ->add($this->classRef(ProtoServiceActionParam::class)->getStatic('create'))
                        ->begin('(')
                        ->add($this->classRef(ServiceActionParamSource::class)->getStatic($p->getFrom()->getFactoryName()))
                        ->add('(),')->newLine()
                        ->addPrimitiveDefinition($p->getProperty())
                        ->end(')')
                        ->end(')');
                }

                if ($a->getReturn()) {
                    $this
                        ->add('->setReturn')
                        ->begin('(')
                        ->add($this->classRef(ProtoServiceActionReturn::class)->getStatic('create'))
                        ->begin('(')
                        ->addPrimitiveDefinition($a->getReturn()->getProperty())
                        ->end(')')
                        ->end(')');
                }

                $this->unindent();
                $this->end(')');
            }
            $this->unindent();
            $this->addLine(';');
            $this->end('},');
        }

        $this->end('];');
        $this->end('}');
        $this->end('}');
    }

}