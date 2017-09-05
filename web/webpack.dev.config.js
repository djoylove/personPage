var path = require('path');
var webpack = require('webpack');
var base = require('./webpack.config');
var ExtractTextPlugin = require("extract-text-webpack-plugin");

base.devtool = 'cheap-module-eval-source-map';
// add hot-reload related code to entry chunks
Object.keys(base.entry).forEach(function (name) {
    base.entry[name] = ['babel-polyfill', 'react-hot-loader/patch', 'webpack-hot-middleware/client'].concat(base.entry[name]);
});
// base.entry.unshift('babel-polyfill', 'react-hot-loader/patch', 'webpack-hot-middleware/client');
base.plugins.push(
    new webpack.HotModuleReplacementPlugin(),
    new ExtractTextPlugin("css/[name].css", {
        allChunks: true
    }),
    new webpack.optimize.CommonsChunkPlugin({
        name: 'common',
        filename: 'js/common.js',
        minChunks: function (module, count) {
            return module.resource && module.resource.indexOf(path.resolve(__dirname, 'src')) === -1;
        }
    }),
    new webpack.optimize.DedupePlugin(),
    // 允许错误不打断程序
    new webpack.NoErrorsPlugin()
);

module.exports = base;
