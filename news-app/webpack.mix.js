const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.setPublicPath('/')
    .options({
        hmrOptions: {
            port: 3000
        }
    })
    .js('news-search-app/src/index.js', 'public/news-search-app/app.js')
        .react();