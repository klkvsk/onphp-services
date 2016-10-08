import {BaseService} from "./typescript-core/src/Services/BaseService";
import {PrimitiveBoolean} from "../../../typescript-core/src/Primitives/PrimitiveBoolean";
import {PrimitiveInteger} from "../../../typescript-core/src/Primitives/PrimitiveInteger";
import {ServiceActionReturn} from "../../../typescript-core/src/Services/ServiceActionReturn";
import {Form} from "../../../typescript-core/src/Forms/Form";
import {ServiceActionParams} from "../../../typescript-core/src/Services/ServiceActionParams";
import {ServiceAction} from "../../../typescript-core/src/Services/ServiceAction";

export class TestService extends BaseService {

    protected actions = {
        'getList': () => new ServiceAction(
            'GET', '/list/:userId',
            new ServiceActionParams(
                new Form({
                    'page': new PrimitiveInteger()
                }),
                null,
                null,
            ),
            new ServiceActionReturn(
                new PrimitiveBoolean()
            )
        )
    };

    public getList(page:number = 0) {
        return this.action('getList').call(this.connector, {
            page
        });
    }
}