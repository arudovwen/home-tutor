const mix = require("laravel-mix");

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




mix.js("resources/js/app.js", "public/js")
    .sass("resources/sass/app.scss", "public/css/app.css")
    .styles(['public/css/style.css','public/css/hamburger.css'], "public/css/app.css")
    .extract()
    .webpackConfig({
        output:{
            chunkFilename:'js/chunks/[name].js'
        },
        resolve: {
            alias: {
                "@": path.resolve("resources/") // just to use relative path properly
            }
        }
    })
   .options({ processCssUrls: false })
   ;
 
if (mix.inProduction()) {
   mix.version();
}
