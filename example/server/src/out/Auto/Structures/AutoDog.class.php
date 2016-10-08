<?php
/**
 * This file is auto-generated
 * /!\ DO NOT MODIFY /!\
 * 
 * @nodiff last modified 2016-09-11 13:09:02
 */


abstract class AutoDog extends \OnPhp\Services\Base\AbstractStructure implements \PrototypedEntity {
    /**
     * @var $name string
     */
    protected $name = null;
    
    /**
     * @return \EntityProtoDog
     */
    public static function entityProto() {
        return \Singleton::getInstance(\EntityProtoDog::class);
    }
    
    public function setName(string $name = null) {
        $this->name = $name;
        return $this;
    }
    
    public function getName() {
        return $this->name;
    }

}
