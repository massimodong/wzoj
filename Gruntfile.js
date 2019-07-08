module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    uglify: {
      options: {
        banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'
      },
      build: {
        src: 'build/_concated.js',
        dest: 'public/include/js/_concated.min.js'
      }
    },
    cssmin: {
      options: {
        mergeIntoShorthands: false,
        roundingPrecision: -1
      },
      target: {
        files: {
          'public/include/css/_concated.min.css': ['build/_concated.css']
        }
      }
    },
    concat: {
      js: {
        src: [
          'node_modules/jquery/dist/jquery.min.js',
          'node_modules/popper.js/dist/umd/popper.min.js',
          'node_modules/bootstrap/dist/js/bootstrap.min.js',
          'node_modules/bootstrap-select/dist/js/bootstrap-select.min.js'
        ],
        dest: 'build/_concated.js'
      },
      css: {
        src: [
          'node_modules/bootstrap/dist/css/bootstrap.min.css',
          'node_modules/bootstrap-select/dist/css/bootstrap-select.min.css'
        ],
        dest: 'build/_concated.css'
      }
    },
    /*,
    copy: {
      main: {
        files: [{expand:true, cwd: 'bower/bootstrap/dist/fonts', src: '**', dest: 'public/include/fonts/'},
               {expand:true, cwd: 'bower/bootstrap-fileinput/img', src: '**', dest: 'public/include/img/'}]
      }
    }*/
});

grunt.loadNpmTasks('grunt-contrib-concat');
grunt.loadNpmTasks('grunt-contrib-uglify');
grunt.loadNpmTasks('grunt-contrib-cssmin');
/*grunt.loadNpmTasks('grunt-contrib-copy');*/

grunt.registerTask('default', ['concat', 'uglify', 'cssmin'/*, 'copy'*/]);
};
