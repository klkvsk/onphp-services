import {Dictionary} from "../Common/Dictionary";
import {PrimitiveImportError} from "../Primitives/PrimitiveImportError";
import {Exception} from "../Common/Exceptions";

export class FormValidationException extends Exception {

    constructor(public errors: Dictionary<PrimitiveImportError>) {
        super();
    }

    toString(): string {
        return JSON.stringify(this.errors);
    }
}