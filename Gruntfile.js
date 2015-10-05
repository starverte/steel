module.exports = function(grunt) {
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
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
        'social_media.php',
        'steel.php',
        'teams.php',
        'templates.php',
        'widgets.php'
      ]
    }
  });

  grunt.loadNpmTasks( 'grunt-phpdoc' );
  grunt.loadNpmTasks( 'grunt-shell' );
  grunt.registerTask( 'init', ['shell:empty_tests', 'shell:syntax_clone', 'shell:phpcs_clone', 'shell:wpcs_clone', 'shell:phpcs_config'] );
  grunt.registerTask( 'test', ['shell:reset_tests', 'shell:syntax_tests', 'shell:phpcs_tests'] );
}
