export class Exception extends Error {
    public message: string;
    public stack: any;

    constructor(message?: string) {
        super(message);
        this.message = message;
    }
}

export class BusinessLogicException         extends Exception {}
export class NetworkException               extends Exception {}
export class ObjectNotFoundException        extends Exception {}
export class WrongArgumentException         extends Exception {}
export class UnimplementedFeatureException  extends Exception {}