<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-11-24
 */

namespace OnPhp\Services\Codegen\Php;

use \OnPhp\Services\Meta\MetaStructure;

class PhpStructureAutoEntityProtoClassSourceCodeBuilder extends AbstractPhpSourceCodeBuilder
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
        $cref = $this->classRef($s->getClassRef())->addClassNamePrefix('AutoEntityProto');

        $primitiveCref = $this->classRef(\Primitive::class);

        if ($cref->getNamespace()) {
            $this->addLine('namespace ' . $cref->getNamespace() . ';');
        }
        $this->newLine();
        $this
            ->add('abstract class ' . $cref->getName())
            ->add(' extends ' . $this->classRef(\EntityProto::class)->getFullName())
            ->begin();

        $this
            ->newLine()
            ->add('public function className()')
            ->begin()
            ->add('return ' . $this->classRef($s->getClassRef())->getSelfName() . ';')
            ->end(); // of className()

        $this->newLine();

        if ($s->getParent()) {
            $this
                ->add('public function baseProto()')
                ->begin()
                ->add('return ' . $this->classRef($s->getParent()->getClassRef())->getStatic('entityProto()') . ';')
                ->end(); // of baseProto()
            $this->newLine();
        }

        $this
            ->add('public function getFormMapping()')
            ->begin()
            ->begin('return [');


        foreach ($s->getProperties() as $prop) {
            $this
                ->addValue($prop->getName())
                ->add(' => ')
                ->addPrimitiveDefinition($prop)
                ->add(',')
                ->newLine();;
        }
        $this
            ->end('];')
            ->end(); // of getFormMapping()


        $this->end(); // of class

    }

}