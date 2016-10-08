<?php
/**
 * This file is auto-generated
 * /!\ DO NOT MODIFY /!\
 * 
 * @nodiff last modified 2016-09-11 13:09:02
 */


abstract class AutoList_Dog extends \OnPhp\Services\Base\AbstractStructure implements \PrototypedEntity {
    /**
     * @var $items \Dog[]
     */
    protected $items = null;
    /**
     * @var $count int
     */
    protected $count = null;
    
    /**
     * @return \EntityProtoList_Dog
     */
    public static function entityProto() {
        return \Singleton::getInstance(\EntityProtoList_Dog::class);
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
