<?php
/*
 * Various shortcodes for easily adding customized content
 *
 * @package Steel
 * @module Shortcodes
 *
 * @since 0.7.1
 */
 
/*
 * Create [column] shortcode
 */
add_shortcode( 'column', 'column_shortcode' );
function column_shortcode( $atts, $content = null ) {
	extract( shortcode_atts( array( 'title' => null, 'num' => '2', 'first' => false, 'last' => false ), $atts ) );
	switch ($num) {
		case '2' : $style = 'span6'; break;
		case '3' : $style = 'span4'; break;
		case '4' : $style = 'span3'; break;
		case '5' : if ($first or $last) { $style = 'span3'; } else { $style = 'span2'; } break;
		case '6' : $style = 'span2'; break;
	}
	$new = strip_tags($content, '<a><strong><em><blockquote><code><ol><ul><li>');
	if ($first) {
		if (!empty($title)) { return '<div class="row-fluid"><div class="' . $style . '"><h3>' . esc_attr($title) .'</h3><p>' . $new . '</p></div>'; }
		else { return '<div class="row-fluid"><div class="' . $style . '"><p>' . $new . '</p></div>'; }
	}
	elseif ($last) {
		if (!empty($title)) { return '<div class="' . $style . '"><h3>' . esc_attr($title) .'</h3><p>' . $new . '</p></div></div>'; }
		else { return '<div class="' . $style . '"><p>' . $new . '</p></div></div>'; }
	}
	else {
		if (!empty($title)) { return '<div class="' . $style . '"><h3>' . esc_attr($title) .'</h3><p>' . $new . '</p></div>'; }
		else { return '<div class="' . $style . '"><p>' . $new . '</p></div>'; }	
	}
}

/*
 * Create [tooltip] shortcode
 */
add_shortcode( 'tooltip', 'tooltip_shortcode' );
function tooltip_shortcode( $atts, $content = null ) {
	extract( shortcode_atts( array( 'title' => '', 'placement' => 'top' ), $atts ) );
	$new = strip_tags($content, '<a><strong><em><blockquote><code><ol><ul><li>');
	return '<a class="steel-tooltip" href="#" data-toggle="tooltip" title="' . $title . '" data-placement="' . $placement . '">' . $new . '</a>';
}

/*
 * Create [popover] shortcode
 */
add_shortcode( 'popover', 'popover_shortcode' );
function popover_shortcode( $atts, $content = null ) {
	extract( shortcode_atts( array( 'title' => '', 'placement' => 'top', 'text' => null, 'color' => 'default', 'size' => 'default' ), $atts ) );
	$new_content = strip_tags($content, '<a><strong><em><blockquote><code><ol><ul><li>');
	$new_text = strip_tags($text, '<a><strong><em><blockquote><code><ol><ul><li>');
	$rand = rand();
	switch ($color) {
		case 'default'	: $btn_color = '';				      break;
		case 'blue'			: $btn_color = ' btn-primary';	break;
		case 'green'		: $btn_color = ' btn-success';	break;
		case 'yellow'		: $btn_color = ' btn-warning';	break;
		case 'red'			: $btn_color = ' btn-danger';	  break;
		case 'black'		: $btn_color = ' btn-inverse';	break;
	}
	switch ($size) {
		case 'default'	: $btn_size = '';			    break;
		case 'large'		: $btn_size = ' btn-large';	break;
		case 'small'		: $btn_size = ' btn-small';	break;
		case 'mini'			: $btn_size = ' btn-mini';	break;
	}
	return '<a class="btn steel-popover' . $btn_color . $btn_size . '" href="#" data-toggle="popover" title="' . $title . '" data-content="' . $new_text . '" data-placement="' . $placement . '">' . $new_content . '</a>';
}


/*
 * Create [carousel] shortcode
 */
