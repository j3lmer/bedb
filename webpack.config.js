const Encore = require('@symfony/webpack-encore');
const glob   = require('glob');
const path   = require('path');

const VuetifyLoaderPlugin = require('vuetify-loader/lib/plugin');


// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

const entries = {};

glob.sync('./assets/vue/pages/**/app.js').forEach((path) => {
    const name = path.split('./assets/vue/pages/')[1].split('/app.js')[0];
    entries[name] = path;
});

for (let i = 0; i < Object.entries(entries).length; i += 1) {
    const [name, path] = Object.entries(entries)[i];
    Encore.addEntry(name, path);
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')


    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
     */
    .addEntry('app', './assets/app.js')

// enables the Symfony UX Stimulus bridge (used in assets/bootstrap.js)
// .enableStimulusBridge('./assets/controllers.json')

    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())


    .enableTypeScriptLoader()
    .enableVueLoader(() => {}, {
        version: 2,
        runtimeCompilerBuild: false
    })

    .addPlugin(new VuetifyLoaderPlugin())
    .enableSassLoader(options => {
        options.implementation = require('sass')
    })

// enables Sass/SCSS support
//.enableSassLoader()

// uncomment if you use React
//.enableReactPreset()

// uncomment to get integrity="..." attributes on your script & link tags
// requires WebpackEncoreBundle 1.4 or higher
//.enableIntegrityHashes(Encore.isProduction())

// uncomment if you're having problems with a jQuery plugin
//.autoProvidejQuery()





// export the final configuration
let config = Encore.getWebpackConfig();
config.resolve.alias = {
    '@': path.resolve(__dirname, './assets/vue/')
};
module.exports = config;

