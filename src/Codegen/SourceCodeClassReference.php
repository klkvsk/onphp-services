<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-11-13
 */
namespace OnPhp\Services\Codegen;

class SourceCodeClassReference
{
    const NAMESPACE_NAMESPACE_SEPARATOR = '.';
    const NAMESPACE_CLASSNAME_SEPARATOR = '.';
    const FULLNAME_PREFIX = '';

    /**
     * @var string[]
     */
    protected $namespaceParts = [];

    /**
     * @var string
     */
    protected $name;

    protected function __construct()
    {
    }

    /**
     * These would work the same, referencing class "C" in namespace "B" of another namespace "A":
     * ::create('\A\B\C')
     * ::create('A\B\C')
     * ::create('\A\B', 'C')
     * ::create('A', 'B', 'C')
     * ::create('A', null, 'B/C')
     *
     * "." and "::" would work as separators too:
     * ::create('A.B.C')
     * ::create('A::B::C')
     * @param $namepart
     * @param null $namepart___
     * @return static
     * @throws \WrongArgumentException
     */
    public static function create($namepart, $namepart___ = null)
    {
        $nameparts = func_get_args();

        $self = new static;

        foreach ($nameparts as $namepart) {
            if ($namepart === null)
                continue;

            \Assert::isString($namepart);

            // split namepart by name-only parts, using all common syntaxes
            $namespaceParts = preg_split('/[^A-Z0-9_]+/i', $namepart);

            // filter out empty parts
            // e.g. if $namespace = '\A\B' gives 3 parts, first being empty -- we dont need that
            $namespaceParts = array_filter($namespaceParts);

            $self->namespaceParts = array_merge(
                $self->namespaceParts,
                $namespaceParts
            );
        }

        $self->name = array_pop($self->namespaceParts);
        \Assert::isNotEmpty($self->name);

        return $self;
    }

    /**
     * This is a way to downcast this common class reference to language-specific class reference
     * Language-specific implementation should extend this class
     * @param self $base
     * @return $this
     */
    public static function wrap(self $base)
    {
        $self = new static;
        $self->namespaceParts = $base->namespaceParts;
        $self->name = $base->name;
        return $self;
    }

    /**
     * Get only class name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get full class name (with namespace) as a string
     * @return string
     */
    public function getFullName()
    {
        $namespace = $this->getNamespace();
        $name = static::FULLNAME_PREFIX;
        if ($namespace) {
            $name .= $namespace . static::NAMESPACE_CLASSNAME_SEPARATOR;
        }
        $name .= $this->getName();
        return $name;
    }

    /**
     * Get namespace as a string
     * @return string
     */
    public function getNamespace()
    {
        return implode(static::NAMESPACE_NAMESPACE_SEPARATOR, $this->getNamespaceParts());
    }

    /**
     * Get namespace path as array of namespaces
     * @return \string[]
     */
    public function getNamespaceParts()
    {
        return $this->namespaceParts;
    }

    /**
     * Get full class name with namespace parts as array
     * @return \string[]
     */
    public function getNameParts()
    {
        $parts = $this->getNamespaceParts();
        $parts [] = $this->getName();
        return $parts;
    }

    /**
     * @param $prefix
     * @return $this
     */
    public function addClassNamePrefix($prefix)
    {
        $this->name = $prefix . $this->name;
        return $this;
    }

    /**
     * @param $suffix
     * @return $this
     */
    public function addClassNameSuffix($suffix)
    {
        $this->name = $this->name . $suffix;
        return $this;
    }
}