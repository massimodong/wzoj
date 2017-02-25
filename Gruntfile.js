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
		dest: 'public/js/_bower.min.js'
	}
    },
    cssmin: {
	options: {
		mergeIntoShorthands: false,
		roundingPrecision: -1
	},
	target: {
		files: {
			'public/css/_bower.min.css': ['build/_bower.css']
		}
	}
    },
    bower_concat: {
    	all:{
		dest: {
			'js': 'build/_bower.js',
			'css': 'build/_bower.css'
		},/*
		include: [
			'jquery',
			'bootstrap'
		],*/
		exclude: [
			'isotope'
		],
		mainFiles: {
			'bootstrap':['dist/js/bootstrap.js', 'dist/css/bootstrap.css'],
			'tinymce':['tinymce.js'],
			'isotope':['dist/isotope.pkgd.js']
		}
	}
    },
    copy: {
	main: {
		files: [{expand:true, cwd: 'bower/bootstrap/dist/fonts', src: '**', dest: 'public/fonts/'},
			{expand:true, cwd: 'bower/tinymce/plugins', src: '**', dest: 'public/js/plugins'},
			{expand:true, cwd: 'bower/tinymce/themes', src: '**', dest: 'public/js/themes'},
			{expand:true, cwd: 'bower/tinymce/skins', src: '**', dest: 'public/js/skins'}]
	}
    }
  });

  grunt.loadNpmTasks('grunt-bower-concat');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-contrib-copy');
};
