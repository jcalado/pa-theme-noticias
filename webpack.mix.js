const mix = require('laravel-mix');
const WebpackRequireFrom = require('webpack-require-from');

mix.webpackConfig({
    output: {
        publicPath: '/wp-content/themes/pa-theme-noticias/assets/js/', // but this is a browser path for compiled files
    },
    plugins: [
        new WebpackRequireFrom({
          // see configuration options below
          replaceSrcMethodName: 'replaceSrc',
        }),
    ],
});

mix
    .sass('assets/scss/style.scss', '../../style.css');

mix
    .sass('assets/scss/admin-metabox.scss', '../css/admin-metabox.css');

mix
    .setPublicPath('./assets/js')
    .browserSync('localhost');

mix
    .js('assets/scripts/script.js', './assets/js')
    .webpackConfig({
        module: {
            rules: [
                {
                    test: /\.js?$/,
                    exclude: /node_modules(?!\/slim-js)/,
                    use: [
                        {
                            loader: 'babel-loader',
                            options: Config.babel(),
                        },
                    ],
                },
            ],
        },
    });

mix.options({
    processCssUrls: false,
});

mix
    .sourceMaps(false, 'source-map')
    .version();
