/**
 * Created by klkvsk on 15.12.2015.
 */
module ngphp {
    export class Form {
        protected primitives: Primitive<any>[];

        public add(primitive: Primitive<any>) {
            this.primitives.push(primitive);
        }

        public clean() : void {
            this.primitives.forEach( p => p.clean() );
        }

        public importData(data: Dictionary<any>) {
            this.clean();
            this.primitives.forEach( p => {
                if (data.hasOwnProperty(p.getName())) {
                    p.importValue(data[p.getName()])
                }
            })
        }
    }
}