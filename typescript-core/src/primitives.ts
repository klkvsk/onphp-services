/**
 * Created by klkvsk on 15.12.2015.
 */
export module ngphp {

    export enum PrimitiveImportError {
        WRONG = 1,
        MISSING = 2,
    }

    export abstract class Primitive<T> {
        protected name: string;
        protected value: T;
        protected required: boolean = false;
        protected error: PrimitiveImportError;

        constructor(name: string) {
            this.name = name;
        }

        public abstract importValue(data: any) : boolean;

        public clean() : void {
            this.value = null;
            this.error = null;
        }

        public getName() : string {
            return this.name;
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
        }

        public checkImportResult() : boolean {
            if (this.required && !this.value) {
                this.error = PrimitiveImportError.MISSING;
                return false;
            }
            return true;
        }
    }

    export interface IStructureConstructor<TStructure extends BaseStructure> {
        new(data:any) : TStructure
    }


    export class PrimitiveString extends Primitive<string> {
        public importValue(data:any): boolean {
            this.value = "" + data;
            return this.checkImportResult();
        }
    }

    export class PrimitiveBoolean extends Primitive<boolean> {
        public importValue(data:any) : boolean {
            this.value = !!data;
            return this.checkImportResult();
        }
    }

    class PrimitiveFloat extends Primitive<number> {
        public importValue(data:any) : boolean {
            this.value = +data;
            return this.checkImportResult();
        }
    }

    class PrimitiveInteger extends Primitive<number> {
        public importValue(data:any) : boolean {
            this.value = data | 0;
            return this.checkImportResult();
        }
    }

    class PrimitiveStructure<TStructure extends BaseStructure> extends Primitive<Object> {
        protected ctor: IStructureConstructor<TStructure>;

        public setConstructor(ctor: IStructureConstructor<TStructure>) {
            this.ctor = ctor;
            return this;
        }

        public getConstructuror() {
            return this.ctor;
        }

        public importValue(data:any): boolean {
            this.value = new this.ctor(data);
            return this.checkImportResult();
        }

        public checkImportResult():boolean {
            if (!super.checkImportResult()) {
                return false;
            }
            if (this.value && !(this.value instanceof this.ctor)) {
                this.error = PrimitiveImportError.WRONG;
                return false;
            }
            return true;
        }

    }

    class PrimitiveArray<T> extends Primitive<Array<T>> {
        protected primitive:Primitive<T>;
        protected subErrors:PrimitiveImportError[];

        public setPrimitive(primitive: Primitive<T>) {
            this.primitive = primitive;
            return this;
        }

        public getPrimitive() : Primitive<T> {
            return this.primitive;
        }

        public importValue(data:Array<any>):boolean {
            this.clean();
            if (data instanceof Array) {
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
        }


        public clean():void {
            super.clean();
            this.subErrors = [];
        }

        public getSubErrors() : PrimitiveImportError[] {
            return this.subErrors;
        }
    }

}
