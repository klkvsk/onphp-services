import {Dictionary} from "./Dictionary";

export class Singleton {
    protected static instances: Dictionary<Object> = {};

    public static getInstance<T extends Object>(classRef: any) : T {
        var key = classRef.toString();
        if (!Singleton.instances.hasOwnProperty(key)) {
            Singleton.instances[key] = <T>(new classRef());
        }
        return <T>Singleton.instances[key];
    }
}