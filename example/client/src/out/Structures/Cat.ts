import {AutoCat} from "../Auto/Structures/AutoCat";

export class Cat extends AutoCat {
    // your code goes here...

    public sayHello() {
        console.log('Hello, my name is ' + this.getName());
    }
}
