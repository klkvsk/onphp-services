import {PrimitiveImportError} from "./PrimitiveImportError";
import {PrimitiveArray} from "./PrimitiveArray";

export abstract class Primitive<T> {
    protected value: T;
    protected required: boolean = false;
    protected error: PrimitiveImportError;

    public abstract importValue(data: any) : boolean;

    public clean() : void {
        this.value = null;
        this.error = null;
    }

    public getValue() : T {
        if (this.error) {
            return null;
        }
        return this.value;
    }

    public getError() : PrimitiveImportError {
        return this.error;
    }

    public setRequired(required: boolean) {
        this.required = required;
        return this;
    }

    public checkImportResult() : boolean {
        if (this.required && !this.value) {
            this.error = PrimitiveImportError.MISSING;
            return false;
        }
        return true;
    }
}