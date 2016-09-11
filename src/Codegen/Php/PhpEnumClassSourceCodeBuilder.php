<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-11-20
 */

namespace OnPhp\Services\Codegen\Php;

use \OnPhp\Services\Meta\MetaEnum;

class PhpEnumClassSourceCodeBuilder extends AbstractPhpSourceCodeBuilder
{
    /** @var MetaEnum */
    protected $metaEnum;
    /** @var string "Enumeration" or "Enum" */
    protected $type;

    public static function create(MetaEnum $metaEnum, $type = 'Enumeration')
    {
        $self = new static;
        $self->metaEnum = $metaEnum;
        $self->type = $type;
        return $self;
    }

    public function build()
    {
        $cref = $this->classRef($this->metaEnum->getClassRef());
        $traitCref = $this->classRef($this->metaEnum->getTraitClassRef());
        $valuesCref = $this->classRef($this->metaEnum->getValuesClassRef());

        $this
            ->clean()
            ->addLine('<?php')
            ->addHeaderDocForGeneratedOnce()
            ->newLine();

        if ($traitCref->getNamespace()) {
            $this->addLine('namespace ' . $cref->getNamespace() . ';');
        }

        $this
            ->newLine()
            ->add('class ' . $cref->getName())
            ->add(' extends ' . $this->classRef($this->type)->getFullName())
            ->add(' implements ' . $valuesCref->getFullName())
            ->begin()
            ->add('use ' . $traitCref->getFullName() . ';')
            ->end();
    }
}