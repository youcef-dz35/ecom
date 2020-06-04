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

mix.js('C:/xampp/htdocs/laraecom/resources/assets/js/app.js', 'public/js')
    .sass('C:/xampp/htdocs/laraecom/resources/assets/sass/app.scss', 'public/css')
    .sass('C:/xampp/htdocs/laraecom/resources/assets/sass/responsive.scss','public/css')
    .sourceMaps()
    .browserSync('laraecom.dz');
