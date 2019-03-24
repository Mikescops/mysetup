var gulp = require('gulp'),
    minify = require('gulp-minify'),
    sass = require('gulp-sass'),
    include = require('gulp-include'),
    rename = require('gulp-rename');

sass.compiler = require('node-sass');

/**
 * $ gulp minify-js-libs
 * description: compress the libs js files and output them in the dist folder
 */
gulp.task('minify-js-libs', async function () {
    gulp.src('src/Assets/js/libs/index.js')
        .pipe(include())
        .pipe(rename('libs.js'))
        .pipe(minify({
            noSource: true,
            ext: {
                src: '-debug.js',
                min: '.min.js'
            },
            ignoreFiles: ['.min.js']
        }))
        .pipe(gulp.dest('webroot/dist'));
});

/**
 * $ gulp minify-js-app
 * description: compress app js files and output them in the dist folder
 */
gulp.task('minify-js-app', async function () {
    gulp.src('src/Assets/js/app/index.js')
        .pipe(include())
        .pipe(rename('app.js'))
        .pipe(minify({
            noSource: true,
            ext: {
                src: '-debug.js',
                min: '.min.js'
            },
            ignoreFiles: ['.min.js']
        }))
        .pipe(gulp.dest('webroot/dist'));
});


/**
 * $ gulp minify-scss
 * description: compress all scss files and output them in the dist folder
 */
gulp.task('minify-scss', () => {
    return gulp.src('src/Assets/scss/main.scss')
        .pipe(sass({ outputStyle: 'compressed' }).on('error', sass.logError))
        .pipe(gulp.dest('webroot/dist'));
});

/**
 * $ gulp build
 * description: prepare all assets
 */
gulp.task('build', gulp.parallel('minify-scss', 'minify-js-app', 'minify-js-libs'));

/**
 * $ gulp default
 * description: start the dev assets watching
 */
gulp.task('default', gulp.series('build', watch = function () {
    gulp.watch(['./src/Assets/scss/**/*.scss'], gulp.series('minify-scss'));
    gulp.watch(['./src/Assets/js/app/**/*.js'], gulp.series('minify-js-app'));
    gulp.watch(['./src/Assets/js/libs/**/*.js'], gulp.series('minify-js-libs'));
}));
