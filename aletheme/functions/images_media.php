<?php
/**
 * Get first post image. Used in filters.
 * 
 * @param string $content
 * @return string
 */
function ale_parse_first_image($content = null) {
    if (!$content) {
        $content = get_the_content();
    }
    preg_match('~(<img[^>]+>)~sim', trim($content), $matches);
    return isset($matches[1]) ? $matches[1] : '';
}

/**
 * Remove first image from post. Used in filters.
 * 
 * @param string $content
 * @return string
 */
function ale_remove_first_image($content = null) {
    if (!$content) {
        $content = get_the_content();
    }
    $content = trim(preg_replace('~(<a[^>]+>)?\s*(<img[^>]+>)\s*(</a>)?~sim', '', $content, 1));
    return $content;
}

/**
 * Remove all post images.
 * 
 * @param string $content
 * @return string 
 */
function ale_remove_images($content = null) {
    if (!$content) {
        $content = get_the_content();
    }
    $content = trim(preg_replace('~(<a[^>]+>)?\s*(<img[^>]+>)\s*(</a>)?~sim', '', $content));
    return $content;
}


/**
 * Wrap all content images
 * @param string $content
 * @return string
 */
function ale_wrap_images($content = null) {
    if (!$content) {
        $content = get_the_content();
    }
    $content = preg_replace('~(<img[^>]+>)~sim', '<div class="image">$1</div>', $content);
    return $content;
}

/**
 * Get all post images from content
 * @param string $content
 * @return string
 */
function ale_get_all_images($content = null) {
    if (!$content) {
        $content = get_the_content();
    }
    preg_match_all('~(<img[^>]+>)~sim', $content, $matches);
    return $matches[1];
}

/**
 * Remove links aroung images
 *
 * @param string $content
 * @return string
 */
function ale_clear_images($content)
{
	return preg_replace('~<a[^>]*>(<img[^>]*>)<\/a>~iu', '$1', $content);
}

/**
 * Fix image margins for captions
 * @param int $x
 * @param array $attr
 * @param string $content
 * @return string 
 */
function ale_fix_image_margings($x=null, $attr, $content) {
	extract(shortcode_atts(array(
			'id'    => '',
			'align'    => 'alignnone',
			'width'    => '',
			'caption' => ''
		), $attr));

	if ( 1 > (int) $width || empty($caption) ) {
		return $content;
	}

	if ( $id ) {
		$id = 'id="' . $id . '" ';			
	}

    return '<div ' . $id . 'class="wp-caption ' . $align . '" style="width:'.$width.'px" >' . $content . '<p class="wp-caption-text">' . $caption . '</p></div>';
}
add_filter('img_caption_shortcode', 'ale_fix_image_margings', 10, 3);

/**
 * Get all attached images. Filter by hide_form_gallery meta key
 * @param integer $id
 * @param boolean $show_hidden
 * @return array
 */
function ale_get_attached_images($id = null, $show_hidden = true) {
    if (!$id) {
        $id = get_the_ID();
    }
	
	$attrs = array(
        'post_parent' => $id,
        'post_status' => null,
        'post_type' => 'attachment',
        'post_mime_type' => 'image',
        'order' => 'ASC',
        'numberposts' => -1,
        'orderby' => 'menu_order',
	);
	
	if (!$show_hidden) {
		$attrs['meta_query'] = array(
			array(
				'key'		=> '_ale_hide_from_gallery',
				'value'		=> 0,
				'type'		=> 'DECIMAL',
			),
		);
	}
	
    return get_children($attrs);
}

/**
 * Get first post image attachment
 * @param integer $id
 * @param boolean $show_hidden
 * @return array|boolean
 */
function ale_get_first_attached_image($id = null, $show_hidden = true) {
    if (!$id) {
        $id = get_the_ID();
    }
	
	$attrs = array(
        'post_parent' => $id,
        'post_status' => null,
        'post_type' => 'attachment',
        'post_mime_type' => 'image',
        'order' => 'ASC',
        'numberposts' => 1,
        'orderby' => 'menu_order',
    );
	
	if (!$show_hidden) {
		$attrs['meta_query'] = array(
			array(
				'key'		=> '_ale_hide_from_gallery',
				'value'		=> 0,
				'type'		=> 'DECIMAL',
			),
		);
	}
	
    $image = get_children($attrs);
    
    if (!count($image)) {
        return false;
    }
    
    $image = array_values($image);
    return $image[0];
}

/**
 * Display first attached image 
 * @param int $id
 * @param mixed $size
 * @param boolean $show_hidden
 * @return string 
 */
