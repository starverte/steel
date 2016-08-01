module.exports = function(grunt) {
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    copy: {
      bootstrap: {
        files: [
          {
            expand: true,
            flatten: true,
            src: 'node_modules/bootstrap/dist/css/*',
            dest: 'css/',
          },
          {
            expand: true,
            flatten: true,
            src: 'node_modules/bootstrap/dist/fonts/*',
            dest: 'fonts/',
          },
          {
            expand: true,
            flatten: true,
            src: 'node_modules/bootstrap/dist/js/*',
            dest: 'js/',
          },
        ],
      },
      matchstix: {
        files: [
          {
            expand: true,
            src: 'node_modules/matchstix/cards/*',
            dest: 'cards/',
            flatten: true,
          },
        ],
      },
    },
    shell: {
      phpcs_config: {
        command: 'vendor/bin/phpcs --config-set installed_paths ../../wp-coding-standards/wpcs'
      },
      syntax_tests: {
        command: "find . -name '*.php' -not -path './node_modules/*' -not -path './vendor/*' -exec php -lf '{}' \\;"
      },
      phpcs_tests: {
        command: 'vendor/bin/phpcs -p -s -v -n . --standard=./.phpcs.rules.xml --extensions=php --ignore=node_modules/*,vendor/*'
      },
      msx_cards: {
        command: 'sed -i "" "s/MSX_TEXT_DOMAIN/steel/" cards/*'
      }
    }
  });

  grunt.loadNpmTasks( 'grunt-contrib-copy' );
  grunt.loadNpmTasks( 'grunt-shell' );
  grunt.registerTask( 'init', ['shell:phpcs_config'] );
  grunt.registerTask( 'build', ['copy:bootstrap', 'copy:matchstix', 'shell:msx_cards'] );
  grunt.registerTask( 'test', ['shell:syntax_tests', 'shell:phpcs_tests'] );
}

