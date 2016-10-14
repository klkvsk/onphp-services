import {Primitive} from "./Primitive";
import {DateTime} from "../Common/Date";
import {WrongArgumentException} from "../Common/Exceptions";

export class PrimitiveDateOnly extends Primitive<DateTime> {
    public importValue(data:any): boolean {
        try {
            this.value = new DateTime(data);
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