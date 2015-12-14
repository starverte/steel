/**
 * Matchstix Cards Admin Javascript
 *
 * @package MSX\Cards
 *
 * @since 0.2.0
 */

var file_frame;

jQuery(function($) {

  $( "#cards" ).sortable();
  $( "#cards" ).disableSelection();

  $( "#cards" ).on( "sortstop", function( event, ui ) {
    var $sorted_ids = jQuery( "#cards" ).sortable( "toArray" );
    document.getElementById("cards_order").value = $sorted_ids;
  });

  $( ".card-title" ).keyup( function( event, ui ) {
    var $card_title_id = $( this ).attr( "id" );
    var $card_id = $card_title_id.replace("$card_title_","");
    var $card_title = $(this).val();
    $('#controls_'+$card_id).text($card_title);
  });

});

jQuery('.card-insert-image').click( function( event ){

  event.preventDefault();

  if ( file_frame ) {
    file_frame.open();
    return;
  }

  file_frame = wp.media.frames.file_frame = wp.media({
    title: "Select Slide Media",
    button: {
      text: "Select",
    },
    library: {
      type: 'image',
    },
    multiple: false,
  });

  file_frame.on( 'select', function() {
    attachment = file_frame.state().get('selection').first().toJSON();
    msx_card_insert_image(attachment);
  });

  file_frame.open();

});
jQuery('.card-insert-video').click( function( event ){

  event.preventDefault();

  if ( file_frame ) {
    file_frame.open();
    return;
  }

  file_frame = wp.media.frames.file_frame = wp.media({
    title: "Select Slide Media",
    button: {
      text: "Select",
    },
    library: {
      type: 'video',
    },
    multiple: false,
  });

  file_frame.on( 'select', function() {
    attachment = file_frame.state().get('selection').first().toJSON();
    msx_card_insert_video(attachment);
  });

  file_frame.open();

});
jQuery('.card-insert-link').click( function( event ){
  msx_card_insert_link();
});
jQuery('.card-add-thumbnail').click( function( event ){

  event.preventDefault();

  if ( file_frame ) {
    file_frame.open();
    return;
  }

  file_frame = wp.media.frames.file_frame = wp.media({
    title: "Select Slide Media",
    button: {
      text: "Select",
    },
    library: {
      type: 'image',
    },
    multiple: false,
  });

  file_frame.on( 'select', function() {
    attachment = file_frame.state().get('selection').first().toJSON();
    msx_card_add_thumbnail( event.target.id, attachment );
  });

  file_frame.open();

});
jQuery('.card-set-thumbnail').click( function( event ){

  event.preventDefault();

  if ( file_frame ) {
    file_frame.open();
    return;
  }

  file_frame = wp.media.frames.file_frame = wp.media({
    title: "Select Slide Media",
    button: {
      text: "Select",
    },
    library: {
      type: 'image',
    },
    multiple: false,
  });

  file_frame.on( 'select', function() {
    attachment = file_frame.state().get('selection').first().toJSON();
    msx_card_set_thumbnail( event.target.id, attachment );
  });

  file_frame.open();

});

