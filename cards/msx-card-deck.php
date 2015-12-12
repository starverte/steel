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
  global $msx_text_domain;
  $labels = array(
    'name'                => _x( 'Card Decks', 'Post Type General Name', $msx_text_domain ),
    'singular_name'       => _x( 'Card Deck', 'Post Type Singular Name', $msx_text_domain ),
    'menu_name'           => __( 'Cards', $msx_text_domain ),
    'all_items'           => __( 'All decks', $msx_text_domain ),
    'view_item'           => __( 'View Deck', $msx_text_domain ),
    'add_new_item'        => __( 'Add New Deck', $msx_text_domain ),
    'add_new'             => __( 'New Deck', $msx_text_domain ),
    'edit_item'           => __( 'Edit Deck', $msx_text_domain ),
    'update_item'         => __( 'Update Deck', $msx_text_domain ),
    'search_items'        => __( 'Search decks', $msx_text_domain ),
    'not_found'           => __( 'No decks found', $msx_text_domain ),
    'not_found_in_trash'  => __( 'No decks found in Trash. Did you check recycling?', $msx_text_domain ),
  );
  $args = array(
    'label'               => __( 'msx_card_deck', $msx_text_domain ),
    'description'         => __( 'A grouping of cards', $msx_text_domain ),
    'labels'              => $labels,
    'supports'            => array( 'title' ),
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

<a href="#" class="button card-insert-image" title="Add image card to deck">
  <span class="dashicons dashicons-format-image"></span> Add image
</a>

<a href="#" class="button card-insert-video" title="Add video card to deck">
  <span class="dashicons dashicons-format-video"></span> Add video
</a>
<div id="cards_wrap">
  <div id="cards"><?php
    foreach ( $cards as $card ) {
      if ( ! empty( $card ) && false !== get_post_status( $card->ID ) ) {
        $card_custom = get_post_custom( $card->ID );

        if ( ! empty( $card_custom['image'] ) ) {
          $image = wp_get_attachment_image_src( $card_custom['image'][0], 'msx-card-thumb' );
        } elseif ( ! empty( $card_custom['video'] ) ) {
          $image = wp_get_attachment_image_src( get_post_thumbnail_id( $card->ID ), 'msx-card-thumb' );
        }
?>
    <div class="msx-card" id="<?php echo $card->ID; ?>">
      <div class="card-controls">
        <span id="controls_<?php echo $card->ID; ?>"><span class="dashicons dashicons-format-<?php echo get_post_format( $card->ID ); ?>"></span> <?php echo $card->post_title; ?></span>

        <a class="card-delete" href="#" onclick="msx_card_delete( '<?php echo $card->ID; ?>' )" title="Delete card">
          <span class="dashicons dashicons-dismiss" style="float:right"></span>
        </a>
      </div><?php

        if ( ! empty( $image ) ) {
          if ( 'video' == get_post_format( $card->ID ) ) { ?>
      <a class="card-set-thumbnail" id="set_<?php echo $card->ID; ?>_thumbnail" href="#" data-target="#card_<?php echo $card->ID; ?>_thumbnail" data-image="#card_img_<?php echo $card->ID; ?>">
        <img id="card_img_<?php echo $card->ID; ?>" src="<?php echo $image[0]; ?>" width="<?php echo $image[1]; ?>" height="<?php echo $image[2]; ?>">
      </a><?php
          } else { ?>
      <img id="card_img_<?php echo $card->ID; ?>" src="<?php echo $image[0]; ?>" width="<?php echo $image[1]; ?>" height="<?php echo $image[2]; ?>"><?php
          }
        } else { ?>
      <a class="card-set-thumbnail" id="set_<?php echo $card->ID; ?>_thumbnail" href="#" data-target="#card_<?php echo $card->ID; ?>_thumbnail" data-image="#card_img_<?php echo $card->ID; ?>">Add video thumbnail</a>
      <img id="card_img_<?php echo $card->ID; ?>" src="" width="300" height="185" style="display:none">
<?php } ?>

      <p>
        <input type="text" size="32" class="card-title" name="card_<?php echo $card->ID; ?>_title" id="card_<?php echo $card->ID; ?>_title" value="<?php echo $card->post_title; ?>" placeholder="Title" /><br>
        <textarea cols="32" name="card_<?php echo $card->ID; ?>_content" id="card_<?php echo $card->ID; ?>_content" placeholder="Caption"><?php echo $card->post_content; ?></textarea>
      </p>

      <span class="dashicons dashicons-admin-links" style="float:left;padding:5px;"></span>
      <input type="text" size="28" name="card_<?php echo $card->ID; ?>_link" id="card_<?php echo $card->ID; ?>_link" value="<?php echo $card_custom['target'][0]; ?>" placeholder="Link" />

<?php if ( 'video' == get_post_format( $card->ID ) ) : ?>
      <input type="hidden" name="card_<?php echo $card->ID; ?>_thumbnail" id="card_<?php echo $card->ID; ?>_thumbnail" value="<?php echo get_post_thumbnail_id( $card->ID ); ?>" />
<?php endif; ?>

      <input type="hidden" name="card_<?php echo $card->ID; ?>_format" id="card_<?php echo $card->ID; ?>_format" value="<?php echo get_post_format( $card->ID ); ?>" />
    </div><?php
    }

    $image = null;
  } ?>
  </div>

  <div class="btn-card" title="Add card to deck">
    <a class="card-insert-image card-new" href="#">
      <span class="dashicons dashicons-format-image"></span> Add Image
    </a>
    <a class="card-insert-video card-new" href="#">
      <span class="dashicons dashicons-format-video"></span> Add Video
    </a>
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
        if ( 'attachment' == get_post_type( $card ) ) {
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

          update_post_meta( $card, 'target', $_POST[ 'card_' . $card . '_link' ] );
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