function ale_display_first_attached_image($id = null, $size = 'large', $show_hidden = true)
{
	$image = ale_get_first_attached_image($id, $show_hidden);
	
	if (!$image) {
		return '';
	}
	
	echo wp_get_attachment_image($image->ID, $size);
}

/**
 * Get featured image src
 * @param int $post_id
 * @param string $size
 * @return string
 */
function ale_get_featured_image_src($post_id, $size  = 'thumbnail') {
	if (!$post_id) {
		$post_id = get_the_ID();
	}
	
	$post_thumbnail_id = get_post_thumbnail_id($post_id);  
	if ($post_thumbnail_id) {
		$post_thumbnail_img = wp_get_attachment_image_src($post_thumbnail_id, $size);  
		return $post_thumbnail_img[0];  
	} else {
		return '';
	}
}

/**
 * Get first attached image source with set size.
 * 
 * @param int $post_id
 * @param string $size
 * @param boolean $show_hidden
 * @return string 
 */
function ale_get_first_attached_image_src($post_id, $size = 'thumbnail', $show_hidden = true) {
	if (!$post_id) {
		$post_id = get_the_ID();
	}
	$image = ale_get_first_attached_image($post_id, $show_hidden);
	
	if ($image) {
		$image_img = wp_get_attachment_image_src($image->ID, $size);  
		return $image_img[0];  
	} else {
		return '';
	}
}

/**
 * Get attachment ID by URL
 * 
 * @param string $url
 * @return integer|boolean
 */
function ale_get_attachment_id($url) {

    $dir = wp_upload_dir();
    $dir = trailingslashit($dir['baseurl']);

    if(false === strpos($url, $dir)) {
		return false;
	}
	
    $file = basename($url);

    $query = array(
        'post_type' => 'attachment',
        'fields' => 'ids',
        'meta_query' => array(
            array(
                'value' => $file,
                'compare' => 'LIKE',
            )
        )
    );

    $query['meta_query'][0]['key'] = '_wp_attached_file';
    $ids = get_posts( $query );

    foreach($ids as $id) {
		if($url == array_shift(wp_get_attachment_image_src($id, 'full'))) {
			return $id;
		}
	}
	
    $query['meta_query'][0]['key'] = '_wp_attachment_metadata';
    $ids = get_posts( $query );

    foreach($ids as $id) {
        $meta = wp_get_attachment_metadata($id);
        foreach($meta['sizes'] as $size => $values) {
            if($values['file'] == $file && $url == array_shift(wp_get_attachment_image_src($id, $size))) {
                return $id;
            }	
		}
    }

    return false;
}

/**
 * Echo meta for media attachment
 * @param string $key
 * @param boolean $single
 * @param mixed $post_id 
 */
function ale_media_meta($key, $media_id) {
	echo ale_get_media_meta($key, $media_id);
}
/**
 * Find meta for media attachment
 * @param string $key
 * @param boolean $single
 * @param mixed $post_id 
 */
function ale_get_media_meta($key, $media_id) {
	$key = '_ale_' . $key;
	return get_post_meta($media_id, $key, true);
}

/**
 * YouTube Player
 *
 * Creates the necessary iframe structure for YouTube
 * Gets custom theme options and adds to iframe src.
 *
 * @return string
 */
