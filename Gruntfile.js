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
					'assets/css/admin-mesh.css': 'assets/scss/admin-mesh.scss',
					'assets/css/mesh-grid-foundation.css': 'assets/scss/mesh-grid-foundation.scss'
				}
			}
		},

        uglify: {
            plugin: {
                files: {
                    'assets/js/admin-mesh.min.js' : ['assets/js/admin-mesh.js'],
                    'assets/js/mesh.min.js' : ['assets/js/mesh.js']
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
	                'assets/js/admin-mesh-limitslider.js',
                    'assets/js/admin-mesh-pointers.js',
                    'assets/js/admin-mesh-blocks.js',
                    'assets/js/admin-mesh-templates.js',
                    'assets/js/admin-mesh-core.js'
                ],

                dest: 'assets/js/admin-mesh.js'
            },

            frontend: {
	            src: [
		            'assets/js/mesh-frontend.js'
	            ],

	            dest: 'assets/js/mesh.js'
            }
        },

        addtextdomain: {
            options: {
                textdomain: 'mesh',    // Project text domain.
                updateDomains: [
                    'linchpin-mce'
                ]  // List of text domains to replace.
            },
            target: {
                files: {
                    src: [
                        '*.php',
                        '**/*.php',
                        '!node_modules/**',
                        '!tests/**',
                        '!lib/Mischelf/**',
                        '!vendor/**'
                    ]
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
                    'assets/js/admin-mesh-pointers.js',
                    'assets/js/admin-mesh-blocks.js',
                    'assets/js/admin-mesh-templates.js',
                    'assets/js/admin-mesh-core.js',
                    'assets/js/mesh-frontend.js',
                    'assets/js/mesh-frontend-interchange.js'
                ],
                tasks: ['concat','uglify']
            }
        },

        // copy all the files needed for a build within wordpress.org
        copy: {
            main: {
                files: [
                    {expand: true, src: ['admin/*'], dest: 'build/mesh/trunk/'},
                    {expand: true, src: ['assets/**', '!assets/scss/**'], dest: 'build/mesh/trunk/'},
                    {expand: true, src: ['lib/**'], dest: 'build/mesh/trunk/'},
                    {expand: true, src: ['templates/**'], dest: 'build/mesh/trunk/'},
                    {expand: true, src: ['./readme.txt'], dest: 'build/mesh/trunk/', isFile:true},
                    {expand: true, src: ['./CHANGELOG.md'], dest: 'build/mesh/trunk/', isFile:true},
                    {expand: true, src: ['./README.md'], dest: 'build/mesh/trunk/', isFile:true},
                    {expand: true, src: ['./*.php'], dest: 'build/mesh/trunk/'}
                ]
            }
        }
    });

    grunt.loadNpmTasks( 'grunt-wp-i18n' );
    grunt.loadNpmTasks( 'grunt-contrib-copy' );

    grunt.registerTask( 'scripts', [ 'concat', 'uglify' ] );
    grunt.registerTask( 'build', [ 'addtextdomain', 'scripts', 'sass', 'copy' ] );
    grunt.registerTask( 'default', [ 'scripts', 'sass', 'watch' ] );
}
