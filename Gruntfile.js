'use strict'
module.exports = function(grunt) {
    grunt.initConfig({
        clean: {
            test: ['tmp']
        },

        // Configuration to be run (and then tested).
        cssmin: {
            minify: {
                expand: true,
                cwd: 'public/css/',
                src: ['*.css', '!*.min.css'],
                dest: 'public/css/',
                ext: '.min.css'
            }
        },
        watch: {
            files: ['public/css/style.css'],
            tasks: ['cssmin']
        }
    });

    // Actually load this plugin's task(s).
    // grunt.loadTasks('tasks');

    // These plugins provide necessary tasks.
    //grunt.loadNpmTasks('grunt-contrib-clean');
    //grunt.loadNpmTasks('grunt-contrib-nodeunit');
    //grunt.loadNpmTasks('grunt-contrib-internal');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-watch');

    // Whenever the "test" task is run, first clean the "tmp" dir, then run this
    // plugin's task(s), then test the result.
    // NOTE: We run the task twice to check for file overwrite issues.
//    grunt.registerTask('test', ['clean', 'cssmin', 'cssmin', 'nodeunit']);

    // By default, lint and run all tests.
//    grunt.registerTask('default', ['jshint', 'test', 'build-contrib']);
};
