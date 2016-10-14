import {Cat} from "./out/Structures/Cat";
import {TestService} from "./out/Services/TestService";
import {AxiosServiceConnector} from "../../../typescript-core/src/Services/AxiosServiceAdapter";

AxiosServiceConnector.configure({
    baseURL: '/onphp-services/example/client/dist/data'
});

var catsService = new TestService(new AxiosServiceConnector());

catsService.getList()
    .then(function (list: Cat[]) {

        for (let cat of list) {
            cat.sayHello();
        }

    });
