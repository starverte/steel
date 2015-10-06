var file_frame;

jQuery(function($) {

  $( "#series" ).sortable();
  $( "#series" ).disableSelection();

  $( "#series" ).on( "sortstop", function( event, ui ) {
    var sorted_items = jQuery( "#series" ).sortable( "toArray" );
    document.getElementById("item_list").value = sorted_items;
  });

  $( ".item-title" ).keyup( function( event, ui ) {
    var item_title_id = $( this ).attr( "id" );
    var item_id = item_title_id.replace("post_title_","");
    var item_title = $(this).val();
    $('#controls_'+item_id).text(item_title);
  });
  $( '.item-date' ).datepicker({
    dateFormat: "MM d, yy"
  });

});

jQuery('.btn-media-add').click( function( event ){

  event.preventDefault();

  if ( file_frame ) {
    file_frame.open();
    return;
  }

  file_frame = wp.media.frames.file_frame = wp.media({
    title: "Select Media",
    button: {
      text: "Select",
    },
    multiple: false
  });

  file_frame.on( 'select', function() {
    attachment = file_frame.state().get('selection').first().toJSON();
    item_new( attachment );
  });

  file_frame.open();

});



//Function Definitions
function item_new(attachment) {
  artist = '';
  if ( typeof attachment.artist != 'undefined' )
    artist = attachment.artist;

  jQuery("#series").append(
    '<div class="item ui-sortable-handle" id="' + attachment.id + '">' +
      '<div class="item-header">' +
        '<span class="controls-title" id="controls_' + attachment.id + '">' + attachment.title + '</span><a class="item-delete" href="#" onclick="item_delete( ' + attachment.id + ' )" title="Delete item"><span class="dashicons dashicons-dismiss"></span></a>' +
      '</div>' +

      '<p>' +
        '<textarea class="item-title" name="post_title_' + attachment.id + '" id="post_title_' + attachment.id + '" placeholder="Title" rows="1">' + attachment.title + '</textarea>' +
        '<textarea class="item-content" name="post_content_' + attachment.id + '" id="post_content_' + attachment.id + '" placeholder="Summary" rows="3">' + attachment.description + '</textarea>' +
      '</p>' +

      '<div class="item-h2">' +
        '<p><strong>Details</strong></p>' +
      '</div>' +

      '<span class="dashicons dashicons-calendar"></span>' +

      '<input class="item-date" type="text" size="28" name="date_published_' + attachment.id + '" id="date_published_' + attachment.id + '" value="' + attachment.date + '" placeholder="Date published">' +

      '<span class="dashicons dashicons-businessman"></span>' +

      '<input class="item-artist" type="text" size="28" name="artist_' + attachment.id + '" id="artist_' + attachment.id + '" value="' + artist + '" placeholder="Author">' +

      '<div class="clearfix"></div>' +

      '<div class="item-h2">' +
        '<p><strong>Files</strong></p>' +
      '</div>' +

      '<div>' +
        '<span class="dashicons dashicons-media-audio"></span>' +
        '<span class="audio-file">' + basename( attachment.url ) + '</span>' +
        '<div class="clearfix"></div>' +
      '</div>' +
    '</div>'
  );
  newOrder = jQuery("#item_list").val();
  newOrder = newOrder + ',' + attachment.id;
  newOrder = newOrder.replace(",,",",");
  jQuery("#item_list").val(newOrder);
};
function item_delete(id) {
  jQuery("#"+id).remove();
  deleteOrder = jQuery("#item_list").val();
  deleteOrder = deleteOrder.replace(","+id,"");
  deleteOrder = deleteOrder.replace(id,"");
  deleteOrder = deleteOrder.replace(",,",",");
  jQuery("#item_list").val(deleteOrder);
}
