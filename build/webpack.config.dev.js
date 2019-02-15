'use strict'

const { VueLoaderPlugin } = require('vue-loader');
const HtmlWebPackPlugin = require("html-webpack-plugin");
const CopyWebpackPlugin = require('copy-webpack-plugin');
const Axio = require('axios');
const path = require('path');

module.exports = {
  mode: 'development',
  entry: [
    './src/main.js'
  ],
  module: {
    rules: [
      {
        test: /\.html$/,
        use: [{ loader: "html-loader", options: { minimize: true } }]
      },
      {
        test: /\.vue$/,
        use: 'vue-loader'
      },
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: 'babel-loader'
      },
      {
        test: /\.js$/,
        enforce: "pre",
        use: ["source-map-loader"],
      },
      {
        test:/\.(s*)css$/,

        // Webpack will pass the file from right to left instead of left to right.
        // Compiles Sass to CSS, using Node Sass by default (sass-loader),
        // Get CSS from a Vue file or any JS files (css-loader) and
        // inject it into my HTML as a style tag (vue-style-loader).
        use: ['vue-style-loader', 'css-loader', 'sass-loader']
      }
    ]
  },
  //to track down errors and warnings which maps your compiled code back to your,
  //original source code.source map provides a way of mapping code within a compressed,
  //file back to it’s original position in a source file
  devtool: 'inline-source-map',
  plugins: [
    new VueLoaderPlugin(),
    new HtmlWebPackPlugin({
      template: "./src/index.html",
      filename: "index.html",
      inject: true,
      hash: true
    }),
    new CopyWebpackPlugin([
      { from: resolve('src/index.html'), to: resolve('dist/index.html'), toType: 'file' },
      { from: resolve('src/images'), to: resolve('dist/images'), toType: 'dir' },
      { from: resolve('src/php'), to: resolve('dist/php'), toType: 'dir' },
      { from: resolve('static/theme'), to: resolve('dist/theme'), toType: 'dir' },
      { from: resolve('static/data'), to: resolve('dist/data'), toType: 'dir' },
      { from: resolve('vendor'), to: resolve('dist/vendor'), toType: 'dir' },
    ])
  ],
  resolve: {
    extensions: ['.js', '.vue'],
    alias: {
      // use the compiler-included build
      'vue': path.join(__dirname, '../node_modules/vue/dist/vue.common.js')
    }
  }
}

// Return actual path of directory
function resolve (dir) {
  return path.join(__dirname, '..', dir);
}
