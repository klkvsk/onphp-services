import {Cat} from "../../../Structures/Cat";
import {PrimitiveString} from "../../../../../../../typescript-core/src/Primitives/PrimitiveString";
import {StructureProto} from "../../../../../../../typescript-core/src/Structures/StructureProto";
import {Form} from "../../../../../../../typescript-core/src/Forms/Form";
/**
 * Created by klkvsk on 08.10.2016.
 */

export abstract class AutoProtoCat extends StructureProto {

    public getConstructor() {
        return Cat;
    }

    public getForm() {
        return new Form()
            .add('name', new PrimitiveString()
                .setRequired(true)
            );
    }

}
