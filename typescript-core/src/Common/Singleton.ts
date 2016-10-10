import {Dictionary} from "./Dictionary";

export class Singleton {
    protected static instances: Dictionary<Object> = {};

    public static getInstance<T>(constructor: ObjectConstructor) : T {
        var key = constructor.name;
        if (!Singleton.instances.hasOwnProperty(key)) {
            Singleton.instances[key] = <T>(new constructor());
        }
        return Singleton.instances[key];
    }
}