import {Form} from "../Forms/Form";

export class ServiceActionParams {
    constructor(
        public route: Form,
        public query: Form,
        public body: Form,
    ) {}
}