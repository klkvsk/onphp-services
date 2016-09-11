<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-11-20
 */
namespace OnPhp\Services\Meta;

class MetaEnumValue
{
    protected $id;
    protected $const;
    protected $name;

    protected function __construct($id, $const, $name = null)
    {
        $this->id = $id;
        $this->const = $const;
        $this->name = $name ?: $const;
    }

    public static function create($id, $const, $name = null)
    {
        return new self($id, $const, $name);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getConst()
    {
        return $this->const;
    }

    public function getName()
    {
        return $this->name;
    }

}