import {Form} from "../Forms/Form";
import {IServiceConnector} from "./IServiceConnector";
import {ServiceAction} from "./ServiceAction";
import {ServiceActionParams} from "./ServiceActionParams";
import {Dictionary} from "../Common/Dictionary";
import {PrimitiveBoolean} from "../Primitives/PrimitiveBoolean";
import {PrimitiveInteger} from "../Primitives/PrimitiveInteger";
import {ServiceActionReturn} from "./ServiceActionReturn";

export abstract class BaseService {

    protected actionInstances: Dictionary<ServiceAction> = {};
    protected abstract actions: Dictionary<() => ServiceAction>;

    constructor(
        protected connector: IServiceConnector
    ) {
    }

    protected action(name: string) : ServiceAction {
        return this.actionInstances[name] = this.actionInstances[name] || this.actions[name]();
    };
}