'use strict';

let gulp   = require('gulp');
let jshint = require('gulp-jshint');
let minify = require('gulp-minify');

function minify_js(cb) {
	gulp
		.src([
			'assets/js/*.js',
			'!assets/js/*-min.js'
		])
		.pipe(jshint())
		.pipe(jshint.reporter('default'))
		.pipe(minify())
		.pipe(gulp.dest('assets/js/'));

	cb();
}

gulp.task('default', gulp.series([minify_js]));
