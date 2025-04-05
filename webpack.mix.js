const mix = require('laravel-mix');

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
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/editor.scss', 'public/css')
    .js('resources/js/home.js', 'public/js')
    .js('resources/js/browse.js', 'public/js')
    .js('resources/js/my-themes.js', 'public/js')
    .js('resources/js/editor.js', 'public/js')
    .js('resources/js/theme.js', 'public/js')
    .js('resources/js/account.js', 'public/js')
    .js('resources/js/charts.js', 'public/js')
    .js('resources/js/admin/advert-create.js', 'public/js/admin')
    .sourceMaps()
    .version();
