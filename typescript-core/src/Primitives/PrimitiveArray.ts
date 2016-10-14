import {Primitive} from "./Primitive";
import {PrimitiveImportError} from "./PrimitiveImportError";

export class PrimitiveArray<T> extends Primitive<T[]> {

    protected primitive:Primitive<T>;
    protected subErrors:PrimitiveImportError[];

    public setPrimitive(primitive: Primitive<T>) {
        this.primitive = primitive;
        return this;
    }

    public getPrimitive() : Primitive<T> {
        return this.primitive;
    }

    public importValue(data:any[]):boolean {
        this.clean();
        if (!Array.isArray(data)) {
            this.error = PrimitiveImportError.WRONG;
            return false;
        }

        this.value = new Array(data.length);
        for (var i:number = 0; i < data.length; i++) {
            this.primitive.clean();
            this.primitive.importValue(data[i]);
            var error = this.primitive.getError();
            var value = this.primitive.getValue();
            if (error) {
                this.subErrors[i] = error;
            }
            this.value[i] = value;
        }

        return this.checkImportResult();
    }


    public clean():void {
        super.clean();
        this.subErrors = [];
    }

    public getSubErrors() : PrimitiveImportError[] {
        return this.subErrors;
    }
}