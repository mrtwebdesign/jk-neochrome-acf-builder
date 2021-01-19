const __themename = 'astra';

const publicPath = '/';

const MiniCssExtractPlugin = require("mini-css-extract-plugin");

const path = require('path');

let conf_primary_theme = {

    entry: {
      //  main: ['./src/js/index.js', './src/scss/main.scss'],
        acf_repeater_builder: ['./src/scss/acf-repeater-builder.scss'],
    },

    output: {
        publicPath: publicPath,
        path: path.resolve(__dirname, `./wp-content/themes/${__themename}`),
        filename: 'inc/assets/js/[name].js'
    },

    module: {
        rules: [
            {
                test: /\.js$/,
                use: {
                    loader: "babel-loader"
                }
            },

            {
                test: /\.scss$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    {loader: "css-loader", options: {}},
                    {
                        loader: "postcss-loader",
                        options: {
                            ident: 'postcss',
                            plugins: [
                                require('autoprefixer')({
                                    'browsers': ['> 1%', 'last 2 versions']
                                }),
                                require('cssnano')()
                            ]
                        }
                    },
                    {loader: "sass-loader", options: {}}
                ]
            },
        ]
    },

    plugins: [
        new MiniCssExtractPlugin({
            filename: 'inc/assets/css/[name].css',
        }),
    ],

    watch: true,
};

const export_array = [];

export_array.push(conf_primary_theme);

module.exports = export_array;
