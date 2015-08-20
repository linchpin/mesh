module.exports = function(grunt) {

    // Load all grunt tasks
    require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        uglify: {
            plugin: {
                files: {
                    'js/admin-mcs.min.js' : ['js/admin-mcs.js']
                }
            }
        },

        watch: {
            grunt: { files: ['Gruntfile.js'] },

            javascript: {
                files: [
                    'js/admin-mcs.js'
                ],
                tasks: ['uglify']
            }
        }
    });

    grunt.registerTask('default', ['uglify', 'watch']);
}