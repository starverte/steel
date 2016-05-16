<?php
/**
 * Matchstix Deck of Cards (custom post type)
 *
 * @package MSX\Cards
 *
 * @since 0.2.0
 */

/**
 * Return arguments for registering msx_card_deck
 */
function msx_card_deck_post_type_args() {
  $labels = array(
    'name'                  => _x( 'Card Decks', 'post type general name', 'steel' ),
    'singular_name'         => _x( 'Card Deck', 'post type singular name', 'steel' ),
    'menu_name'             => __( 'Cards', 'steel' ),
    'name_admin_bar'        => __( 'Card Decks', 'steel' ),
    'add_new'               => __( 'Add New', 'steel' ),
    'add_new_item'          => __( 'Add New Card Deck', 'steel' ),
    'edit_item'             => __( 'Edit Card Deck', 'steel' ),
    'new_item'              => __( 'New Card Deck', 'steel' ),
    'view_item'             => __( 'View Card Deck', 'steel' ),
    'update_item'           => __( 'Update', 'steel' ),
    'search_items'          => __( 'Search Card Decks', 'steel' ),
    'not_found'             => __( 'No decks found.', 'steel' ),
    'not_found_in_trash'    => __( 'No decks found in Trash.', 'steel' ),
    'all_items'             => __( 'All Card Decks', 'steel' ),
    'archives'              => __( 'Card Deck Archives', 'steel' ),
    'insert_into_item'      => __( 'Insert into deck', 'steel' ),
    'uploaded_to_this_item' => __( 'Uploaded to this deck', 'steel' ),
    'featured_image'        => __( 'Featured Image', 'steel' ),
    'set_featured_image'    => __( 'Set featured image', 'steel' ),
    'remove_featured_image' => __( 'Remove featured image', 'steel' ),
    'use_featured_image'    => __( 'Use as featured image', 'steel' ),
    'filter_items_list'     => __( 'Filter decks list', 'steel' ),
    'items_list_navigation' => __( 'Card Decks list navigation', 'steel' ),
    'items_list'            => __( 'Card Decks list', 'steel' ),
  );
  $args = array(
    'label'               => __( 'msx_card_deck', 'steel' ),
    'description'         => __( 'A grouping of cards', 'steel' ),
    'labels'              => $labels,
    'supports'            => array( 'title', 'editor' ),
    'hierarchical'        => false,
    'public'              => false,
    'show_ui'             => true,
    'show_in_menu'        => true,
    'show_in_nav_menus'   => false,
    'show_in_admin_bar'   => true,
    'menu_position'       => 15,
    'menu_icon'           => 'dashicons-slides',
    'can_export'          => false,
    'has_archive'         => false,
    'exclude_from_search' => true,
    'publicly_queryable'  => true,
    'rewrite'             => true,
    'capability_type'     => 'post',
  );
  return $args;
}

/**
 * Display cards on Edit screen
 */
