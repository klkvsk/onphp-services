import {Dictionary} from "../Common/Dictionary";

export interface IServiceConnectorRequest {
    httpMethod: string,
    httpPath:   string,
    query?:     Dictionary<any>,
    body?:      Dictionary<any>,
    headers?:   Dictionary<any>
}

export interface IServiceConnectorResponse {
    statusCode: number,
    statusText: string,
    headers:    Dictionary<string>,
    body:       any,
}

export interface IServiceConnector {
    doRequest(IServiceConnectorRequest) : Promise<IServiceConnectorResponse>
}
