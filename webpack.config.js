/* global __dirname */

var path = require('path');

var webpack = require('webpack');
var CopyWebpackPlugin = require('copy-webpack-plugin');

var dir_js = path.resolve(__dirname, 'js');
var dir_html = path.resolve(__dirname, 'html');
var dir_build = path.resolve(__dirname, 'build');

module.exports = {
  entry: path.resolve(dir_js, 'index.js'),
  output: {
    path: dir_build,
    filename: 'js/bundle.js'
  },
  devServer: {
    contentBase: dir_build,
    port: 8081
  },
  module: {
    loaders: [
      {
        loader: 'react-hot',
        test: dir_js,
      },
      {
        loader: 'babel-loader',
        test: dir_js,
        query: {
          presets: ['es2015', 'react', 'stage-1'],
        },
      }
    ]
  },
  plugins: [
    // Simply copies the files over
    new CopyWebpackPlugin([
      { from: dir_html } // to: output.path
    ]),
    // Avoid publishing files when compilation fails
    new webpack.NoErrorsPlugin()
  ],
  stats: {
    // Nice colored output
    colors: true
  },
  // Create Sourcemaps for the bundle
  devtool: 'source-map',
};