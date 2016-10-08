<?php
/**
 * This file is auto-generated
 * /!\ DO NOT MODIFY /!\
 * 
 * @nodiff last modified 2016-09-11 13:09:02
 */


abstract class AutoEntityProtoList_Cat extends \EntityProto {
    
    public function className() {
        return \List_Cat::class;
    }
    
    public function getFormMapping() {
        return [
            'items' => \Primitive::form('items')    
                ->ofProto(\Cat::entityProto())
                ->required()
                ->arrayOf()
            ,
            'count' => \Primitive::integer('count')    
                ->required()
            ,
        ];
    }

}
