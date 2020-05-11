const mix = require('laravel-mix');
const path = require('path');
const webpack = require('webpack');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');

mix.setPublicPath(path.normalize('public/laraone'));

mix.options({
    terser: {
      extractComments: false,
    }
});

mix.autoload({
    'lodash': ['_', 'window._']
});

mix.webpackConfig({
    resolve: {
        alias: {
            '~': path.resolve(__dirname, './resources/admin/js'),
            styles: path.resolve(__dirname, './resources/admin/sass')
        }
    },
    plugins: [
        new webpack.ProgressPlugin(),
        new CleanWebpackPlugin(),
        new webpack.NormalModuleReplacementPlugin(/element-ui[\/\\]lib[\/\\]locale[\/\\]lang[\/\\]zh-CN/, 'element-ui/lib/locale/lang/en'),
        new webpack.NormalModuleReplacementPlugin(/element-ui[\/\\]src[\/\\]locale[\/\\]lang[\/\\]zh-CN/, 'element-ui/src/locale/lang/en'),
        new webpack.NormalModuleReplacementPlugin(/element-ui[\/\\]packages[\/\\]scrollbar/, 'element-ui/lib/scrollbar')
    ],
    module: {
        rules: [{
            test: /\.worker\.js$/,
            use: {
                loader: 'worker-loader',
                options: {
                name: '[name].js',
                publicPath: '/laraone/'
                }
            }
        }]
    }
});


mix.js('resources/admin/js/app.js', 'js/admin.js')
    .js('resources/auth/js/app.js', 'js/auth.js')
    .js('resources/installer/js/app.js', 'js/installer.js')
    .sass('resources/admin/sass/app.scss', 'css/admin.css')
    .sass('resources/auth/sass/app.scss', 'css/auth.css')
    .sass('resources/installer/sass/app.scss', 'css/installer.css')
    .setResourceRoot('/laraone/');

mix.version();

if (mix.inProduction()) {
    mix.disableNotifications();
}
