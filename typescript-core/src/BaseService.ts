/// <reference path="./tsd.d.ts" />
import ng = require('angular');
import Dictionary from 'common';

interface IServiceCallParameters {
    headers?: Dictionary<string>,
    route?:   Dictionary<string|number>,
    query?:   Dictionary<any>,
    body?:    Dictionary<any>,
}

/**
 * wrapper for action return type meta info
 */
class ServiceActionReturn {
    public type: Function;
    public isArray: boolean;

    constructor(type: Function, isArray:boolean = false) {
        this.type = type;
        this.isArray = isArray;
    }

    getDefinition(): Function {
        if (this.isArray) {
            return this.type;
        } else {
            return this.type;
        }
    }
}

enum ServiceParamPropertyType {
    STRING,
    BOOLEAN,
    FLOAT,
    INTEGER,
    DATE,
    DATETIME,
    ENUM,
    STRUCTURE,
}

interface SingletonConstructor<TSingleton> extends Function {
    instance?: TSingleton,
    new(): TSingleton,
}
function singletonClass(target: SingletonConstructor<any>) {
    return function() {
        if (!target.instance) {
            target.instance = new target();
        }
        return target.instance;
    };
}

@singletonClass
class Singleton {
    protected static instances: Dictionary<Object>;
    public static get(ctor: Function) {
        var ctorName, fn = 'function';
        ctorName = ctor.toString();
        if (ctorName.substr(0, fn.length) == fn) {
            ctorName = ctorName.substr(0, ctorName.indexOf('('));
            ctorName = ctorName.substr(fn.length);
            ctorName = ctorName.replace(/\s+/g, '');
        } else {
            ctorName = null;
        }

        if (!ctorName) {
            throw new Error('can not get constructor name from function: ' + ctor.toString());
        }

        if (!Singleton.instances.hasOwnProperty(ctorName)) {
            Singleton.instances[ctorName] = new ctor();
        }

        return Singleton.instances[ctorName];
    }
}

/**
 * base class for client<->service communication services
 */
class BaseService {
    protected $http: ng.IHttpService;

    static $inject: string[] = [ '$http' ];

    constructor(httpServiceInstance: ng.IHttpService) {
        this.$http = httpServiceInstance;
    }

    action(httpMethod: string,
           httpPath: string,
           parametersData: IServiceCallParameters,
           returnType: ServiceActionReturn
    ) {
        // put route parameters into :placeholders
        httpPath = httpPath.replace(
            /:([a-z][a-z0-9_]+)/ig,
            function (_: string, placeholderName: string) : string {
                if (parametersData.route && parametersData.route.hasOwnProperty(placeholderName)) {
                    return parametersData.route[placeholderName].toString();
                }
                return '';
            }
        );

        var request:ng.IRequestConfig = <ng.IRequestConfig>{};
        request.method  = httpMethod;
        request.url     = httpPath;
        request.params  = parametersData.query;
        request.data    = parametersData.body;
        request.headers = parametersData.headers;

        var promise:ng.IHttpPromise<any> = this.$http(request);
        promise = promise.success(
            function (data: any, status: number, headers: ng.IHttpHeadersGetter, config: ng.IRequestConfig) {
                console.log(arguments);
            }
        );

        promise = promise.error(
            function (error: any, status: number, headers: ng.IHttpHeadersGetter, config: ng.IRequestConfig) {
                console.log(arguments);
            }
        );
    }

    param(name:string, value:string, type:Function, isArray:boolean, isRequired:boolean) {
        return new ServiceActionParam(name, value, type, isArray, isRequired);
    }

    returns(type: Function, isArray: boolean) {
        return new ServiceActionReturn(type, isArray);
    }

}

export = BaseService;