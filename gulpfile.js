let gulp = require('gulp');
let sass = require('gulp-sass');
let babel = require('gulp-babel');
let concat = require('gulp-concat');
let uglify = require('gulp-uglify-es').default;
let cleanCSS = require('gulp-clean-css');
let rigger = require('gulp-rigger');
let del = require('del');

let paths = {
    styles: {
        src: 'src/resources/scss/app.scss',
        dest: 'src/public/css/'
    },
    scripts: {
        src: 'src/resources/js/app.js',
        dest: 'src/public/js/'
    },
    icons: {
        src: 'src/resources/ico/*',
        dest: 'src/public/ico'
    },
    fonts: {
        src: 'src/resources/font/*',
        dest: 'src/public/font'
    }
};

function clean() {
    return del(['src/public']);
}

function styles() {
    return gulp.src(paths.styles.src)
        .pipe(sass())
        .pipe(cleanCSS())
        .pipe(concat('app.css'))
        .pipe(gulp.dest(paths.styles.dest));
}

function scripts() {
    return gulp.src(paths.scripts.src, {sourcemaps: true})
        .pipe(rigger())
        .pipe(babel())
        .pipe(uglify())
        .pipe(concat('app.js'))
        .pipe(gulp.dest(paths.scripts.dest));
}

function icons() {
    return gulp.src(paths.icons.src, {sourcemaps: true})
        .pipe(gulp.dest(paths.icons.dest));
}

function fonts() {
    return gulp.src(paths.fonts.src, {sourcemaps: true})
        .pipe(gulp.dest(paths.fonts.dest));
}

function watch() {
    gulp.watch(paths.scripts.src, scripts);
    gulp.watch(paths.styles.src, styles);
}

let build = gulp.series(clean, gulp.parallel(styles, scripts, icons, fonts));

exports.clean = clean;
exports.styles = styles;
exports.scripts = scripts;
exports.watch = watch;
exports.build = build;
exports.default = build;