module.exports = function(grunt) {

    // Load all grunt tasks
    require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

		sass: {
			dist: {
				options: {
					outputStyle: 'compressed',
					sourceMap: false
				},

				files: {
					'assets/css/admin-mcs.css': 'assets/scss/admin-mcs.scss',
				}
			}
		},

        uglify: {
            plugin: {
                files: {
                    'js/admin-mcs.min.js' : ['js/admin-mcs.js']
                }
            }
        },

        watch: {
            grunt: { files: ['Gruntfile.js'] },

            sass: {
	            files: 'assets/scss/**/*.scss',
	            tasks: ['sass'],
            },

            javascript: {
                files: [
                    'js/admin-mcs.js'
                ],
                tasks: ['uglify']
            }
        }
    });

	grunt.registerTask('build', ['sass']);
    grunt.registerTask('default', ['uglify', 'watch']);
}