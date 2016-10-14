var webpack = require('webpack');
var fs = require('fs');

module.exports = {
    entry: './src/app.ts',

    output: {
        filename: "./dist/app.js"
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

};