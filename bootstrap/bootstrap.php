<?php
/**
 * Modifies Walker_Nav_Menu to display a list group.
 *
 * @package Steel\Bootstrap
 */

/**
 * Include Steel_Walker_Nav_Menu_List_Group class
 */
include_once dirname( __FILE__ ) . '/class-steel-walker-nav-menu-list-group.php';

/**
 * Include Bootstrap shortcodes
 */
include_once dirname( __FILE__ ) . '/shortcodes.php';

/**
 * Display Bootstrap list group
 *
 * @uses Steel_Walker_Nav_Menu_List_Group
 * @uses wp_nav_menu()
 *
 * @param array $args Arguments for the list group.
 */
function steel_list_group( $args = array() ) {
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
      'walker'          => new Steel_Walker_Nav_Menu_List_Group(),
  );
  $args = wp_parse_args( $args, $defaults );
  wp_nav_menu( $args );
}
