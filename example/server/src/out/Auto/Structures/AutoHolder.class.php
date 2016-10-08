<?php
/**
 * This file is auto-generated
 * /!\ DO NOT MODIFY /!\
 * 
 * @nodiff last modified 2016-09-11 13:12:24
 */


abstract class AutoHolder extends \OnPhp\Services\Base\AbstractStructure implements \PrototypedEntity {
    /**
     * @var $cats \List_Cat
     */
    protected $cats = null;
    /**
     * @var $dogs \List_Dog
     */
    protected $dogs = null;
    
    /**
     * @return \EntityProtoHolder
     */
    public static function entityProto() {
        return \Singleton::getInstance(\EntityProtoHolder::class);
    }
    
    public function setCats(\List_Cat $cats = null) {
        $this->cats = $cats;
        return $this;
    }
    
    public function getCats() {
        return $this->cats;
    }
    
    public function setDogs(\List_Dog $dogs = null) {
        $this->dogs = $dogs;
        return $this;
    }
    
    public function getDogs() {
        return $this->dogs;
    }

}
