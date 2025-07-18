/**
 * Include requirements.
 */
var gulp = require('gulp');
var debug = require('gulp-debug');
var sass = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');
var watch = require('gulp-watch');
/**
 * Resource paths.
 */
var scssSrc = './scss/**/*.scss';
/**
 * Default task is to watch for changes.
 */
gulp.task('default', ['watch']);
/**
 * Build everything.
 */
gulp.task('build', function () {
    gulp.start('build-scss');
});
/**
 * Compile sass files.
 */
gulp.task('build-scss', function () {
    return gulp.src(scssSrc)
        .pipe(sass())
        .pipe(autoprefixer({
            browsers: ['last 2 versions'],
            cascade: false
        }))
        .pipe(gulp.dest('./assets/css'));
});
/**
 * Watch for stylesheet changes.
 */
gulp.task('watch', function () {
    return watch([scssSrc], function () {
        gulp.start('build');
    });
});
