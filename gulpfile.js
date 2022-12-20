var gulp = require("gulp"),
	clean = require("gulp-clean");


/*
 * series for doing multiple task in order 1->2->3
 * src is for getting files from the computer
 * dest is for transfer file to destination 
*/
const { series, src, dest } = require('gulp');
//install plugins


const zip = require('gulp-zip');

const babel		= require('gulp-babel');

const package = require('./package.json'); 


//clean existing build zip file
function cleanZip(cb) {
	return gulp.src("./*.zip", {
		read: false,
		allowEmpty: true
	}).pipe(clean());
	cb();
}


//clean file & folders from build

function cleanBuild(cb) {
	return gulp.src("./build", {
		read: false,
		allowEmpty: true
	}).pipe(clean());
	cb();
};


// bundle all files export to destination directory
function bundleFiles(cb){
	return src([
		'./**/*.*',
		'!./build/**',
		'!./assets/**/*.map',
		'!./assets/react/**',
		'!./assets/scss/**',
		'!./assets/.sass-cache',
		'!./node_modules/**',
		'!./v2-library/**',
		'!./test/**',
		'!./.docz/**',
		'!./**/*.zip',
		'!.github',
		'!./readme.md',
		'!.DS_Store',
		'!./**/.DS_Store',
		'!./LICENSE.txt',
		'!./*.lock',
		'!./*.js',
		'!./*.json',
		'!yarn-error.log',
		'!bin/**',
		'!tests/**',
		'!vendor/bin/**',
		'!vendor/doctrine/**',
		'!vendor/myclabs/**',
		'!vendor/nikic/**',
		'!vendor/phar-io/**',
		'!vendor/phpdocumentor/**',
		'!vendor/phpspec/**',
		'!vendor/phpunit/**',
		'!vendor/sebastian/**',
		'!vendor/theseer/**',
		'!vendor/webmozart/**',
		'!vendor/yoast/**',
		'!.phpunit.result.cache',
		'!.travis.yml',
		'!phpunit.xml.dist',
		'!phpunit.xml',
	])
	.pipe(dest("build/tutor-lms-bunnynet-integration"));
	cb();
}


// from destination directory take all files make zip
function exportZip(cb) {
	const package = require('./package.json');
	const buildName = `${package.name}-${package.version}.zip`;
	return src("./build/**/*.*").pipe(zip(buildName)).pipe(dest("./"));
	cb();
}


gulp.task("watch", function () {
	gulp.watch("assets/scss/**/*.scss", gulp.series(...task_keys));
});

//exports.default 	= series(...task_keys, "watch");
exports.zip 		= series(cleanZip,cleanBuild,bundleFiles, exportZip);