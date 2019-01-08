import { src, dest, watch, series, parallel } from 'gulp';
import yargs from 'yargs';
import jshint from 'gulp-jshint';
import stylish from 'jshint-stylish';
import sass from 'gulp-sass';
import cleanCss from 'gulp-clean-css';
import postcss from 'gulp-postcss';
import sourcemaps from 'gulp-sourcemaps';
import autoprefixer from 'autoprefixer';
import gulpif from 'gulp-if';
import del from 'del';
import webpack from 'webpack-stream';
import named from 'vinyl-named';
import browserSync from "browser-sync";

const PRODUCTION = yargs.argv.prod;

// webpack start
const server = browserSync.create();
export const serve = done => {
	server.init({
		proxy: "http://wordpress.local:8000/" // put your local website link here
	});
	done();
};
export const reload = done => {
	server.reload();
	done();
};
// webpack end

export const lint = () => {
	return src(['assets/js/*.js'])
		.pipe(jshint())
		.pipe(jshint.reporter(stylish))
		.pipe(jshint.reporter('fail'));
}

export const clean = () => del(['dist']);

export const copyFontAwesome = () => {
	return src([
		'node_modules/font-awesome/{css,fonts}/*',
		'!node_modules/font-awesome/css/font-awesome.css',
		'!node_modules/font-awesome/css/font-awesome.css.map',
	])
	.pipe(dest('dist/font-awesome'));
}

export const styles = () => {
	return src(['assets/scss/bundle.scss','assets/scss/admin.scss'])
		.pipe(gulpif(!PRODUCTION, sourcemaps.init()))
		.pipe(sass().on('error', sass.logError))
		.pipe(gulpif(PRODUCTION, postcss([ autoprefixer  ])))
		.pipe(gulpif(PRODUCTION, cleanCss({compatibility:'ie8'})))
		.pipe(gulpif(!PRODUCTION, sourcemaps.write()))
		.pipe(dest('dist/css'))
		.pipe(server.stream());
}

export const scripts = () => {
	return src(['assets/js/bundle.js','assets/js/admin.js','assets/js/cookies.js'])
		.pipe(named())
		.pipe(webpack({
			module: {
				rules: [
					{
						test: /\.js$/,
						use: {
							loader: 'babel-loader',
							options: {
								presets: ['@babel/preset-env']
							}
						}
					}
				]
			},
			mode: PRODUCTION ? 'production' : 'development',
			devtool: !PRODUCTION ? 'inline-source-map' : false,
			output: {
				filename: '[name].js'
			},
		}))
		.pipe(dest('dist/js'));
}

export const copy = () => {
	return src([
		'assets/**/*',
		'!assets/{js,scss}',
		'!assets/{js,scss}/**/*',
	])
	.pipe(dest('dist'));
}

export const watchForChanges = () => {
	watch('assets/js/**/*.js', scripts);
	watch(['assets/**/*','!assets/{js,scss}','!assets/{js,scss}/**/*'], copy);
	watch("**/*.php", reload);
}

export const dev = series(clean, copyFontAwesome, parallel(styles, scripts, copy), serve, watchForChanges)
export const build = series(clean, copyFontAwesome, parallel(styles, scripts, copy))
export default dev;
