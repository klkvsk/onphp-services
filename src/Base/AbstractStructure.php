<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-12-02
 */

namespace OnPhp\Services\Base;

abstract class AbstractStructure implements \JsonSerializable, \PrototypedEntity
{

    /**
     * @return static
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @param array $data
     * @return static
     * @throws Exceptions\StructureValidationException
     */
    public static function make(array $data)
    {
        $scope2form = \ScopeToFormImporter::create(static::entityProto());
        /** @var \Form $form */
        $form = $scope2form->compile($data);

        if ($form->getErrors()) {
            throw new Exceptions\StructureValidationException('could not import ' . static::class, $form);
        }

        $form2object = \FormToObjectConverter::create(static::entityProto());
        /** @var static $object */
        $object = $form2object->compile($form);

        return $object;
    }

    /**
     * @return array
     * @throws Exceptions\StructureValidationException
     */
    public function export()
    {
        $object2form = \ObjectToFormConverter::create(static::entityProto());
        /** @var \Form $form */
        $form = $object2form->compile($this);

        if ($form->getErrors()) {
            throw new Exceptions\StructureValidationException('could not export ' . static::class, $form);
        }

        $form2scope = \FormToScopeExporter::create(static::entityProto());
        /** @var array $data */
        $data = $form2scope->compile($form);

        return $data;
    }

    function jsonSerialize()
    {
        return $this->export();
    }
}