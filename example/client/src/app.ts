import {Cat} from "./out/Structures/Cat";
import {TestService} from "./out/Services/TestService";
import {AxiosServiceConnector} from "../../../typescript-core/src/Services/AxiosServiceAdapter";
import {WrongArgumentException} from "../../../typescript-core/src/Common/Exceptions";
var cat : Cat = Cat.proto().make<Cat>({name: 'purrr'});

var t = new TestService(new AxiosServiceConnector());
t.getList().then(function (list) {
    console.log(list)
});
