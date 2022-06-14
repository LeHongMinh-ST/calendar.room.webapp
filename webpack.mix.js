const mix = require('laravel-mix');

// const VuetifyLoaderPlugin = require('vue-loader/lib/plugin')
// const CaseSensitivePathsPlugin = require('case-sensitive-paths-webpack-plugin')
//
// let webpackConfig = {
//     plugins: [
//         new VuetifyLoaderPlugin(),
//         new CaseSensitivePathsPlugin()
//     ]
// }
//
// mix.webpackConfig(webpackConfig)

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .vue()
    .sass('resources/sass/app.scss', 'public/css');
