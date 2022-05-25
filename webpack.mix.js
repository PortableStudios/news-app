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

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        //
    ]);



Mix.listen('configReady', (webpackConfig) => {
    if (Mix.isUsing('hmr')) {
        // Remove leading '/' from entry keys
        webpackConfig.entry = Object.keys(webpackConfig.entry).reduce((entries, entry) => {
            entries[entry.replace(/^\//, '')] = webpackConfig.entry[entry];
            return entries;
        }, {});

        // Remove leading '/' from ExtractTextPlugin instances
        webpackConfig.plugins.forEach((plugin) => {
            if (plugin.constructor.name === 'ExtractTextPlugin') {
                plugin.filename = plugin.filename.replace(/^\//, '');
            }
        });
    }
});
