import {Dictionary} from '../Common';

export abstract class BaseStructure implements Dictionary<any> {

    public static proto() {
        this.constructor.name;
    }

    public fill(data: Dictionary<any>) {

    }

}

class SomeStructure extends BaseStructure {}

SomeStructure.proto();