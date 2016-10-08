<?php
/**
 * This file is auto-generated
 * /!\ DO NOT MODIFY /!\
 * 
 * @nodiff last modified 2016-09-11 13:09:02
 */


abstract class AutoEntityProtoCat extends \EntityProto {
    
    public function className() {
        return \Cat::class;
    }
    
    public function getFormMapping() {
        return [
            'name' => \Primitive::string('name')    
                ->required()
            ,
        ];
    }

}
