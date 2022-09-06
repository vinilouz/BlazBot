const { src, dest, watch, series } = require("gulp");
const sass = require('gulp-sass')(require('sass'));
const postcss = require("gulp-postcss");
const cssnano = require("cssnano");
const terser = require("gulp-terser")
const cleanCss = require('gulp-clean-css');
const combineMedia = require('gulp-combine-media');

const paths = {
  srcSASS: "src/scss",
  srcJS: "src/js",
  distCSS: "site/themes/blazerobot/public/css",
  distJS: "site/themes/blazerobot/public/js",
};

// Sass Task
function scssTask() {
  return src(`${paths["srcSASS"]}/style.scss`, { sourcemaps: true })
    .pipe(sass())
    // .pipe(cleanCss({ level: { 2: { restructureRules: true } } }))
    // .pipe(combineMedia())
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
