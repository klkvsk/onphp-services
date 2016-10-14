import {Primitive} from "./Primitive";
import {PrimitiveImportError} from "./PrimitiveImportError";
import {BaseStructure} from "../Structures/BaseStructure";
import {StructureProto} from "../Structures/StructureProto";

export class PrimitiveStructure<TStructure extends BaseStructure> extends Primitive<Object> {
    protected proto: StructureProto;

    public setProto(proto: StructureProto) {
        this.proto = proto;
        return this;
    }

    public getProto() {
        return this.proto;
    }

    public importValue(data:any): boolean {
        this.value = this.proto.make(data);
        return this.checkImportResult();
    }

    public checkImportResult():boolean {
        if (!super.checkImportResult()) {
            return false;
        }
        if (this.value && !(this.value instanceof this.proto.getConstructor())) {
            this.error = PrimitiveImportError.WRONG;
            return false;
        }
        return true;
    }

}