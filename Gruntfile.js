/* jshint node:true */
'use strict';

/**
 * Manage project tasks. 
 */
module.exports = function(grunt) {
	
	// auto load grunt tasks
	require('load-grunt-tasks')(grunt);

	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),

		// watch for changes for files
		watch: {
			// dispatch livereload event
			site: {
				files: ['**/*'],
				options: {
					livereload: true
				}
			}
		}
	});
};
