import {Dictionary} from "./Dictionary";

interface Constructable {
    name: string,
    new (): Object
}

export class Singleton {
    protected static instances: Dictionary<Object> = {};

    public static getInstance<T extends Object>(classRef: Constructable) : T {
        var key = classRef.name;
        if (!Singleton.instances.hasOwnProperty(key)) {
            Singleton.instances[key] = <T>(new classRef());
        }
        return <T>Singleton.instances[key];
    }
}