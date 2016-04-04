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
					'assets/css/mesh-grid-foundation.css': 'assets/scss/mesh-grid-foundation.scss'
				}
			}
		},

        uglify: {
            plugin: {
                files: {
                    'assets/js/admin-mcs.min.js' : ['assets/js/admin-mcs.js']
                }
            }
        },

        concat: {
            options: {
                separator: ';\n',
                stripBanners: 'line',
                sourceMap: true
            },

            dist: {
                src: [
                    'assets/js/admin-mcs-blocks.js',
                    'assets/js/admin-mcs-core.js'
                ],

                dest: 'assets/js/admin-mcs.js'
            }
        },

        makepot: {
            target: {
                options: {
                    type: 'wp-plugin'
                }
            }
        },

        watch: {
            grunt: { files: ['Gruntfile.js'] },

            sass: {
	            files: 'assets/scss/**/*.scss',
	            tasks: ['sass']
            },

            javascript: {
                files: [
                    'assets/js/admin-mcs-blocks.js',
                    'assets/js/admin-mcs-core.js'
                ],
                tasks: ['concat','uglify']
            }
        }
    });

    grunt.loadNpmTasks( 'grunt-wp-i18n' );
	grunt.registerTask('build', ['sass']);
    grunt.registerTask('default', [ 'makepot','uglify', 'concat', 'watch']);
}