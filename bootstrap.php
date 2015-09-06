<?php
/**
 * Modifies Walker_Nav_Menu to display a list group.
 *
 * @package Steel\Bootstrap
 * @uses Walker_Nav_Menu
 */

include_once dirname( __FILE__ ) . '/inc/class-walker-nav-menu-list-group.php';

function Steel_Walker_Nav_Menu_List_Group( $args = array() ) {
  $defaults = array(
      'theme_location'  => '',
      'menu'            => '',
      'container'       => '',
      'container_class' => '',
      'container_id'    => '',
      'menu_class'      => 'list-group',
      'menu_id'         => '',
      'echo'            => true,
      'fallback_cb'     => '',
      'before'          => '',
      'after'           => '',
      'link_before'     => '',
      'link_after'      => '',
      'items_wrap'      => '<div id="%1$s" class="%2$s">%3$s</div>',
      'depth'           => 0,
      'walker'          => new Steel_Walker_Nav_Menu_List_Group
  );
  $args = wp_parse_args( $args, $defaults );
  wp_nav_menu( $args );
}
