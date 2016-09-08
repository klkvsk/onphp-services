<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-11-20
 */
namespace OnPhp\Services\Base;

trait AbstractEnumTrait
{
    protected static $identityMap = [];

    public static function create($id)
    {
        \Assert::isIndexExists(self::getConstList(), $id);
        return self::$identityMap[$id] = isset(self::$identityMap[$id])
            ? self::$identityMap[$id]
            : new static($id);
    }

    public static function getConstList()
    {
        // filled in enum-specific trait
        return [];
    }

    abstract public function getId();

    public function getConst()
    {
        $map = static::getConstList();
        return $map[$this->getId()];
    }

    /**
     * @param self $enum
     * @return bool
     * @throws \WrongArgumentException
     */
    public function is(self $enum)
    {
        \Assert::isSame(get_class($this), get_class($enum));
        return $this->getId() == $enum->getId();
    }

    /**
     * @param self $enum
     * @return bool
     * @throws \WrongArgumentException
     */
    public function not(self $enum)
    {
        \Assert::isSame(get_class($this), get_class($enum));
        return $this->getId() != $enum->getId();
    }

    /**
     * @return string
     */
    public function toString()
    {
        return static::class . '::' . $this->getConst();
    }

}