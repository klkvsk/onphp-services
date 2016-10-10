import {Dictionary} from "../Common/Dictionary";
import {Primitive} from "../Primitives/Primitive";
import {PrimitiveImportError} from "../Primitives/PrimitiveImportError";
import {PrimitiveString} from "../Primitives/PrimitiveString";

export class PrimitiveFactory {
    test() {
        return new PrimitiveString();
    }
}

export class Form {
    protected primitives: Dictionary<Primitive<any>>;

    constructor(primitives?: Dictionary<Primitive<any>>) {
        if (primitives) {
            for (let name in primitives) {
                this.add(name, primitives[name]);
            }
        }
    }

    public add(name: string, primitive: Primitive<any>) : Form {
        this.primitives[name] = primitive;
        return this;
    }

    public clean() : Form {
        for (let p in this.primitives) {
            this.primitives[p].clean();
        }
        return this;
    }

    public importData(data: Dictionary<any>): boolean {
        this.clean();

        var result: boolean = true;
        for (let name in this.primitives) {
            if (data.hasOwnProperty(name)) {
                result = result && this.primitives[name].importValue(data[name])
            }
        }

        return result;
    }

    public getErrors() : Dictionary<PrimitiveImportError> {
        var errors: Dictionary<PrimitiveImportError> = {};
        for (let name in this.primitives) {
            if (this.primitives[name].getError()) {
                errors[name] = this.primitives[name].getError();
            }
        }

        return errors;
    }

    public getValue(name: string) {
        return this.primitives[name].getValue();
    }

    public exportData() : Dictionary<any> {
        var data: Dictionary<any> = {};
        for (let name in this.primitives) {
            data[name] = this.primitives[name].getValue();
        }
        return data;
    }
}
