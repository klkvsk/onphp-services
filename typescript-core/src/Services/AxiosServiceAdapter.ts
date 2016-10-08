import {IServiceConnector, IServiceConnectorRequest, IServiceConnectorResponse} from "./IServiceConnector";
import axios, { AxiosRequestConfig, AxiosInstance, AxiosResponse } from 'axios';

export class AxiosServiceConnector implements IServiceConnector {
    protected static axios: AxiosInstance;
    protected static config: AxiosRequestConfig;

    static configure(config: AxiosRequestConfig) {
        this.config = config;
        this.axios = null;
    }

    doRequest(request: IServiceConnectorRequest) : Promise<IServiceConnectorResponse> {
        if (!AxiosServiceConnector.axios) {
            AxiosServiceConnector.axios = axios.create(AxiosServiceConnector.config);
        }

        var axRequest = <AxiosRequestConfig>{
            url:        request.httpPath,
            headers:    request.headers,
            method:     request.httpMethod,
            params:     request.query,
            data:       request.body,
        };

        return AxiosServiceConnector.axios.request(axRequest)
            .then(
                (axResponse: AxiosResponse) : IServiceConnectorResponse => <IServiceConnectorResponse>{
                    statusCode: axResponse.status,
                    statusText: axResponse.statusText,
                    headers:    axResponse.headers,
                    body:       axResponse.data
                }
            );
    }
}