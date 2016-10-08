<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-11-24
 */

namespace OnPhp\Services\Codegen\Typescript;

use \OnPhp\Services\Meta\MetaStructure;

class TypescriptStructureAutoClassSourceCodeBuilder extends AbstractTypescriptSourceCodeBuilder
{

    /** @var MetaStructure */
    protected $metaStructure;

    /**
     * @param MetaStructure $metaStructure
     * @return self
     */
    public static function create(MetaStructure $metaStructure)
    {
        $self = new self;
        $self->metaStructure = $metaStructure;
        return $self;
    }

    public function build()
    {
        parent::build();

        $s = $this->metaStructure;
        $cref = $this->classRef($s->getClassRef())->addClassNamePrefix('Auto');
        //$crefEP = $this->classRef($s->getClassRef())->addClassNamePrefix('EntityProto');

        if ($cref->getNamespace()) {
            $this->addLine('namespace ' . $cref->getNamespace() . ';');
        }

        if ($s->getParent()) {
            $parentCref = $this->classRef($s->getParent()->getClassRef());
        } else {
            $parentCref = $this->classRef('BaseStructure');
        }

        $this->newLine();
        $this
            ->add('abstract class ' . $cref->getName())
            ->add(' extends ' . $parentCref->getFullName())
            ->begin();

        foreach ($s->getProperties() as $prop) {
            $this->doc([
                ['@var', '$' . $prop->getName(), $this->typeRef($prop->getType())->getTypeDoc()]
            ]);
            $this->addLine('protected $' . $prop->getName() . ' = ' . 'null;');
        }

        $this->newLine();
        $this
            ->doc([
                ['@return', $crefEP->getFullName()]
            ])
            ->add('public static function entityProto()')
            ->begin()
            ->add('return ' . $this->classRef(\Singleton::class)->getStatic('getInstance'))
            ->addArguments([$crefEP])
            ->add(';')
            ->end(); // of entityProto()

        foreach ($s->getProperties() as $prop) {
            $this->newLine();

            // setter
            $this
                ->add('public function set' . ucfirst($prop->getName()))
                ->add('(' . $this->typeRef($prop->getType())->getTypeHint() . ' $' . $prop->getName() . ' = null)')
                ->begin()
                ->addLine('$this->' . $prop->getName() . ' = $' . $prop->getName() . ';')
                ->addLine('return $this;')
                ->end();

            $this->newLine();

            // getter
            $this
                ->add('public function get' . ucfirst($prop->getName()) . '()')
                ->begin()
                ->add('return $this->' . $prop->getName() . ';')
                ->end();
        }

        $this->end(); // of class
    }

}