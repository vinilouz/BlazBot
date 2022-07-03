const { src, dest, watch, series } = require("gulp");
const sass = require("gulp-sass");
const postcss = require("gulp-postcss");
const cssnano = require("cssnano");
const terser = require("gulp-terser")
var gcmq = require('gulp-group-css-media-queries')

const paths = {
  srcSASS: "src/scss",
  srcJS: "src/js",
  distCSS: "site/themes/exit/public/css",
  distJS: "site/themes/exit/public/js",
};

// Sass Task
function scssTask() {
  return src(`${paths["srcSASS"]}/style.scss`, { sourcemaps: true })
    .pipe(sass())
    .pipe(gcmq())
    .pipe(postcss([cssnano()]))
    .pipe(dest(`${paths["distCSS"]}`, { sourcemaps: "." }));
}

// JavaScript Task
function jsTask() {
  return src(`${paths["srcJS"]}/script.js`, { sourcemaps: true })
    .pipe(terser())
    .pipe(dest(`${paths["distJS"]}`, { sourcemaps: "." }));
}

// Watch Task
function watchTask() {
  watch("*.html");
  watch(
    [`${paths["srcSASS"]}/**/*.scss`, `${paths["srcJS"]}/**/*.js`],
    series(scssTask, jsTask)
  );
}

// Default Gulp task
exports.default = series(scssTask, jsTask, watchTask);