function msx_card_deck_edit() {
  global $post;
  $deck_custom = get_post_custom( $post->ID );
  $cards_list = explode( ',', $deck_custom['cards_order'][0] );

  $args = array(
    'post_type' => 'msx_card',
    'post__in' => $cards_list,
    'orderby' => 'post__in',
    'order' => 'ASC',
  );
  $cards = get_posts( $args ); ?>

<div id="cards_wrap">
  <div id="cards"><?php
    foreach ( $cards as $card ) {
      if ( ! empty( $card ) && false !== get_post_status( $card->ID ) ) {
        $card_custom = get_post_custom( $card->ID );

        if ( ! empty( $card_custom['image'] ) ) {
          $img_src = wp_get_attachment_image_url( $card_custom['image'][0], 'msx-card-thumb' );
          $img_src_set = wp_get_attachment_image_srcset( $card_custom['image'][0], 'msx-card-thumb' );
          $img_sizes = wp_get_attachment_image_sizes( $card_custom['image'][0], 'msx-card-thumb' );
        } elseif ( ! empty( $card_custom['video'] ) ) {
          $img_src = wp_get_attachment_image_url( get_post_thumbnail_id( $card->ID ), 'msx-card-thumb' );
          $img_src_set = wp_get_attachment_image_srcset( get_post_thumbnail_id( $card->ID ), 'msx-card-thumb' );
          $img_sizes = wp_get_attachment_image_sizes( get_post_thumbnail_id( $card->ID ), 'msx-card-thumb' );
        }
?>
    <div class="msx-card" id="<?php echo $card->ID; ?>">
      <div class="card-controls">
        <span id="controls_<?php echo $card->ID; ?>"><span class="dashicons dashicons-format-<?php echo get_post_format( $card->ID ); ?>"></span> <?php echo $card->post_title; ?></span>

        <a class="card-delete" href="#" onclick="msx_card_delete( '<?php echo $card->ID; ?>' )" title="Delete card">
          <span class="dashicons dashicons-dismiss" style="float:right"></span>
        </a>
      </div><?php

        if ( ! empty( $img_src ) ) {
          if ( 'video' == get_post_format( $card->ID ) ) { ?>
      <a class="card-set-thumbnail" id="set_<?php echo $card->ID; ?>_thumbnail" href="#">
        <img id="card_img_<?php echo $card->ID; ?>" src="<?php echo $img_src; ?>" srcset="<?php echo $img_src_set; ?>" sizes="<?php echo $img_sizes; ?>" data-target="#card_<?php echo $card->ID; ?>_thumbnail" data-image="#card_img_<?php echo $card->ID; ?>">
      </a><?php
          } else { ?>
      <img id="card_img_<?php echo $card->ID; ?>" src="<?php echo $img_src; ?>" srcset="<?php echo $img_src_set; ?>" sizes="<?php echo $img_sizes; ?>"><?php
          }
        } elseif ( 'video' == get_post_format( $card->ID ) ) { ?>
      <a class="card-add-thumbnail" id="set_<?php echo $card->ID; ?>_thumbnail" href="#" data-target="#card_<?php echo $card->ID; ?>_thumbnail" data-image="#card_img_<?php echo $card->ID; ?>">Add video thumbnail</a>
      <img id="card_img_<?php echo $card->ID; ?>" src="" width="300" height="185" style="display:none">
<?php } ?>

      <p>
        <input type="text" size="32" class="card-title" name="card_<?php echo $card->ID; ?>_title" id="card_<?php echo $card->ID; ?>_title" value="<?php echo $card->post_title; ?>" placeholder="Title" /><br>
        <textarea cols="32" name="card_<?php echo $card->ID; ?>_content" id="card_<?php echo $card->ID; ?>_content" placeholder="Caption"><?php echo $card->post_content; ?></textarea>
      </p><?php
        if ( 'link' != get_post_format( $card->ID ) ) { ?>
      <span class="dashicons dashicons-admin-links" style="float:left;padding:5px;"></span>
      <input type="text" size="28" name="card_<?php echo $card->ID; ?>_link" id="card_<?php echo $card->ID; ?>_link" value="<?php echo $card_custom['target'][0]; ?>" placeholder="Link" /><?php
        } else { ?>
      <span class="dashicons dashicons-admin-links" style="float:left;padding:5px;"></span>
      <input type="text" size="28" name="card_<?php echo $card->ID; ?>_link_target" id="card_<?php echo $card->ID; ?>_link_target" value="<?php echo $card_custom['target'][0]; ?>" placeholder="Target URL" />
      <span class="dashicons dashicons-format-image" style="float:left;padding:5px;"></span>
      <input type="text" size="28" name="card_<?php echo $card->ID; ?>_link_image" id="card_<?php echo $card->ID; ?>_link_image" value="<?php echo $card_custom['image'][0]; ?>" placeholder="Image URL" />
      <span class="dashicons dashicons-format-video" style="float:left;padding:5px;"></span>
      <input type="text" size="28" name="card_<?php echo $card->ID; ?>_link_video" id="card_<?php echo $card->ID; ?>_link_video" value="<?php echo $card_custom['video'][0]; ?>" placeholder="Video URL" /><?php
        } ?>

<?php if ( 'video' == get_post_format( $card->ID ) ) : ?>
      <input type="hidden" name="card_<?php echo $card->ID; ?>_thumbnail" id="card_<?php echo $card->ID; ?>_thumbnail" value="<?php echo get_post_thumbnail_id( $card->ID ); ?>" />
<?php endif; ?>

      <input type="hidden" name="card_<?php echo $card->ID; ?>_format" id="card_<?php echo $card->ID; ?>_format" value="<?php echo get_post_format( $card->ID ); ?>" />
    </div><?php
    }

    $image = null;
  } ?>
  </div>
</div>

<input type="hidden" name="cards_order" id="cards_order" value="<?php echo $deck_custom['cards_order'][0]; ?>">
<div style="float:none; clear:both;"></div><?php
}

