'use strict';
let gulp          = require('gulp');
let plugins       = require('gulp-load-plugins');
let yargs         = require('yargs');
let rimraf        = require('rimraf');
let yaml          = require('js-yaml');
let fs            = require('fs');
let named         = require('vinyl-named');
let through2      = require('through2');
let sass          = require('gulp-sass')(require('node-sass'));

// Load all Gulp plugins into one variable
const $ = plugins();

let PRODUCTION = !!(yargs.argv.production); // Check for --production flag
let VERSION_BUMP = yargs.argv.release;      // Check for --release (x.x.x semver version number)

// Load settings from settings.yml
const {COMPATIBILITY, PORT, UNCSS_OPTIONS, PATHS, LOCAL_PATH} = loadConfig();

let sassConfig = {
	mode: (PRODUCTION ? true : false)
};

/**
 * Load in additional config files
 */
function loadConfig() {
	let ymlFile = fs.readFileSync('config.yml', 'utf8');
	return yaml.load(ymlFile);
}

/**
 * Set production mode during the build process
 *
 * @param done
 */
function setProductionMode(done) {
	PRODUCTION            = false;
	sassConfig.production = true;
	done();
}

/**
 * Build the "dist" folder by running all of the below tasks
 * Sass must be run later so UnCSS can search for used classes in the others assets.
 * */
gulp.task(
	'build:release',
	gulp.series(
		setProductionMode,
		clean,
		javascript,
		buildSass,
		bumpPluginFile,
		bumpPackageJson,
		bumpReadmeStableTag,
		bumpComposerJson,
		copy
	)
);

// Build the site, run the server, and watch for file changes
gulp.task(
	'default',
	gulp.series(
		clean,
		javascript,
		buildSass,
		copy,
		gulp.parallel(watch)
	)
);

/**
 * This happens every time a build starts
 *
 * @since 1.4.0
 *
 * @param done
 */
function clean(done) {
	rimraf('css', done);
	rimraf('js', done);

	done();
}

/**
 * Bump the version number within the define method of our plugin file
 * PHP Constant: example `define( 'MESH_VERSION', '1.0.0' );`
 *
 * Bump the version number within our meta data of the plugin file
 *
 * Update the release date with today's date
 *
 * @since 1.4.0
 *
 * @return {*}
 */
function bumpPluginFile(done) {

	let constant = 'MESH_VERSION';
	let define_bump_obj = {
		key: constant,
		regex: new RegExp('([<|\'|"]?(' + constant + ')[>|\'|"]?[ ]*[:=,]?[ ]*[\'|"]?[a-z]?)(\\d+.\\d+.\\d+)(-[0-9A-Za-z.-]+)?(\\+[0-9A-Za-z\\.-]+)?([\'|"|<]?)', 'ig')
	};

	let bump_obj = {
		key: 'Version',
	};

	if (VERSION_BUMP) {
		bump_obj.version        = VERSION_BUMP;
		define_bump_obj.version = VERSION_BUMP;
	}

	let today = getReleaseDate();

	return gulp.src('./mesh.php')
		.pipe($.bump(bump_obj))
		.pipe($.bump(define_bump_obj))
		.pipe($.replace(/(((0)[0-9])|((1)[0-2]))(\/)([0-2][0-9]|(3)[0-1])(\/)\d{4}/ig, today))
		.pipe(through2.obj(function (file, enc, cb) {
			let date        = new Date();
			file.stat.atime = date;
			file.stat.mtime = date;
			cb(null, file);
		}))
		.pipe(gulp.dest('./'));
}

/**
 * Update the what's new template with the date of the release instead of having to manually update it every release
 *
 * @since 1.4.0
 *
 * @return {*}
 */
function getReleaseDate() {
	let today = new Date();
	let dd    = String(today.getDate()).padStart(2, '0');
	let mm    = String(today.getMonth() + 1).padStart(2, '0');
	let yyyy  = today.getFullYear();

	return mm + '/' + dd + '/' + yyyy;
}

/**
 * Bump the composer.json
 *
 * @since 1.4.0
 *
 * @return {*}
 */
function bumpComposerJson() {

	let bump_obj = {
		key: 'version'
	};

	if (VERSION_BUMP) {
		bump_obj.version = VERSION_BUMP;
	}

	return gulp.src('./composer.json')
		.pipe($.bump(bump_obj))
		.pipe(through2.obj(function (file, enc, cb) {
			let date = new Date();
			file.stat.atime = date;
			file.stat.mtime = date;
			cb(null, file);
		}))
		.pipe(gulp.dest('.'));
}

/**
 * bump readme file stable tag to our latest version
 *
 * @since 1.4.0
 *
 * @return {*}
 */
function bumpReadmeStableTag() {

	let bump_obj = {key: "Stable tag"};

	if (VERSION_BUMP) {
		bump_obj.version = VERSION_BUMP;
	}

	return gulp.src('./readme.txt')
		.pipe($.bump(bump_obj))
		.pipe(through2.obj(function (file, enc, cb) {
			let date = new Date();
			file.stat.atime = date;
			file.stat.mtime = date;
			cb(null, file);
		}))
		.pipe(gulp.dest('./'));
}

/**
 * Bump the package.json
 *
 * @since 1.4.0
 *
 * @return {*}
 */
function bumpPackageJson() {

	let bump_obj = {
		key: 'version'
	};

	if (VERSION_BUMP) {
		bump_obj.version = VERSION_BUMP;
	}

	return gulp.src('./package.json')
		.pipe($.bump(bump_obj))
		.pipe(through2.obj(function (file, enc, cb) {
			let date = new Date();
			file.stat.atime = date;
			file.stat.mtime = date;
			cb(null, file);
		}))
		.pipe(gulp.dest('.'));
}

/**
 * Copy files out of the assets folder
 * This task skips over the "img", "js", and "scss" folders, which are parsed separately
 *
 * @since 1.4.0
 *
 * @return {*}
 */
function copy() {
	return gulp.src(PATHS.assets)
		.pipe(gulp.dest('css/fonts'));
}

/**
 * In production, the CSS is compressed
 *
 * @since 1.4.0
 *
 * @return {*}
 */
function buildSass() {
	return gulp.src('assets/scss/*.scss')
		.pipe($.sourcemaps.init())
		.pipe(sass({
			includePaths: PATHS.sass
		}).on('error', sass.logError))
		.pipe( gulp.dest('css') );
}

/**
 * In production, the file is minified
 *
 * @since 1.4.0
 *
 * @return {*}
 */
function javascript() {
	return gulp.src(PATHS.entries)
		.pipe(named())
		.pipe($.sourcemaps.init())
		.pipe($.if(PRODUCTION, $.uglify()
			.on('error', e => {
				console.log(e);
			})
		))
		.pipe($.if(!PRODUCTION, $.sourcemaps.write()))
		.pipe(gulp.dest('js'));
}

/**
 * Watch for changes to static assets
 * Sass
 * JavaScript
 * readme.txt
 *
 * @since 1.4.0
 */
function watch() {
	gulp.watch('readme.txt', readme);
	gulp.watch('assets/scss/**/*.scss').on('all', buildSass);
	gulp.watch('assets/js/**/*.js').on('all', gulp.series(javascript));
	gulp.watch(PATHS.assets, copy);
}
