import {Dictionary} from '../Common/Dictionary';
import {StructureProto} from "./StructureProto";
import {UnimplementedFeatureException} from "../Common/Exceptions";

export abstract class BaseStructure implements Dictionary<any> {

    static proto() : StructureProto {
        throw new UnimplementedFeatureException()
    }

}