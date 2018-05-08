var gulp = require('gulp');

var sass = require('gulp-sass');
var imagemin = require('gulp-imagemin');

var destination = '../web';

gulp.task('css', function () {
    return gulp.src('sass/styles.scss')
        .pipe(sass())
        .pipe(gulp.dest(destination + '/admin/css/'));
});

gulp.task('vendor', function () {
    return gulp.src('sass/vendor/vendor.scss')
        .pipe(sass())
        .pipe(gulp.dest(destination + '/admin/css/vendor/'));
});

gulp.task('images', function () {
    return gulp.src('src/imgs/**/*.+(png|jpg|gif|svg)')
        .pipe(imagemin())
        .pipe(gulp.dest(destination + '/admin/imgs/'))
});

gulp.task('js', function () {
    return gulp.src('src/js/**/*')
        .pipe(gulp.dest(destination + '/admin/js/'))
});