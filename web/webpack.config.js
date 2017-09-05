var path = require('path');
var webpack = require('webpack');
var ExtractTextPlugin = require("extract-text-webpack-plugin");
var HtmlWebpackPlugin = require('html-webpack-plugin');

var isProduction = process.env.NODE_ENV === 'production';
var distPath = path.join(__dirname, isProduction ? '../public/web/dist' : 'dist');
module.exports = {
    devtool: 'source-map',
    entry: {
        main: './src/index.jsx'
    },
    target: 'web',
    output: {
        path: distPath,
        filename: 'js/[name].js',
        publicPath: isProduction ? '/web/dist/' : '/'
    },
    plugins: [
        new webpack.DefinePlugin({
            'process.env.NODE_ENV': isProduction ? JSON.stringify('production') : JSON.stringify('development')
        }),
        new HtmlWebpackPlugin({
            template: __dirname + '/index.html',
            filename: isProduction ? '../index.html' : 'index.html',
            minify: {
                minifyCSS: true,
                minifyJS: true
            },
            chunks: ['common', 'main'],
            favicon: __dirname + '/favicon.ico',
            chunksSortMode: 'dependency'
        })
    ],
    resolve: {
        extensions: ['', '.js', '.jsx']
    },
    module: {
        loaders: [
            {
                test: /\.(js|jsx)$/,
                loaders: ['babel'],
                exclude: /node_modules/,
                include: __dirname
            },
            {
                test: /\.(png|jpe?g|gif)(\?.*)?$/,
                loader: 'url-loader?limit=10240&name=images/[name].[hash:8].[ext]'
            },
            {
                test: /\.(woff2?|eot|ttf|otf|svg)(\?.*)?$/,
                loader: 'url-loader?limit=10240&name=images/[name].[hash:8].[ext]'
            },
            {
                test: /\.css?$/,
                loader: ExtractTextPlugin.extract("style-loader", "css-loader?sourceMap!postcss-loader?sourceMap")
            },
            {
                test: /\.less?$/,
                loader:  ExtractTextPlugin.extract("style-loader", "css-loader?sourceMap!postcss-loader?sourceMap!less-loader?sourceMap"),
                include: __dirname
            }
        ]
    }
};
