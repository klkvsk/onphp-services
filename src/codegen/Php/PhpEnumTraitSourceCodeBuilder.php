<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-11-20
 */

namespace OnPhp\Services\Codegen\Php;

use \OnPhp\Services\Base\AbstractEnumTrait;
use \OnPhp\Services\Meta\MetaEnum;

class PhpEnumTraitSourceCodeBuilder extends AbstractPhpSourceCodeBuilder
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
        parent::build();
        if ($traitCref->getNamespace()) {
            $this->addLine('namespace ' . $traitCref->getNamespace() . ';');
        }
        $this
            ->newLine()
            ->add('trait ' . $traitCref->getName())
            ->begin()
            ->addLine('use ' . $this->classRef(AbstractEnumTrait::class)->getFullName() . ';')
            ->newLine();

        $this
            ->add('final public static function getConstList()')
            ->begin()
            ->add('return ')
            ->begin('[');
        foreach ($this->metaEnum->getValues() as $metaEnumValue) {
            $this
                ->add($valuesCref->getStatic($metaEnumValue->getConst()))
                ->add(' => ')
                ->addValue($metaEnumValue->getConst())
                ->add(',')
                ->newLine();
        }
        $this
            ->end('];')
            ->end()
            ->newLine();

        $this
            ->add('final public ' . ($this->type != 'Enumeration' ? 'static ' : '') . 'function getNameList()')
            ->begin();
        if ($this->type == 'Enum' || $this->type == 'Enumeration') {
            $this
                ->add('\Assert::isEmpty(')
                ->add($this->type == 'Enum' ? 'static::$names' : '$this->names')
                ->add(', ')
                ->addValue('$names not empty, it should not be filled when using enum traits')
                ->add(');')
                ->newLine();
        }
        $this
            ->add('return ')
            ->begin('[');
        foreach ($this->metaEnum->getValues() as $metaEnumValue) {
            $this
                ->add($valuesCref->getStatic($metaEnumValue->getConst()))
                ->add(' => ');
            if (PhpSourceCodeGenerator::$localizationFunction) {
                $this->add(PhpSourceCodeGenerator::$localizationFunction);
            }
            $this
                ->add('(')
                ->addValue($metaEnumValue->getName())
                ->add('),')
                ->newLine();
        }
        $this
            ->end('];')
            ->end()
            ->newLine();

        foreach ($this->metaEnum->getValues() as $metaEnumValue) {
            $this
                ->doc([
                    ['@return', $cref->getFullName()]
                ])
                ->add('public static function ' . self::constNameToMethodName($metaEnumValue->getConst()) . '()')
                ->begin()
                ->addLine('return static::create(' . $valuesCref->getStatic($metaEnumValue->getConst()) . ');')
                ->end()
                ->newLine();
        }

        if ($this->type == 'Enum') {
            $this->addInternalIdFix();
        }

        $this->end();
    }

    public static function constNameToMethodName($constName)
    {
        return preg_replace_callback(
            '/_([a-z])/',
            function ($m) {
                return strtoupper($m[1]);
            },
            strtolower($constName)
        );
    }

    public function addInternalIdFix()
    {
        $this
            ->addLine('// support for dumb setInternalId which does not use getNameList')
            ->add('protected function setInternalId($id)')
            ->begin()
            ->add('if ($this instanceof \Enum)')
            ->begin()
            ->addLine('$tmp = static::$names;')
            ->addLine('static::$names = static::getNameList();')
            ->addLine('parent::setInternalId($id);')
            ->addLine('static::$names = $tmp;')
            ->end()
            ->addLine('return $this;')
            ->end();
        return $this;
    }

}