add_shortcode('carousel', 'carousel_shortcode');
function carousel_shortcode($attr) {
	$post = get_post();

	static $instance = 0;
	$instance++;

	if ( ! empty( $attr['ids'] ) ) {
		// 'ids' is explicitly ordered, unless you specify otherwise.
		if ( empty( $attr['orderby'] ) )
			$attr['orderby'] = 'post__in';
		$attr['include'] = $attr['ids'];
	}

	// Allow plugins/themes to override the default carousel template.
	$output = apply_filters('post_carousel', '', $attr);
	if ( $output != '' )
		return $output;

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}

	extract(shortcode_atts(array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post->ID,
		'itemtag'    => 'div',
		'icontag'    => 'div',
		'captiontag' => 'div',
		'size'       => 'large',
		'include'    => '',
		'exclude'    => ''
	), $attr));

	$id = intval($id);
	if ( 'RAND' == $order )
		$orderby = 'none';

	if ( !empty($include) ) {
		$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( !empty($exclude) ) {
		$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	} else {
		$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	}

	if ( empty($attachments) )
		return '';

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment )
			$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
		return $output;
	}

	$itemtag = tag_escape($itemtag);
	$captiontag = tag_escape($captiontag);
	$icontag = tag_escape($icontag);
	$valid_tags = wp_kses_allowed_html( 'post' );
	if ( ! isset( $valid_tags[ $itemtag ] ) )
		$itemtag = 'dl';
	if ( ! isset( $valid_tags[ $captiontag ] ) )
		$captiontag = 'dd';
	if ( ! isset( $valid_tags[ $icontag ] ) )
		$icontag = 'dt';

	$itemwidth = 100;
	$float = is_rtl() ? 'right' : 'left';

	$selector = "carousel-{$instance}";

	$carousel_style = $carousel_div = '';
	if ( apply_filters( 'use_default_carousel_style', true ) )
		$carousel_style = "";
	$size_class = sanitize_html_class( $size );
	$carousel_div = "<div id='steelCarousel' class='steel-carousel carousel slide carouselid-{$id}'>";
	$output = apply_filters( 'carousel_style', $carousel_style . "\n\t\t" . $carousel_div );
	
	$output .= "<ol class='carousel-indicators'>\n\t";
	
	$n = 0;
	foreach ( $attachments as $id => $attachment ) { 
		$output .= "<li data-target='#steelCarousel'";
		if ($n == 0 ) { $output .= " class='active'"; }
		$output .= " data-slide-to='" . ($n++) . "'></li>\n";
	}
	
	$output .= "</ol>\n";
	$output .= "<div class='carousel-inner'>\n\t";

	$i = 0;
	foreach ( $attachments as $id => $attachment ) {
		$img_src = wp_get_attachment_image_src($id, $size);
		$img_title = apply_filters( 'the_title', $attachment->post_title );
		$alt = trim(strip_tags( get_post_meta($id, '_wp_attachment_image_alt', true) ));
		if ($alt == '') {$img_alt = $img_title; }
		else { $img_alt = $alt; }

		$output .= "<{$itemtag} class='";
		if ($i == 0 ) { $output .= "active "; }
		$output .= "item i" . $i . "'>";
		$output .= "<img id='i" . ($i++) . "' src='" . $img_src[0] . "' alt='" . $img_alt . "' />";
		if ( $captiontag && trim($attachment->post_excerpt) ) {
			$output .= "
				<{$captiontag} class='carousel-caption'>
				<h4>" . $img_title . "</h4>
				<p>" . wptexturize($attachment->post_excerpt) . "</p>
				</{$captiontag}>";
		}
		$output .= "</{$itemtag}>\n";
	}

	$output .= "
			</div>
			<a class='carousel-control left' href='#steelCarousel' data-slide='prev'>&lsaquo;</a>
  		<a class='carousel-control right' href='#steelCarousel' data-slide='next'>&rsaquo;</a>
		</div>\n";

	return $output;
}
?>
