import {Dictionary} from "../Common/Dictionary";
import {Primitive} from "../Primitives/Primitive";
import {PrimitiveString} from "../Primitives/PrimitiveString";
import {PrimitiveImportError} from "../Primitives/PrimitiveImportError";
import {ObjectNotFoundException} from "../Common/Exceptions";

export class PrimitiveFactory {
    test() {
        return new PrimitiveString();
    }
}

export class Form {
    protected primitives: Dictionary<Primitive<any>> = {};

    constructor(primitives?: Dictionary<Primitive<any>>) {
        if (primitives) {
            for (let name in primitives) {
                this.add(name, primitives[name]);
            }
        }
    }

    public getPrimitives() : Dictionary<Primitive<any>> {
        return this.primitives;
    }

    public getPrimitiveNames() : string[] {
        let names: string[] = [];
        for (let name in this.primitives) {
            names.push(name);
        }
        return names;
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
                let importResult = this.primitives[name].importValue(data[name]);
                result = result && importResult;
            }
        }

        return result;
    }

    public getErrors() : Dictionary<PrimitiveImportError> {
        let errors: Dictionary<PrimitiveImportError> = {};
        for (let name in this.primitives) {
            if (this.primitives[name].getError()) {
                errors[name] = this.primitives[name].getError();
            }
        }

        return errors;
    }

    public getValue(name: string) {
        if (this.primitives.hasOwnProperty(name)) {
            return this.primitives[name].getValue();
        } else {
            throw new ObjectNotFoundException('form has no primitive "' + name + '"')
        }
    }

    public exportData() : Dictionary<any> {
        var data: Dictionary<any> = {};
        for (let name in this.primitives) {
            data[name] = this.primitives[name].getValue();
        }
        return data;
    }
}
