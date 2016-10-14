import {Primitive} from "./Primitive";
import {DateOnly} from "../Common/Date";
import {WrongArgumentException} from "../Common/Exceptions";

export class PrimitiveDateOnly extends Primitive<DateOnly> {
    public importValue(data:any): boolean {
        try {
            this.value = new DateOnly(data);
        } catch (e) {
            if (e instanceof WrongArgumentException) {
                return false;
            } else {
                throw e;
            }
        }
        return this.checkImportResult();
    }
}