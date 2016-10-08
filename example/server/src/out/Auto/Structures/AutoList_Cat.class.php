<?php
/**
 * This file is auto-generated
 * /!\ DO NOT MODIFY /!\
 * 
 * @nodiff last modified 2016-09-11 13:09:02
 */


abstract class AutoList_Cat extends \OnPhp\Services\Base\AbstractStructure implements \PrototypedEntity {
    /**
     * @var $items \Cat[]
     */
    protected $items = null;
    /**
     * @var $count int
     */
    protected $count = null;
    
    /**
     * @return \EntityProtoList_Cat
     */
    public static function entityProto() {
        return \Singleton::getInstance(\EntityProtoList_Cat::class);
    }
    
    public function setItems(array $items = null) {
        $this->items = $items;
        return $this;
    }
    
    public function getItems() {
        return $this->items;
    }
    
    public function setCount(int $count = null) {
        $this->count = $count;
        return $this;
    }
    
    public function getCount() {
        return $this->count;
    }

}
