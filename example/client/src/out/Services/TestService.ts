import {BaseService} from "../../../../../typescript-core/src/Services/BaseService";
import {PrimitiveInteger} from "../../../../../typescript-core/src/Primitives/PrimitiveInteger";
import {Form} from "../../../../../typescript-core/src/Forms/Form";
import {ServiceActionParams} from "../../../../../typescript-core/src/Services/ServiceActionParams";
import {PrimitiveBoolean} from "../../../../../typescript-core/src/Primitives/PrimitiveBoolean";
import {ServiceActionReturn} from "../../../../../typescript-core/src/Services/ServiceActionReturn";
import {ServiceAction} from "../../../../../typescript-core/src/Services/ServiceAction";
import {PrimitiveArray} from "../../../../../typescript-core/src/Primitives/PrimitiveArray";
import {PrimitiveStructure} from "../../../../../typescript-core/src/Primitives/PrimitiveStructure";
import {Cat} from "../Structures/Cat";

export class TestService extends BaseService {

    protected actions = {
        'getList': () => new ServiceAction(
            'GET', '/list/:page',
            new ServiceActionParams(
                new Form({
                    'page': new PrimitiveInteger()
                }),
                null,
                null
            ),
            new ServiceActionReturn(
                new PrimitiveArray()
                    .setPrimitive(
                        new PrimitiveStructure<Cat>()
                            .setProto(Cat.proto())
                    )
            )
        )
    };

    public getList(page:number = 0) {
        return this.action('getList').call(this.connector, {
            page
        });
    }
}