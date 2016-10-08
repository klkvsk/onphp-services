import {Dictionary} from '../Common';
import {Form} from "../Forms/Form";
import {BaseStructure} from "./BaseStructure";

export abstract class StructureProto {

    abstract getConstructor();

    abstract getForm() : Form


    static forClass(constructor: typeof BaseStructure) {
        constructor.proto();
    }
}
