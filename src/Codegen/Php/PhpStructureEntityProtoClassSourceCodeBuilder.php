<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-11-24
 */

namespace OnPhp\Services\Codegen\Php;

use \OnPhp\Services\Meta\MetaStructure;

class PhpStructureEntityProtoClassSourceCodeBuilder extends AbstractPhpSourceCodeBuilder
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
        $this
            ->clean()
            ->addLine('<?php')
            ->addHeaderDocForGeneratedOnce()
            ->newLine();

        $s = $this->metaStructure;
        $cref = $this->classRef($s->getClassRef())->addClassNamePrefix('EntityProto');
        $parentCref = $this->classRef($s->getClassRef())->addClassNamePrefix('AutoEntityProto');

        if ($cref->getNamespace()) {
            $this->addLine('namespace ' . $cref->getNamespace() . ';');
        }

        $this->newLine();
        $this
            ->add('class ' . $cref->getName())
            ->add(' extends ' . $parentCref->getFullName())
            ->begin()
            ->add('// your code goes here...')
            ->end();
    }

}