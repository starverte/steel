var file_frame;

jQuery('.btn-channel-cover').click(function (event) {

  event.preventDefault();

  if (file_frame) {
    file_frame.open();
    return;
  }

  file_frame = wp.media.frames.file_frame = wp.media({
    title: "Select Cover Photo",
    button: {
      text: "Select",
    },
    multiple: false
  });

  file_frame.on( 'select', function() {
    attachment = file_frame.state().get('selection').first().toJSON();
    set_cover_photo( attachment );
  });

  file_frame.open();

});

//Function Definitions
function set_cover_photo( attachment ) {
  jQuery('.cover-photo').remove();
  jQuery("#channel_cover_photo_id").val( attachment.id );
  jQuery('#channel_cover_photo').append(
    '<img class="cover-photo" src="' +
    attachment.url +
    '" width="140" height="140">'
  )
};

