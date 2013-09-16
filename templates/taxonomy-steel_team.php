<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Flint
 */

get_header(); ?>

  <section id="primary" class="content-area container">
    <div id="content" class="site-content" role="main">

    <?php if ( have_posts() ) : ?>

      <header class="page-header">
        <h1 class="page-title">
          <?php if ( is_tax('steel_team') ) { printf( __( '%s', 'flint' ), '<span>' . single_term_title( '', false ) . '</span>' ); } ?>
        </h1>
      </header><!-- .page-header -->

      <?php /* Start the Loop */ ?>
      <?php while ( have_posts() ) : the_post(); ?>

        <?php include( dirname( __FILE__ )  . '/type-steel_profile.php' ); ?>

      <?php endwhile; ?>

      <?php flint_content_nav( 'nav-below' ); ?>

    <?php else : ?>

      Nothing here.

    <?php endif; ?>

    </div><!-- #content -->
  </section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