function ale_create_youtube_player($media_source = '', $width = 640, $height = 360, $allow_autoplay = 1) {
	if (preg_match('%(?:youtube\.com/(?:user/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $media_source, $matches)) {
		/* Give player a unique ID */
		$player_id = 'ytplayer_' . $matches[1] . '_' . ale_get_random_number();

		$defaults = array(
			'wmode' => 'transparent',
			'enablejsapi' => 1,
			'playerapiid' => 'ytplayer',
			'origin' => esc_url(home_url()),
			'color' => null,
			'theme' => null,
			'fs' => null,
			'loop' => null,
			'rel' => null,
			'showinfo' => null,
			'autoplay' => null
		);

		$params = wp_parse_args(parse_url($media_source, PHP_URL_QUERY), $defaults);

		// Stop autoplay from possibly autoplaying on pages with multiple posts and videos
		if (0 == $allow_autoplay || !is_singular())
			$params['autoplay'] = 0;

		$url = 'http://www.youtube.com/embed/' . $matches[1] . '/?' . http_build_query(array_filter($params), '', '&');

		$output = '<'.'iframe width="' . $width . '" height="' . $height . '" src="' . $url . '" id="' . $player_id . '" class="youtube-player" frameborder="0" wmode="Opaque" allowfullscreen></iframe>';
	} else {
		$output = __('Sorry that seems to be an invalid <strong>YouTube</strong> URL. Please check it again.', 'aletheme');
	}

	return $output;
}

/**
 * Vimeo Player
 *
 * Creates the necessary iframe structure for Vimeo
 * Gets custom theme options and adds to iframe src.
 *
 * @return string
 */
function ale_create_vimeo_player($media_source = '', $width = 640, $height = 360, $allow_autoplay = 1) {
	if (preg_match('~^http://(?:www\.)?vimeo\.com/(?:clip:)?(\d+)~', $media_source, $matches)) {
		/* Give player a unique ID */
		$player_id = 'player_' . $matches[1] . '_' . ale_get_random_number();
		$color = ale_get_option('primary_2');
		$video_color = ( 1 == ale_get_option('enable_styles') && $color ) ? ltrim($color, '#') : '252A31';

		$defaults = array(
			'wmode' => 'transparent',
			'api' => 1,
			'player_id' => $player_id,
			'title' => 0,
			'byline' => 0,
			'portrait' => 0,
			'autoplay' => null,
			'loop' => null,
			'rel' => null,
			'color' => $video_color
		);

		$params = wp_parse_args(parse_url($media_source, PHP_URL_QUERY), $defaults);

		if (0 == $allow_autoplay || !is_singular())
			$params['autoplay'] = 0;

		$url = 'http://player.vimeo.com/video/' . $matches[1] . '/?' . http_build_query(array_filter($params), '', '&');

		$output = '<'.'iframe width="' . $width . '" height="' . $height . '" src="' . $url . '" id="' . $player_id . '" class="vimeo-player" frameborder="0" data-playcount="0" webkitAllowFullScreen allowFullScreen></iframe>';
	} else {
		$output = __('Sorry that seems to be an invalid <strong>Vimeo</strong> URL. Please check it again. Make sure there is a string of numbers at the end (e.g. http://vimeo.com/1234567).', 'aletheme');
	}
	return $output;
}

/**
 * Create WP Embed
 *
 * Creates the necessary iframe structure for available
 * sites using the default WP embed shortcode. If a video
 * address is one of the accepted sites that can use the
 * URL and oembed, aside from Vimeo and Youtube, this function
 * will be called. Vimeo and YouTube url's use a custom
 * function of ale_create_vimeo_player() or ale_create_youtube_player()
 *
 * @return string
 */
function ale_create_wp_embed_player( $media_source = '', $width = 640, $height = 360, $allow_autoplay = 1 ) {
	$wp_embed = new WP_Embed();
	$output = $wp_embed->run_shortcode( '[embed width=' . $width . ' height=' . $height . ']' . $media_source . '[/embed]' );
	return $output;
}


function ale_get_embed_video($url) {
	require_once ABSPATH . WPINC . '/class-oembed.php';
	$WP_oEmbed = new WP_oEmbed();
	$provider = $WP_oEmbed->discover($url);
	$data = $WP_oEmbed->fetch( $provider, $url);

	return $data;
}

/**
 * Get embed thumbnail for media entry
 * 
 * @param string $url
 * @param boolean $echo
 * @return string 
 */
function ale_embed_thumbnail($url, $echo = true) {
	$data = ale_get_embed_video($url);
	if ($echo) {
		echo '<img src="' . $data->thumbnail_url . '" alt="' . $data->title . ' " />';
	} else {
		return $data->thumbnail_url;
	}
}

/**
 * Lazyloading filter for content
 * Disables lazyloading for inserted galleries
 * 
 * @param string $content
 * @return string
 */
function ale_lazyload_images($content) {
	$pre = get_template_directory_uri() . '/img/lazyload.gif';
	
	preg_match_all('~<img[^>]+>~sim', $content, $_matches);
	
	if (!count($_matches)) {
		return $content;
	}
	
	$matches = array();
	foreach ($_matches[0] as $match) {
		$entry = array(
			'source' => $match,
		);
		
		if (!substr_count($match, 'no-lazyload')) {
			$update = preg_replace('~src="([^"]+)"~sim', 'src="' . $pre . '" data-original="$1"', $match);
			$content = str_replace($match, $update, $content);
		}
		
		$matches[] = $entry;
	}
	return $content;
}

/**
 * Lazyloading filter.
 * To use the jQuery lazyload plugin should be initialized.
 * 
 * @param type $content
 * @return type
 */
function ale_bulk_lazyload_images($content) {
	$content = preg_replace('~src="([^"]+)"~sim', 'src="' . $pre . '" data-original="$1"', $content);
	return $content;
}