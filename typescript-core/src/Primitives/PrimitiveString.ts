import {Primitive} from "./Primitive";

export class PrimitiveString extends Primitive<string> {
    public importValue(data:any): boolean {
        this.value = "" + data;
        return this.checkImportResult();
    }
}