/**
 * Save deck and card data
 */
function msx_card_deck_save() {
  global $post;
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE && (isset( $post_id )) ) { return $post_id; }
  if ( defined( 'DOING_AJAX' ) && DOING_AJAX && (isset( $post_id )) ) { return $post_id; }
  if ( preg_match( '/\edit\.php/', $_SERVER['REQUEST_URI'] ) && (isset( $post_id )) ) { return $post_id; }

  if ( isset( $_POST['cards_order'] ) ) {
    $cards_list = '';
    update_post_meta( $post->ID, 'cards_order', $_POST['cards_order'] );
    $cards = explode( ',', $_POST['cards_order'] );
    foreach ( $cards as $card ) {
      if ( ! empty( $card ) ) {
        if ( 'new_link' == $card ) {
          // Prevent infinite loop.
          remove_action( 'save_post', 'msx_card_deck_save' );

          $new_card = wp_insert_post(
            array(
              'post_title' => $_POST['card_new_link_title'],
              'post_content' => $_POST['card_new_link_content'],
              'post_parent' => $card,
              'post_type' => 'msx_card',
              'post_status' => 'publish',
            )
          );

          add_action( 'save_post', 'msx_card_deck_save' );

          update_post_meta( $new_card, 'target', $_POST['card_new_link_target'] );
          update_post_meta( $new_card, 'image', $_POST['card_new_link_image'] );
          update_post_meta( $new_card, 'video', $_POST['card_new_link_video'] );
          set_post_format( $new_card, 'link' );
          $cards_list = $cards_list . $new_card . ',';
        } else if ( 'attachment' == get_post_type( $card ) ) {
          // Prevent infinite loop.
          remove_action( 'save_post', 'msx_card_deck_save' );

          $new_card = wp_insert_post(
            array(
              'post_title' => $_POST[ 'card_' . $card . '_title' ],
              'post_content' => $_POST[ 'card_' . $card . '_content' ],
              'post_parent' => $card,
              'post_type' => 'msx_card',
              'post_status' => 'publish',
            )
          );

          add_action( 'save_post', 'msx_card_deck_save' );

          update_post_meta( $new_card, 'target', $_POST[ 'card_' . $card . '_link' ] );
          update_post_meta( $new_card, $_POST[ 'card_' . $card . '_format' ], $card );
          set_post_format( $new_card, $_POST[ 'card_' . $card . '_format' ] );
          if ( isset( $_POST[ 'card_' . $card . '_thumbnail' ] ) ) {
            set_post_thumbnail( $new_card, $_POST[ 'card_' . $card . '_thumbnail' ] );
          }
          $cards_list = $cards_list . $new_card . ',';
        } else if ( 'msx_card' == get_post_type( $card ) ) {
          // Prevent infinite loop.
          remove_action( 'save_post', 'msx_card_deck_save' );

          wp_update_post(
            array(
              'ID' => $card,
              'post_title' => $_POST[ 'card_' . $card . '_title' ],
              'post_content' => $_POST[ 'card_' . $card . '_content' ],
            )
          );

          add_action( 'save_post', 'msx_card_deck_save' );

          if ( 'link' != $_POST[ 'card_' . $card . '_format' ] ) {
            update_post_meta( $card, 'target', $_POST[ 'card_' . $card . '_link' ] );
          } else {
            update_post_meta( $card, 'target', $_POST[ 'card_' . $card . '_link_target' ] );
            update_post_meta( $card, 'image', $_POST[ 'card_' . $card . '_link_image' ] );
            update_post_meta( $card, 'video', $_POST[ 'card_' . $card . '_link_video' ] );
          }

          set_post_format( $card, $_POST[ 'card_' . $card . '_format' ] );
          if ( isset( $_POST[ 'card_' . $card . '_thumbnail' ] ) ) {
            set_post_thumbnail( $card, $_POST[ 'card_' . $card . '_thumbnail' ] );
          }
          $cards_list = $cards_list . $card . ',';
        }
      }
    }

    update_post_meta( $post->ID, 'cards_order', $cards_list );
  }
}
add_action( 'save_post_msx_card_deck', 'msx_card_deck_save' );

