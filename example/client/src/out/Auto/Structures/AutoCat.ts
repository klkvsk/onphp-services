import {BaseStructure} from "../../../../../../typescript-core/src/Structures/BaseStructure";
import {Singleton} from "../../../../../../typescript-core/src/Common/Singleton";
import {ProtoCat} from "../../Structures/Proto/ProtoCat";

export abstract class AutoCat extends BaseStructure {

    protected name : string = null;

    public static proto() : ProtoCat {
        return Singleton.getInstance<ProtoCat>(ProtoCat);
    }

    public setName(name : string = null) {
        this.name = name;
        return this;
    }

    public getName() {
        return this.name;
    }

}