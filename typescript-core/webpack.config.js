var webpack = require('webpack');
var fs = require('fs');

module.exports = {
    entry: './src/index.ts',

    output: {
        filename: "./dist/onphp.bundle.js",
        library: "onphp",
        libraryTarget: "var"
    },

    //devtool: "source-map",

    resolve: {
        extensions: ["", ".webpack.js", ".ts", ".js" ]
    },

    module: {
        loaders: [
            { test: /\.ts$/, loader: "ts-loader", useCache: true }
        ]
    },

    plugins: [
    //    new webpack.optimize.UglifyJsPlugin()
    ],

    externals: {
        "@angular/http": "ng.http",
    }
};