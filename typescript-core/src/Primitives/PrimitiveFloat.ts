import {Primitive} from "./Primitive";

export class PrimitiveFloat extends Primitive<number> {
    public importValue(data:any) : boolean {
        this.value = +data;
        return this.checkImportResult();
    }
}
