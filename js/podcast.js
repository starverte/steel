var file_frame;

jQuery(function($) {

  $( "#series" ).sortable();
  $( "#series" ).disableSelection();

  $( "#series" ).on( "sortstop", function( event, ui ) {
    var sortedIDs = jQuery( "#series" ).sortable( "toArray" );
    document.getElementById("series_order").value = sortedIDs;
  });

  $( ".episode-title" ).keyup( function( event, ui ) {
    titleID = $( this ).attr( "id" );
    episodeID = titleID.replace("episode_","").replace("_title","");
    episodeTitle = $(this).val();
    $('#controls_'+episodeID).text(episodeTitle);
  });

});

jQuery('.add_episode_media').click( function( event ){

  event.preventDefault();

  if ( file_frame ) {
    file_frame.open();
    return;
  }

  file_frame = wp.media.frames.file_frame = wp.media({
    title: "Select Episode Media",
    button: {
      text: "Select",
    },
    multiple: false
  });

  file_frame.on( 'select', function() {
    attachment = file_frame.state().get('selection').first().toJSON();
    newEpisode(attachment);
  });

  file_frame.open();

});

//Function Definitions
function newEpisode(attachment) {
  jQuery("#series").append('<div class="episode" id="' + attachment.id + '"><div class="episode-controls"><span id="controls_' + attachment.id + '">' + attachment.title + '</span><a class="del-episode" href="#" onclick="deleteEpisode(\'' + attachment.id + '\')" title="Delete episode"><span class="dashicons dashicons-dismiss" style="float:right"></span></a></div><p><input type="text" size="32" class="episode-title" name="series_title_' + attachment.id + '" id="episode_' + attachment.id + '_title" value="'+attachment.title+'" placeholder="Title" /><br><textarea cols="28" rows="3" name="series_content_' + attachment.id + '" id="episode_' + attachment.id + '_summary" placeholder="Summary">' + attachment.caption + '</textarea></p><span class="dashicons dashicons-calendar" style="float:left;padding:5px;"></span><input type="text" size="22" name="series_date_' + attachment.id + '" id="episode_' + attachment.id + '_date" value="" placeholder="mm/dd/yyyy" style="margin:0;"><span class="dashicons dashicons-businessman" style="float:left;padding:5px;"></span><input type="text" size="22" name="series_author_' + attachment.id + '" id="episode_' + attachment.id + '_author" value="" placeholder="Author" style="margin:0;"><span class="dashicons dashicons-clock" style="float:left;padding:5px;"></span><input type="text" size="22" name="series_duration_' + attachment.id + '" id="episode_' + attachment.id + '_duration" value="" placeholder="HH:MM:SS" style="margin:0;"></div>');
  newOrder = jQuery("#series_order").val();
  newOrder = newOrder + ',' + attachment.id;
  newOrder = newOrder.replace(",,",",");
  jQuery("#series_order").val(newOrder);
};
function deleteEpisode(id) {
  jQuery("#"+id).remove();
  deleteOrder = jQuery("#series_order").val();
  deleteOrder = deleteOrder.replace(","+id,"");
  deleteOrder = deleteOrder.replace(id,"");
  deleteOrder = deleteOrder.replace(",,",",");
  jQuery("#series_order").val(deleteOrder);
}
function collapseEpisode(id) {
  jQuery("#episode_img_"+id).toggle("blind");
}