//Function Definitions
function msx_card_insert_image(attachment) {
  jQuery("#cards").append('<div class="msx-card" id="' + attachment.id + '"><div class="card-controls"><span id="controls_' + attachment.id + '"><span class="dashicons dashicons-format-image"></span> ' + attachment.title + '</span><a class="card-delete" href="#" onclick="msx_card_delete( ' + attachment.id + ' )" title="Delete card"><span class="dashicons dashicons-dismiss" style="float:right"></span></a></div><img id="card_img_' + attachment.id + '" src="' + attachment.url + '" style="max-width:300;max-height:185;margin:0 auto;"><p><input type="text" size="32" class="card-title" name="card_' + attachment.id + '_title" id="card_' + attachment.id + '_title" value="' + attachment.title + '" placeholder="Title" /><br><textarea cols="32" name="card_' + attachment.id + '_content" id="card_' + attachment.id + '_content" placeholder="Caption">' + attachment.caption + '</textarea></p><span class="dashicons dashicons-admin-links" style="float:left;padding:5px;"></span><input type="text" size="28" name="cards_' + attachment.id + '_link" id="cards_' + attachment.id + '_link" value="" placeholder="Link" /><input type="hidden" name="card_' + attachment.id + '_format" id="card_' + attachment.id + '_format" value="image" /></div>');
  $order_new = jQuery("#cards_order").val();
  $order_new = $order_new + ',' + attachment.id;
  $order_new = $order_new.replace(",,",",");
  jQuery("#cards_order").val($order_new);
}
function msx_card_insert_video(attachment) {
  console.log( JSON.stringify( attachment ) );
  if ( 'undefined' == typeof attachment.img )
    $image = attachment.icon;
  else
    $image = attachment.image.src;
  jQuery("#cards").append('<div class="msx-card" id="' + attachment.id + '"><div class="card-controls"><span id="controls_' + attachment.id + '"><span class="dashicons dashicons-format-video"></span> ' + attachment.title + '</span><a class="card-delete" href="#" onclick="msx_card_delete( ' + attachment.id + ' )" title="Delete card"><span class="dashicons dashicons-dismiss" style="float:right"></span></a></div><img id="card_img_' + attachment.id + '" src="' + $image + '" style="max-width:300;max-height:185;margin:0 auto;"><p><input type="text" size="32" class="card-title" name="card_' + attachment.id + '_title" id="card_' + attachment.id + '_title" value="' + attachment.title + '" placeholder="Title" /><br><textarea cols="32" name="card_' + attachment.id + '_content" id="card_' + attachment.id + '_content" placeholder="Caption">' + attachment.caption + '</textarea></p><span class="dashicons dashicons-admin-links" style="float:left;padding:5px;"></span><input type="text" size="28" name="cards_' + attachment.id + '_link" id="cards_' + attachment.id + '_link" value="" placeholder="Link" /><input type="hidden" name="card_' + attachment.id + '_format" id="card_' + attachment.id + '_format" value="video" /></div>');
  $order_new = jQuery("#cards_order").val();
  $order_new = $order_new + ',' + attachment.id;
  $order_new = $order_new.replace(",,",",");
  jQuery("#cards_order").val($order_new);
}
function msx_card_insert_link() {
  jQuery("#cards").append('<div class="msx-card" id="new_link"><div class="card-controls"><span id="controls_new_link"><span class="dashicons dashicons-format-link"></span> New Link</span><a class="card-delete" href="#" onclick="msx_card_delete( new_link )" title="Delete card"><span class="dashicons dashicons-dismiss" style="float:right"></span></a></div><p><input type="text" size="32" class="card-title" name="card_new_link_title" id="card_new_link_title" value="" placeholder="Title" /><br><textarea cols="32" name="card_new_link_content" id="card_new_link_content" placeholder="Caption"></textarea></p><span class="dashicons dashicons-admin-links" style="float:left;padding:5px;"></span><input type="text" size="28" name="cards_new_link_target" id="cards_new_link_target" value="" placeholder="Target URL" /><span class="dashicons dashicons-format-image" style="float:left;padding:5px;"></span><input type="text" size="28" name="cards_new_link_image" id="cards_new_link_image" value="" placeholder="Image URL" /><span class="dashicons dashicons-format-video" style="float:left;padding:5px;"></span><input type="text" size="28" name="cards_new_link_video" id="cards_new_link_video" value="" placeholder="Video URL" /><input type="hidden" name="card_new_link_format" id="card_new_link_format" value="link" /></div>');
  $order_new = jQuery("#cards_order").val();
  $order_new = $order_new + ',new_link';
  $order_new = $order_new.replace(",,",",");
  jQuery("#cards_order").val($order_new);
  jQuery(".card-insert-link").remove();
  jQuery("#card_new_link_title").focus();
}
function msx_card_delete(id) {
  jQuery("#"+id).remove();
  $order_new = jQuery("#cards_order").val();
  $order_new = $order_new.replace(","+id,"");
  $order_new = $order_new.replace(id,"");
  $order_new = $order_new.replace(",,",",");
  jQuery("#cards_order").val($order_new);
}
function msx_card_add_thumbnail( $event, $attachment ) {
  $target = jQuery('#' + $event).data('target');
  $image = jQuery('#' + $event).data('image');
  jQuery($target).val($attachment.id);
  jQuery($image).attr( 'src', $attachment.url );
  jQuery($image).attr( 'style', '' );
  jQuery('#' + $event).attr( 'style', 'display:none' );
}
function msx_card_set_thumbnail( $event, $attachment ) {
  $target = jQuery('#' + $event).data('target');
  $image = jQuery('#' + $event).data('image');
  jQuery($target).val($attachment.id);
  jQuery($image).attr( 'src', $attachment.url );
}
function msx_card_toggle(id) {
  jQuery("#slide_img_"+id).toggle("blind");
}
