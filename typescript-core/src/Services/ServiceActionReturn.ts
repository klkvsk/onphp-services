import {Primitive} from "../Primitives/Primitive";

export class ServiceActionReturn {
    constructor(
        public primitive: Primitive<any>
    ) {}
}