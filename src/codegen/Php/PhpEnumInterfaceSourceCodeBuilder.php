<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-11-20
 */

namespace OnPhp\Services\Codegen\Php;

use \OnPhp\Services\Meta\MetaEnum;

class PhpEnumInterfaceSourceCodeBuilder extends AbstractPhpSourceCodeBuilder
{
    /** @var MetaEnum */
    protected $metaEnum;
    public static $arrayInConsts = false;

    public static function create(MetaEnum $metaEnum)
    {
        $self = new static;
        $self->metaEnum = $metaEnum;
        return $self;
    }

    public function build()
    {
        $cref = $this->classRef($this->metaEnum->getValuesClassRef());
        parent::build();
        if ($cref->getNamespace()) {
            $this->addLine('namespace ' . $cref->getNamespace() . ';');
        }
        $this
            ->newLine()
            ->add('interface ' . $cref->getName())
            ->begin();

        $this->newLine();

        foreach ($this->metaEnum->getValues() as $metaEnumValue) {
            $const = strtoupper($metaEnumValue->getConst());
            $id = $metaEnumValue->getId();
            if (\Assert::checkInteger($id)) {
                $id = (int)$id;
            }
            $this->add("const $const = ")->addValue($id)->add(';')->newLine();
        }

        $this->end();
    }

}