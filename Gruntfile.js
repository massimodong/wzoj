module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    uglify: {
      options: {
        banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'
      },
      build: {
        src: 'build/_bower.js',
        dest: 'public/include/js/_bower.min.js'
      }
    },
    cssmin: {
      options: {
        mergeIntoShorthands: false,
        roundingPrecision: -1
      },
      target: {
        files: {
          'public/include/css/_bower.min.css': ['build/_bower.css']
        }
      }
    },
    bower_concat: {
      all:{
        dest: {
          'js': 'build/_bower.js',
        '  css': 'build/_bower.css'
        },
        include: [
          'jquery',
          'bootstrap',
          'datatables.net',
          'datatables.net-bs',
          'bootstrap-fileinput',
          'isotope',
          'bootstrap-select',
          'chart.js',
          'prism',
          'codemirror',
          'Mergely'
        ],
        mainFiles: {
          'bootstrap':['dist/js/bootstrap.js', 'dist/css/bootstrap.css'],
          'isotope':['dist/isotope.pkgd.js'],
          'bootstrap-select':['dist/js/bootstrap-select.js', 'dist/css/bootstrap-select.css', 'dist/js/i18n/defaults-zh_CN.js'],
          'prism':['prism.js', 'components/prism-c.js', 'components/prism-cpp.js',
          'components/prism-pascal.js','components/prism-python.js', 'themes/prism.css']
        }
      }
    },
    copy: {
      main: {
        files: [{expand:true, cwd: 'bower/bootstrap/dist/fonts', src: '**', dest: 'public/include/fonts/'},
               {expand:true, cwd: 'bower/bootstrap-fileinput/img', src: '**', dest: 'public/include/img/'}]
      }
    }
});

grunt.loadNpmTasks('grunt-bower-concat');
grunt.loadNpmTasks('grunt-contrib-uglify');
grunt.loadNpmTasks('grunt-contrib-cssmin');
grunt.loadNpmTasks('grunt-contrib-copy');

grunt.registerTask('default', ['bower_concat', 'uglify', 'cssmin', 'copy']);
};
