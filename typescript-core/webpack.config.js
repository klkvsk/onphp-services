var webpack = require('webpack');
var fs = require('fs');

function glob_ts(path, rx) {
    return Array.prototype.concat.apply([],
        fs.readdirSync(path).map( (p) => {
            let file = path + '/' + p;
            return fs.statSync(file).isDirectory()
                ? glob_ts(file)
                : ( file.endsWith('.ts') && !file.endsWith('.d.ts') ? file : null )
        })
    ).filter(i => i != null);
}

module.exports = {
    //entry: glob_ts('./src'),
    entry: './src/index.ts',


    output: {
        filename: "./dist/onphp.bundle.js",
        library: "onphp",
        libraryTarget: "var"
    },

    //devtool: "source-map",

    resolve: {
        extensions: ["", ".webpack.js", ".ts" ]
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