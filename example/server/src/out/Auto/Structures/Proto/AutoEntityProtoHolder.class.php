<?php
/**
 * This file is auto-generated
 * /!\ DO NOT MODIFY /!\
 * 
 * @nodiff last modified 2016-09-11 13:12:24
 */


abstract class AutoEntityProtoHolder extends \EntityProto {
    
    public function className() {
        return \Holder::class;
    }
    
    public function getFormMapping() {
        return [
            'cats' => \Primitive::form('cats')    
                ->ofProto(\List_Cat::entityProto())
                ->required()
            ,
            'dogs' => \Primitive::form('dogs')    
                ->ofProto(\List_Dog::entityProto())
                ->required()
            ,
        ];
    }

}
