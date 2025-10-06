const path = require("path");

module.exports = [
    {
        mode: "production",
        entry: {
            main: "./src/js/theme_daux/index.js",
        },
        output: {
            path: path.resolve(__dirname, "themes/daux/js"),
            chunkFilename: "[name].mjs",
            chunkLoading: "import",
            chunkFormat: "module",
        },
        experiments: {
            outputModule: true,
        },
    },
    {
        mode: "production",
        devtool: "source-map",
        entry: {
            main: "./src/js/search/index.js",
        },
        output: {
            filename: "search.min.js",
            path: path.resolve(__dirname, "daux_libraries"),
            chunkFilename: "[name].js",
            //chunkLoading: "import",
            //chunkFormat: "module",
        },
        experiments: {
            //outputModule: true,
        },
        module: {
            rules: [
                {
                    test: /\.m?js$/,
                    exclude: /(node_modules|bower_components)/,
                    use: {
                        loader: "swc-loader",
                        options: {
                            jsc: {
                                parser: {
                                    jsx: true,
                                },
                            },
                        },
                    },
                },
            ],
        },
    },
];
