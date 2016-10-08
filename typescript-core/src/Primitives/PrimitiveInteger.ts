import {Primitive} from "./Primitive";

export class PrimitiveInteger extends Primitive<number> {
    protected min: number = null;
    protected max: number = null;

    public importValue(data:any) : boolean {
        this.value = data | 0;
        return this.checkImportResult();
    }

    public setMin(min: number) : PrimitiveInteger {
        this.min = min;
        return this;
    }

    public getMin() : number {
        return this.min;
    }

    public setMax(max: number ) : PrimitiveInteger {
        this.max = max;
        return this;
    }

    public getMax() : number {
        return this.max;
    }

    public checkImportResult(): boolean {
        return super.checkImportResult()
            && (this.getMin() !== null && this.getValue() < this.getMin())
            && (this.getMax() !== null && this.getValue() > this.getMax());
    }
}