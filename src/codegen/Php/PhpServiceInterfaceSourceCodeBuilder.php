<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-12-03
 */
namespace OnPhp\Services\Codegen\Php;

use \Onphp\Services\Meta\MetaService;
use \Onphp\Services\Meta\MetaServiceAction;


class PhpServiceInterfaceSourceCodeBuilder extends AbstractPhpSourceCodeBuilder
{
    /** @var MetaService */
    protected $metaService;

    public static function create(MetaService $metaService)
    {
        $self = new static;
        $self->metaService = $metaService;
        return $self;
    }

    public function addActionSignature(MetaServiceAction $a)
    {
        $doc = [
            ['@method', $a->getHttpMethod()->getName()],
            ['@path', '/' . $a->getService()->getHttpPath() . '/' . $a->getHttpPath()],
        ];

        $args = [];
        foreach ($a->getParams() as $p) {
            $typeRef = $this->typeRef($p->getProperty()->getType());
            $doc [] = ['@param', $typeRef->getTypeDoc(), '$' . $p->getName(), 'from ' . $p->getFrom()->getId()];

            $arg = $typeRef->getTypeHint() . ' $' . $p->getName();
            if (!$p->getProperty()->isRequired()) {
                $arg .= ' = null';
            }
            $args [] = $arg;
        }
        $doc [] = ['@return', $this->typeRef($a->getReturn()->getProperty()->getType())->getTypeDoc()];
        $this
            ->doc($doc)
            ->add('public function ' . $a->getName() . '(' . implode(', ', $args) . ')');

        return $this;
    }

    public function build()
    {
        AbstractPhpSourceCodeBuilder::build();
        $cref = $this->classRef($this->metaService->getClassRef());
        if ($cref->getNamespace()) {
            $this->addLine('namespace ' . $cref->getNamespace() . ';');
        }
        $this
            ->newLine()
            ->add(
                'interface ' . $cref->getName()
                . ' extends ' . $this->classRef(\OnPhp\Services\Base\IService::class)->getFullName()
            )
            ->begin();

        foreach ($this->metaService->getActions() as $a) {
            $this
                ->newLine()
                ->addActionSignature($a)->add(';')
                ->newLine();
        }

        $this->end();
    }


}