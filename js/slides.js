var file_frame;

jQuery(function($) {

  $( "#slides" ).sortable();
  $( "#slides" ).disableSelection();

  $( "#slides" ).on( "sortstop", function( event, ui ) {
    var sortedIDs = jQuery( "#slides" ).sortable( "toArray" );
    document.getElementById("slides_order").value = sortedIDs;
  });

  $( ".slide-title" ).keyup( function( event, ui ) {
    titleID = $( this ).attr( "id" );
    slideID = titleID.replace("slides_title_","");
    slideTitle = $(this).val();
    $('#controls_'+slideID).text(slideTitle);
  });

});

jQuery('.add_slide_media').click( function( event ){

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
    multiple: false
  });

  file_frame.on( 'select', function() {
    attachment = file_frame.state().get('selection').first().toJSON();
    newSlide(attachment);
  });

  file_frame.open();

});;

//Function Definitions
function newSlide(attachment) {
  jQuery("#slides").append('<div class="slide" id="'+attachment.id+'"><div class="slide-controls"><span id="controls_'+attachment.id+'">'+attachment.title+'</span><a class="del-slide" href="#" onclick="deleteSlide(\''+attachment.id+'\')" title="Delete slide"><span class="dashicons dashicons-dismiss" style="float:right"></span></a></div><img id="slide_img_'+attachment.id+'" src="'+attachment.url+'"  style="width:300px;height:185px;"><p><input type="text" size="32" class="slide-title" name="slides_title_'+attachment.id+'" id="slides_title_'+attachment.id+'" value="'+attachment.title+'" placeholder="Title" /><br><textarea cols="32" name="slides_content_'+attachment.id+'" id="slides_content_'+attachment.id+'" placeholder="Caption">'+attachment.caption+'</textarea></p><span class="dashicons dashicons-admin-links" style="float:left;padding:5px;"></span><input type="text" size="28" name="slides_link_'+attachment.id+'" id="slides_link_'+attachment.id+'" value="" placeholder="Link" /></div>');
  newOrder = jQuery("#slides_order").val();
  newOrder = newOrder + ',' + attachment.id;
  newOrder = newOrder.replace(",,",",");
  jQuery("#slides_order").val(newOrder);
};
function deleteSlide(id) {
  jQuery("#"+id).remove();
  deleteOrder = jQuery("#slides_order").val();
  deleteOrder = deleteOrder.replace(","+id,"");
  deleteOrder = deleteOrder.replace(id,"");
  deleteOrder = deleteOrder.replace(",,",",");
  jQuery("#slides_order").val(deleteOrder);
}
function collapseSlide(id) {
  jQuery("#slide_img_"+id).toggle("blind");
}