/**
 * Display insert media buttons on MSX Card Deck Edit screen
 */
function msx_card_deck_button_image() {
  global $current_screen;

  if ( 'msx_card_deck' == $current_screen->post_type ) {
 ?>
    <button type="button" class="button card-insert-image"><span class="dashicons dashicons-format-image"></span> Add Image</button>
    <button type="button" class="button card-insert-video"><span class="dashicons dashicons-format-video"></span> Add Video</button>
    <button type="button" class="button card-insert-link"><span class="dashicons dashicons-admin-links"></span> Add Link</button><?php
  }
}
add_action( 'media_buttons', 'msx_card_deck_button_image' );

/**
 * Hide WP_Editor on MSX Card Deck Edit screen
 */
function msx_card_deck_editor_hide() {
  global $current_screen;

  if ( 'msx_card_deck' == $current_screen->post_type ) { ?>
<style type="text/css">
  #wp-content-editor-container,
  #post-status-info,
  .wp-switch-editor {
    display: none;
  }
</style><?php
  }
}
add_action( 'admin_footer', 'msx_card_deck_editor_hide' );

/**
 * Display deck of cards as unordered list
 *
 * @param int   $deck The ID of the card deck.
 * @param array $args Array of arguments to affect output.
 */
function msx_card_deck_display( $deck, $args = array() ) {
  $defaults = array(
    'container'       => 'div',
    'container_class' => '',
    'container_id'    => '',
    'deck_class'      => 'msx-card-deck',
    'deck_id'         => 'msx-card-deck-' . $deck,
    'card_class'      => '',
    'image_size'      => 'full',
  );

  $args = wp_parse_args( $args, $defaults );

  $deck = (int) $deck;
  $card_deck = get_post( $deck );
  if ( ! empty( $card_deck ) ) {
    $output = '';
    $deck_custom = get_post_custom( $card_deck->ID );

    $cards_list = explode( ',', $deck_custom['cards_order'][0] );

    $cards = get_posts(
      array(
        'post_type' => 'msx_card',
        'post__in' => $cards_list,
        'orderby' => 'post__in',
        'order' => 'ASC',
      )
    );

    // Start the deck of cards.
    $output .= '<' . $args['container'] . ' class="' . $args['container_class'] . '" id="' . $args['container_id'] . '">';
    $output .= '<ul class="' . $args['deck_class'] . '" id="' . $args['deck_id'] . '">';

    // Display the cards.
    $i = 0;
    foreach ( $cards as $card ) {
      if ( ! empty( $card ) ) {
        $custom = get_post_custom( $card->ID );
        $format = get_post_format( $card->ID );

        $output .= sprintf(
          '<li class="msx-card msx-card-%1$s %2$s" id="msx-card-%3$s">',
          $format,
          $args['card_class'],
          $card->ID
        );

        switch ( $format ) {
          case 'image' :
            $img_src = wp_get_attachment_image_url( $custom['image'][0], $args['image_size'] );
            $img_src_set = wp_get_attachment_image_srcset( $custom['image'][0], $args['image_size'] );
            $img_sizes = wp_get_attachment_image_sizes( $custom['image'][0], $args['image_size'] );
            $output .= ! empty( $custom['target'][0] ) ? '<a href="' . $custom['target'][0] . '" title="' . $card->post_title . '">' : '';

            $output .= sprintf(
              '<img src="%1$s" srcset="%2$s" sizes="%3$s" alt="%4$s">',
              $img_src,
              $img_src_set,
              $img_sizes,
              $card->post_title
            );

            $output .= ! empty( $custom['target'][0] ) ? '</a>' : '';
            break;
          case 'video' :
            $video_meta = wp_get_attachment_metadata( $custom['video'][0] );
            $video_url = wp_get_attachment_url( $custom['video'][0] );
            $video_source = sprintf( '<source src="%1$s" type="%2$s">', $video_url, get_post_mime_type( $custom['video'][0] ) );
            $image = wp_get_attachment_image_src( get_post_thumbnail_id( $card->ID ), $args['image_size'] );
            $output .= sprintf(
              '<video width="%1$s" height="%2$s" poster="%3$s" controls>%4$s</video>',
              $video_meta['width'],
              $video_meta['height'],
              $image[0],
              $video_source
            );
            break;
          case 'link' :
            if ( ! empty( $custom['video'][0] ) ) {
              $output .= '<div class="embed-responsive embed-responsive-16by9">';
              $output .= '<iframe src="' . $custom['video'][0] . '" autoload></iframe>';
              $output .= '</div>';
            }
            break;
        }

        $output .= ! empty( $card->post_title ) || ! empty( $card->post_content ) ? '<div class="caption">' : '';
        $output .= ! empty( $custom['target'][0] ) ? '<a href="' . $custom['target'][0] . '" title="' . $card->post_title . '">' : '';
        $output .= ! empty( $card->post_title ) ? '<h4 class="msx-card-title">' . $card->post_title . '</h4>' : '';
        $output .= ! empty( $custom['target'][0] ) ? '</a>' : '';
        $output .= ! empty( $card->post_content ) ? '<p class="msx-card-content">' . $card->post_content . '</p>' : '';
        $output .= ! empty( $card->post_title ) || ! empty( $card->post_content ) ? '</div>' : '';

        $output .= '</li>';
      }
    }

    // End the deck of cards.
    $output .= '</ul>';
    $output .= '</' . $args['container'] . '>';

    echo $output;
  }
}

