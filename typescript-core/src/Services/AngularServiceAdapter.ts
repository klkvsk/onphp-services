import {URLSearchParams, Http, Headers, Request, Response} from "@angular/http"
import {IServiceConnector, IServiceConnectorResponse} from "./IServiceConnector";
import {IServiceConnectorRequest} from "./IServiceConnector";
import {Dictionary} from "../Common";

import {Observable} from 'rxjs/Rx'

export class AngularServiceConnector implements IServiceConnector {
    constructor (protected http: Http) {}
    doRequest (request: IServiceConnectorRequest) : Promise<IServiceConnectorResponse> {
        let query: URLSearchParams = new URLSearchParams();
        if (request.query) {
            for (let param in request.query) {
                query.set(param, request.query[param]);
            }
        }
        let headers = new Headers();
        if (request.headers) {
            for (let header in request.headers) {
                headers.set(header, request.headers[header]);
            }
        }
        let ngRequest: Request = new Request({
            url: request.httpPath,
            search: query,
            body: request.body,
            headers: headers,
            withCredentials: true,
        });

        var res:Observable<Response> = this.http.request(ngRequest);

        return res.toPromise()
            .then((ngResponse: Response) : IServiceConnectorResponse => <IServiceConnectorResponse> {
                statusCode: ngResponse.status,
                statusText: ngResponse.statusText,
                headers:    this.importHeaders(ngResponse.headers),
                body:       ngResponse.text(),
            });
    }

    protected importHeaders(headers: Headers) : Dictionary<string> {
        var out = <Dictionary<string>>{};
        headers.keys().forEach((key) => {
            out[key] = headers.get(key);
        });
        return out;
    }
}
