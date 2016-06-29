var WebpackDevServer = require('webpack-dev-server');
var webpack = require('webpack');
var config = require('./webpack.config.js');


var hmr = new webpack.HotModuleReplacementPlugin();
if ('undefined' !== typeof config.plugins) {
  config.plugins.push(hmr);
} else {
  config.plugins = [hmr];
}

var compiler = webpack(config);
var server = new WebpackDevServer(compiler, {
  contentBase: 'build',
  hot: true,
  filename: 'js/bundle.js',
  publicPath: '/',
  stats: {
    colors: true,
  },
});
server.listen(8081, 'localhost', () => {
  console.log('Server started, webpack is compiling...');
});