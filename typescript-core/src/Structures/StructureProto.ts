import {Dictionary} from '../Common/Dictionary';
import {Form} from "../Forms/Form";
import {BaseStructure} from "./BaseStructure";

export interface StructureConstructor<T extends BaseStructure> {
    new() : T
    proto(): StructureProto;
}

export abstract class StructureProto {

    abstract getConstructor<T extends BaseStructure>() : StructureConstructor<T>;

    public getForm() : Form {
        return new Form();
    }

    static forClass<T extends BaseStructure>(constructor: StructureConstructor<T>) : StructureProto {
        return constructor.proto();
    }

    public make<T extends BaseStructure>(data: Dictionary<any>) : T {
        let constructor = this.getConstructor<T>();
        let structure: T = new constructor();
        this.fill(structure, data);
        return structure;
    }

    public fill(object: BaseStructure, data: Dictionary<any>) {
        let form = this.getForm();
        form.importData(data);
        for (let name in form.exportData()) {
            object[name] = form.getValue(name);
        }
        return this;
    }

}
