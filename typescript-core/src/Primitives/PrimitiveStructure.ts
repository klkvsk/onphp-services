import {Primitive} from "./Primitive";
import {PrimitiveImportError} from "./PrimitiveImportError";
import {BaseStructure} from "../Structures/BaseStructure";

export interface IStructureConstructor<TStructure extends BaseStructure> {
    new(data:any) : TStructure
}

export class PrimitiveStructure<TStructure extends BaseStructure> extends Primitive<Object> {
    protected ctor: IStructureConstructor<TStructure>;

    public setConstructor(ctor: IStructureConstructor<TStructure>) {
        this.ctor = ctor;
        return this;
    }

    public getConstructuror() {
        return this.ctor;
    }

    public importValue(data:any): boolean {
        this.value = new this.ctor(data);
        return this.checkImportResult();
    }

    public checkImportResult():boolean {
        if (!super.checkImportResult()) {
            return false;
        }
        if (this.value && !(this.value instanceof this.ctor)) {
            this.error = PrimitiveImportError.WRONG;
            return false;
        }
        return true;
    }

}