// ==================================================
// Gulp Variables
// ==================================================

var debug = true;
var notification = true;

var gulp = require('gulp'),
    notify = require('gulp-notify'),
    sass = require('gulp-sass'),
    sourcemaps = require('gulp-sourcemaps'),
    autoprefixer = require('autoprefixer'),
    postcss = require('gulp-postcss'),
    inline_base64 = require('gulp-inline-base64'),
    livereload = require('gulp-livereload'),
    compass = require('gulp-compass');


// ==================================================
// Set Paths
// ==================================================

var sass_path = 'sass';
var css_path = '../../public/css/';
var css_images_path = '../../public/css/images/';


// ==================================================
// Tasks
// ==================================================

gulp.task('default', function() {
    var g = gulp.src(sass_path + '/*.scss')
        .pipe(sourcemaps.init())
        .pipe(compass({
            css: css_path,
            sass: sass_path,
            image: css_images_path
        })).on('error', function(error) {
            console.log(error);
        })
        .pipe(inline_base64({
            baseDir: css_images_path,
            maxSize: 14 * 1024,
            debug: debug
        }))
        .pipe(postcss([autoprefixer({
            browsers: ['last 4 versions']
        })]))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest(css_path))
        .pipe(livereload());

    if (notification) {
        g.pipe(notify('Yup!'));
    }
});

gulp.task('watch', function() {
    gulp.watch(sass_path + '/*.scss', ['default']);
});

gulp.task('livereload', function() {
    notification = false;
    livereload.listen();
    gulp.start('watch');
});
