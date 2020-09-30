const mix = require('laravel-mix');

require('laravel-mix-tailwind');

mix.setPublicPath('public')
    .js('resources/js/charts.js', 'public')
    .sass('resources/sass/base.scss', 'public')
    .tailwind()
    .version()
