import {Primitive} from "./Primitive";

export class PrimitiveBoolean extends Primitive<boolean> {
    public importValue(data:any) : boolean {
        this.value = !!data;
        return this.checkImportResult();
    }
}
