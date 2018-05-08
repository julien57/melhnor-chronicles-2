var gulp = require('gulp');

var sass = require('gulp-sass');
var imagemin = require('gulp-imagemin');

var destination = '../web';

gulp.task('css', function () {
    return gulp.src('src/css/styles.scss')
        .pipe(sass())
        .pipe(gulp.dest(destination + '/front/css/'));
});

gulp.task('images', function () {
    return gulp.src('src/img/**/*.+(png|jpg|gif|svg)')
        .pipe(imagemin())
        .pipe(gulp.dest(destination + '/front/img/'))
});

gulp.task('favicon', function () {
    return gulp.src('src/favicon/**/*.+(png|jpg|gif|svg)')
        .pipe(imagemin())
        .pipe(gulp.dest(destination + '/front/favicon/'))
});

gulp.task('files-favicon', function () {
    return gulp.src('src/favicon/**/*.+(xml|json)')
        .pipe(gulp.dest(destination + '/front/favicon/'))
});

gulp.task('fonts', function () {
    return gulp.src('src/fonts/**/*')
        .pipe(gulp.dest(destination + '/front/fonts/'))
});

gulp.task('js', function () {
    return gulp.src('src/js/**/*')
        .pipe(gulp.dest(destination + '/front/js/'))
});