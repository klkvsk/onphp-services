import {BaseStructure} from "../../../../../../typescript-core/src/Structures/BaseStructure";

export abstract class AutoCat extends BaseStructure {

    protected name : string = null;

    public static entityProto() : ProtoCat {
        return ProtoCat.instance();
    }

    public setName(name : string = null) {
        this.name = name;
        return this;
    }

    public getName() {
        return this.name;
    }

}