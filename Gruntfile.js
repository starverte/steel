module.exports = function(grunt) {
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    csscomb: {
      css: {
        options: {
          config: '.csscomb.json'
        },
        files: {
          'css/admin.css': ['css/admin.css'],
          'css/broadcast-admin.css': ['css/broadcast-admin.css'],
          'css/event-style.css': ['css/event-style.css'],
          'css/glyphicons.css': ['css/glyphicons.css'],
          'css/grid.css': ['css/grid.css'],
          'css/slides-admin.css': ['css/slides-admin.css'],
          'css/slides.css': ['css/slides.css'],
          'css/starverte.css': ['css/starverte.css']
        }
      }
    },
    shell: {
      empty_tests: {
        command: 'rm -rfv tests'
      },
      syntax_clone: {
        command: 'git clone https://gist.github.com/e2ab7e46b53e8882ba8e.git tests && rm -rfv tests/.git*'
      },
      phpcs_clone: {
        command: 'git clone https://github.com/squizlabs/PHP_CodeSniffer.git tests/php-codesniffer'
      },
      wpcs_clone: {
        command: 'git clone https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards.git tests/wordpress-coding-standards'
      },
      phpcs_config: {
        command: 'tests/php-codesniffer/scripts/phpcs --config-set installed_paths ../wordpress-coding-standards'
      },
      reset_tests: {
        command: 'echo "" > tests/results'
      },
      syntax_tests: {
        command: "bash tests/syntax.sh >> tests/results"
      },
      phpcs_tests: {
        command: 'tests/php-codesniffer/scripts/phpcs -p -s -v -n . --standard=./.phpcs.rules.xml --extensions=php --ignore=tests/*,node_modules/* >> tests/results'
      },
      phpcbf: {
        command: 'tests/php-codesniffer/scripts/phpcbf -p -s -v -n . --standard=./.phpcs.rules.xml --extensions=php --ignore=tests/*,node_modules/*'
      }
    },
    phpdoc: {
      options: {
        // Task-specific options go here.
      },
      docs: [
        'bootstrap.php',
        'broadcast.php',
        'deprecated.php',
        'inc/class-walker-nav-menu-list-group.php',
        'inc/class-widget-button.php',
        'inc/class-widget-link.php',
        'inc/class-widget-list-group.php',
        'inc/class-widget-random-quote.php',
        'options.php',
        'quotes.php',
        'shortcodes.php',
        'slides.php',
        'social-media.php',
        'steel.php',
        'teams.php',
        'templates.php',
        'widgets.php'
      ]
    }
  });

  grunt.loadNpmTasks( 'grunt-csscomb' );
  grunt.loadNpmTasks( 'grunt-phpdoc' );
  grunt.loadNpmTasks( 'grunt-shell' );

  grunt.registerTask( 'build', ['csscomb:css', 'shell:phpcbf'] );
  grunt.registerTask( 'init', ['shell:empty_tests', 'shell:syntax_clone', 'shell:phpcs_clone', 'shell:wpcs_clone', 'shell:phpcs_config'] );
  grunt.registerTask( 'test', ['shell:reset_tests', 'shell:syntax_tests',  'shell:phpcs_tests'] );
}
