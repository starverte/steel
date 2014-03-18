/*
 * Scripts to power the Marketplace module
 *
 * @package Steel/Marketplace
 */

var file_frame;

jQuery(function($) {

  $( "#product_view" ).sortable();
  $( "#product_view" ).disableSelection();

  $( "#product_view" ).on( "sortstop", function( event, ui ) {
    var sortedIDs = jQuery( "#product_view" ).sortable( "toArray" );
    document.getElementById("product_view_order").value = sortedIDs;
  });

  $( ".product-view-title" ).keyup( function( event, ui ) {
    titleID = $( this ).attr( "id" );
    product_viewID = titleID.replace("product_view_title_","");
    product_viewTitle = $(this).val();
    $('#controls_'+product_viewID).text(product_viewTitle);
  });

});

jQuery('.add_product_view_media').click( function( event ){

  event.preventDefault();

  if ( file_frame ) {
    file_frame.open();
    return;
  }

  file_frame = wp.media.frames.file_frame = wp.media({
    title: "Select product image",
    button: {
      text: "Select",
    },
    multiple: false
  });

  file_frame.on( 'select', function() {
    attachment = file_frame.state().get('selection').first().toJSON();
    newView(attachment);      
  });

  file_frame.open();

});;

//Function Definitions
function newView(attachment) {
  jQuery("#product_view").append('<div class="product-view" id="'+attachment.id+'"><div class="product-view-controls"><span id="controls_'+attachment.id+'">'+attachment.title+'</span><a class="del-product-view" href="#" onclick="deleteView(\''+attachment.id+'\')" title="Delete product view"><span class="steel-icon-dismiss" style="float:right"></span></a></div><img id="product_view_img_'+attachment.id+'" src="'+attachment.url+'" style="max-width:250px;max-height:155px;"><div style="clear:both;"></div><span class="steel-icon-cover-photo" style="float:left;padding:5px;"></span><input class="product-view-title" type="text" size="23" name="product_view_title_'+attachment.id+'" id="product_view_title_'+attachment.id+'" value="" placeholder="Title (i.e. Front)" style="margin:0;" /></div>');
  newOrder = jQuery("#product_view_order").val();
  newOrder = newOrder + ',' + attachment.id;
  newOrder = newOrder.replace(",,",",");
  jQuery("#product_view_order").val(newOrder);
};
function deleteView(id) {
  jQuery("#"+id).remove();
  deleteOrder = jQuery("#product_view_order").val();
  deleteOrder = deleteOrder.replace(","+id,"");
  deleteOrder = deleteOrder.replace(id,"");
  deleteOrder = deleteOrder.replace(",,",",");
  jQuery("#product_view_order").val(deleteOrder);
}
function collapseproduct_view(id) {
  jQuery("#product_view_img_"+id).toggle("blind");
}