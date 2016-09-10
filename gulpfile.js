
var gulp     = require('gulp');
var cleanCSS = require('gulp-clean-css');
var rename   = require('gulp-rename');

gulp.task('minify-css', function() {
    return gulp.src('css/*.css')
        .pipe(cleanCSS({debug: true}, function(details) {
            console.log(details.name + ': ' + details.stats.originalSize);
            console.log(details.name + ': ' + details.stats.minifiedSize);
        }))
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest('css'));
});

// Default Task
gulp.task('default', ['minify-css']);

