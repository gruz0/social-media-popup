'use strict';

var gulp   = require('gulp');
var jshint = require('gulp-jshint');
var minify = require('gulp-minify');

function minify_js(cb) {
	gulp.src('assets/js/*.js')
		.pipe(jshint())
		.pipe(jshint.reporter('default'))
		.pipe(minify({
			ext: {
				min: '.min.js'
			},
			ignoreFiles: ['*.min.js']
		}))
		.pipe(gulp.dest('assets/js'));

	cb();
}

gulp.task('default', gulp.series([minify_js]));