/**
 * Display deck of cards using Bootstrap's carousel
 *
 * @param int   $deck The ID of the card deck.
 * @param array $args Array of arguments to affect output.
 */
function msx_card_deck_carousel( $deck, $args = array() ) {
  $defaults = array(
    'container_class' => 'carousel slide',
    'container_id'    => 'carousel-' . $deck,
    'deck_class'      => 'carousel-inner msx-card-deck',
    'deck_id'         => 'msx-card-deck-' . $deck,
    'card_class'      => 'item',
    'image_size'      => 'full',
    'indicators'      => true,
    'controls'        => false, // Currently unused. See http://getbootstrap.com/javascript/#carousel.
  );

  $args = wp_parse_args( $args, $defaults );

  $deck = (int) $deck;
  $card_deck = get_post( $deck );
  if ( ! empty( $card_deck ) ) {
    $output = '';
    $deck_custom = get_post_custom( $card_deck->ID );

    $cards_list = explode( ',', $deck_custom['cards_order'][0] );

    $cards = get_posts(
      array(
        'post_type' => 'msx_card',
        'post__in' => $cards_list,
        'orderby' => 'post__in',
        'order' => 'ASC',
      )
    );

    // Add the carousel wrapper.
    $output .= '<div class="' . $args['container_class'] . '" id="' . $args['container_id'] . '" data-ride="carousel">';

    // Display indicators.
    if ( ! false == $args['indicators'] ) {
      $i = 0;
      $output .= '<ol class="carousel-indicators">';

      foreach ( $cards as $card ) {
        if ( ! empty( $card ) ) {
          if ( 0 == $i ) {
            $output .= '<li data-target="#' . $args['container_id'] . '" data-slide-to="0" class="active"></li>';
          } else {
            $output .= '<li data-target="#' . $args['container_id'] . '" data-slide-to="' . $i . '"></li>';
          }

          $i++;
        }
      }

      $output .= '</ol>';
    }

    // Start the decks of cards.
    $output .= '<div class="' . $args['deck_class'] . '" id="' . $args['deck_id'] . '">';

    // Display the cards.
    $c = 0;
    foreach ( $cards as $card ) {
      if ( ! empty( $card ) ) {
        $c++;
        $custom = get_post_custom( $card->ID );
        $format = get_post_format( $card->ID );
        $card_class = 1 == $c ? $args['card_class'] . ' active' : $args['card_class'];

        $output .= sprintf(
          '<div class="%1$s msx-card msx-card-%2$s" id="msx-card-%3$s">',
          $card_class,
          $format,
          $card->ID
        );

        switch ( $format ) {
          case 'image' :
            $img_src = wp_get_attachment_image_url( $custom['image'][0], $args['image_size'] );
            $img_src_set = wp_get_attachment_image_srcset( $custom['image'][0], $args['image_size'] );
            $img_sizes = wp_get_attachment_image_sizes( $custom['image'][0], $args['image_size'] );
            $output .= ! empty( $custom['target'][0] ) ? '<a href="' . $custom['target'][0] . '" title="' . $card->post_title . '">' : '';

            $output .= sprintf(
              '<img src="%1$s" srcset="%2$s" sizes="%3$s" alt="%4$s">',
              $img_src,
              $img_src_set,
              $img_sizes,
              $card->post_title
            );

            $output .= ! empty( $custom['target'][0] ) ? '</a>' : '';
            break;
          case 'video' :
            $video_meta = wp_get_attachment_metadata( $custom['video'][0] );
            $video_url = wp_get_attachment_url( $custom['video'][0] );
            $video_source = sprintf( '<source src="%1$s" type="%2$s">', $video_url, get_post_mime_type( $custom['video'][0] ) );
            $image = wp_get_attachment_image_src( get_post_thumbnail_id( $card->ID ), $args['image_size'] );
            $output .= sprintf(
              '<video width="%1$s" height="%2$s" poster="%3$s" controls>%4$s</video>',
              $video_meta['width'],
              $video_meta['height'],
              $image[0],
              $video_source
            );
            break;
          case 'link' :
            if ( ! empty( $custom['video'][0] ) ) {
              $output .= '<div class="embed-responsive embed-responsive-16by9">';
              $output .= '<iframe src="' . $custom['video'][0] . '" autoload noborder></iframe>';
              $output .= '</div>';
            }
            break;
        }

        $output .= '<div class="carousel-caption">';
        $output .= '<h4 class="msx-card-title">' . $card->post_title . '</h4>';
        $output .= '<p class="msx-card-content">' . $card->post_content . '</p>';
        $output .= '</div>';

        $output .= '</div>';
      }
    }

    // End the deck of cards.
    $output .= '</div>';

    if ( ! false == $args['controls'] ) {
      $output .= '<a class="left carousel-control" href="#' . $args['container_id'] . '" role="button" data-slide="prev">';
      $output .= '<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>';
      $output .= '<span class="sr-only">Previous</span>';
      $output .= '</a>';
      $output .= '<a class="right carousel-control" href="#' . $args['container_id'] . '" role="button" data-slide="next">';
      $output .= '<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>';
      $output .= '<span class="sr-only">Next</span>';
      $output .= '</a>';
    }

    $output .= '</div>';

    echo $output;
  }
}

/**
 * Return IDs for all card decks
 */
function msx_card_deck_list() {
  $args = array( 'post_type' => 'msx_card_deck', 'posts_per_page' => -1 );
  $msx_card_decks = get_posts( $args );
  $card_decks = array();
  $card_decks[0] = 'None';
  if ( $msx_card_decks ) {
    foreach ( $msx_card_decks as $msx_card_deck ) {
      $post_id = $msx_card_deck->ID;
      $title = $msx_card_deck->post_title;
      $card_decks[ $post_id ] = $title;
    }
    wp_reset_postdata();
  }
  return $card_decks;
}

/**
 * Sanitize options based on msx_sanitize_card_deck_list
 *
 * @param mixed $input Unfiltered input.
 */
function msx_sanitize_card_deck_list( $input ) {
  $valid = msx_card_deck_list();

  if ( array_key_exists( $input, $valid ) ) {
    return $input;
  } else {
    return;
  }
}
