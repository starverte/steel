<?php
/**
 * Widgets: Steel_Walker_Nav_Menu_List_Group class
 *
 * @package Steel\Widgets
 * @since 1.3.0
 */

/**
 * Create HTML list of nav menu items, styled as list group.
 *
 * @uses Walker_Nav_Menu
 */
class Steel_Walker_Nav_Menu_List_Group extends Walker_Nav_Menu {

  /**
   * Starts the list before the elements are added.
   *
   * @see Walker_Nav_Menu::start_lvl()
   *
   * @param string $output Passed by reference. Used to append additional content.
   * @param int    $depth  Depth of menu item. Used for padding.
   * @param array  $args   An array of arguments. @see wp_nav_menu().
   */
  function start_lvl( &$output, $depth = 0, $args = array() ) {
    $indent = str_repeat( "\t", $depth );
    $output .= "\n$indent<div class=\"sub-menu\">\n";
  }

  /**
   * Ends the list of after the elements are added.
   *
   * @see Walker_Nav_Menu::end_lvl()
   *
   * @param string $output Passed by reference. Used to append additional content.
   * @param int    $depth  Depth of menu item. Used for padding.
   * @param array  $args   An array of arguments. @see wp_nav_menu().
   */
  function end_lvl( &$output, $depth = 0, $args = array() ) {
    $indent = str_repeat( "\t", $depth );
    $output .= "$indent</div>\n";
  }

  /**
   * Start the element output.
   *
   * @see Walker_Nav_Menu::start_el()
   *
   * @param string $output Passed by reference. Used to append additional content.
   * @param object $item   Menu item data object.
   * @param int    $depth  Depth of menu item. Used for padding.
   * @param array  $args   An array of arguments. @see wp_nav_menu().
   * @param int    $id     Current item ID.
   */
  function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
    $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

    $class_names = $value = '';

    $classes = empty( $item->classes ) ? array() : (array) $item->classes;
    $classes[] = 'menu-item-' . $item->ID;

    /**
     * Filter the CSS class(es) applied to a menu item's <li>.
     */
    $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
    $class_names = $class_names ? ' class="list-group-item ' . esc_attr( $class_names ) . '"' : '';

    /**
     * Filter the ID applied to a menu item's <li>.
     */
    $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
    $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

    $atts = array();
    $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
    $atts['target'] = ! empty( $item->target )     ? $item->target     : '';
    $atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
    $atts['href']   = ! empty( $item->url )        ? $item->url        : '';

    /**
     * Filter the HTML attributes applied to a menu item's <a>.
     */
    $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

    $attributes = '';
    foreach ( $atts as $attr => $value ) {
      if ( ! empty( $value ) ) {
        $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
        $attributes .= ' ' . $attr . '="' . $value . '"';
      }
    }

    $item_output = $args->before;
    $item_output .= '<a ' . $id . $class_names . $attributes .'>';
    /** This filter is documented in wp-includes/post-template.php */
    $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
    $item_output .= '</a>';
    $item_output .= $args->after;

    /**
     * Filter a menu item's starting output.
     */
    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
  }

  /**
   * Ends the element output, if needed.
   *
   * @see Walker_Nav_Menu::end_el()
   *
   * @param string $output Passed by reference. Used to append additional content.
   * @param object $item   Page data object. Not used.
   * @param int    $depth  Depth of page. Not Used.
   * @param array  $args   An array of arguments. @see wp_nav_menu().
   */
  function end_el( &$output, $item, $depth = 0, $args = array() ) {
    $output .= "\n";
  }
}
