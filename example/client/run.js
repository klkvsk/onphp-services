require('systemjs');

System.register("axios", [], function(exports) {
    "use strict";
    return {
        setters:[],
        execute: function() {
            let e = { default: require('axios') };
            console.log(e);
            exports('axios', e);
        }
    }
});

require('./all');

console.log('start');
System.import('example/client/src/app').then(function () {
    console.log('done')
}).catch(function (err) {
    console.log('error');
    console.log(err.toString());
});