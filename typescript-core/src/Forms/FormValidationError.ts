import {Dictionary} from "../Common";
import {PrimitiveImportError} from "../Primitives/PrimitiveImportError";

export class FormValidationError extends Error {

    constructor(public errors: Dictionary<PrimitiveImportError>) {
        super();
    }

    toString(): string {
        return JSON.stringify(this.errors);
    }
}