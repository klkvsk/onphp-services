import {Form} from "../Forms/Form";
import {Primitive} from "../Primitives/Primitive";
import {Dictionary} from "../Common";
import {IServiceConnector, IServiceConnectorRequest, IServiceConnectorResponse} from "./IServiceConnector";
import {ServiceActionParams} from "./ServiceActionParams";
import {ServiceActionReturn} from "./ServiceActionReturn";
import {FormValidationError} from "../Forms/FormValidationError";

export class ServiceAction {
    constructor(
        protected httpMethod:   "GET" | "POST" | "DELETE" | "PUT",
        protected httpPath:     string,
        protected params:       ServiceActionParams,
        protected returns:      ServiceActionReturn
    ) {}

    call(connector: IServiceConnector, params: Dictionary<any>) : Promise<IServiceConnectorResponse> {
        var errors = {};
        for (let p of [ this.params.route, this.params.query, this.params.body ]) {
            if (!p) {
                continue;
            }
            if (!p.importData(params)) {
                let e = p.getErrors();
                for (let name in e) {
                    errors[name] = e[name];
                }
            }
        }

        if (Object.keys(errors).length > 0) {
            throw new FormValidationError(errors);
        }

        // put route parameters into :placeholders
        var httpPath = this.httpPath.replace(
            /:([a-z][a-z0-9_]+)/ig,
            function (_: string, placeholderName: string) : string {
                if (!this.params.route) {
                    return '';
                }
                let value = this.params.route.getValue(placeholderName);
                return value ? value.toString() : '';
            }
        );

        let request = <IServiceConnectorRequest> {
            httpMethod: this.httpMethod,
            httpPath: httpPath,
            query: this.params.query.exportData(),
            body:  this.params.body.exportData()
        };

        return connector.doRequest(request);
    }
}