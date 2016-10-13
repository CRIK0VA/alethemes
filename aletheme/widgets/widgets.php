<?php

/*
 * Register Sidebars
 */
function ale_init_sidebars() {
	foreach (aletheme_get_sidebars() as $sidebar) {
		register_sidebar(
				array(
					'id' => 'aletheme-' . sanitize_title($sidebar),
					'name' => $sidebar,
					'description' => $sidebar,
					'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widget-inner">',
					'after_widget' => '</div></div>',
					'before_title' => '<h3>',
					'after_title' => '</h3>'
				)
		);
	}
}
add_action('init', 'ale_init_sidebars');

/**
 * Register Theme Widgets 
 */
function ale_init_widgets() {
	register_widget('Aletheme_Blog_Widget');
	register_widget('Aletheme_Gallery_Widget');
	register_widget('Aletheme_Flickr_Widget');
	register_widget('Aletheme_About_Widget');
    register_widget('Aletheme_Mostcommented_Widget');
}

add_action('widgets_init', 'ale_init_widgets');

/**
 * Image field for widgets 
 */
class Aletheme_WidgetImageField 
{
	private $image_id;
	private $src;
	private $width;
	private $height;
	private $widget_field;
	private $widget = null;

	function __construct($widget = null, $image_id = 0) {
		$uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : NULL;
		$file = basename(parse_url($uri, PHP_URL_PATH));

		// if we're on the Widgets page
		if ($uri && in_array($file, array('widgets.php')) && is_admin()) {
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('thickbox');
			wp_enqueue_script('media-upload');
			wp_enqueue_script('widgetimagefield', ALETHEME_URL . '/assets/js/widgets.js', array('jquery', 'jquery-ui-core', 'thickbox', 'media-upload'), false, true);

			wp_enqueue_style('thickbox');
		}

		// set our properties
		$this->widget = $widget;
		if ($image_id) {
			$this->image_id = intval($image_id);
			$this->src = $this->get_image_src($this->image_id);
			$this->width = $this->get_image_width($this->image_id);
			$this->height = $this->get_image_height($this->image_id);
			$this->widget_field = $this->get_widget_field($widget, $this->image_id);
		}
	}

	function get_image($size = 'full') {
		$image = false;

		if ($this->image_id) {
			$image = wp_get_attachment_image_src($this->image_id, $size);
		}

		return $image;
	}

	function get_image_src($size = 'full') {
		$src = false;

		if ($this->image_id) {
			$image = $this->get_image($size);
			$src = $image[0];
		}

		return $src;
	}

	function get_image_dimensions($size = 'full') {
		$dimensions = array(null, null);

		if ($this->image_id) {
			$image = $this->get_image($size);
			$dimensions = array($image[1], $image[2]);
		}

		return $dimensions;
	}

	function get_image_width($size = 'full') {
		$width = false;

		if ($this->image_id) {
			$dimensions = $this->get_image_dimensions($size);
			$width = $dimensions[0];
		}

		return $width;
	}

	function get_image_height($size = 'full') {
		$height = false;

		if ($this->image_id) {
			$dimensions = $this->get_image_dimensions($size);
			$height = $dimensions[1];
		}

		return $height;
	}

	function get_widget_field($field_name = null) {
		$field = false;
		if ($this->widget && ( isset($this->widget->image_field) || $field_name )) {
			$field = "<div class='iti-image-widget-field'><div class='iti-image-widget-image' id='" . $this->widget->id . "'>";
			$field .= "<input type='hidden' style='display:none;' id='" . $this->widget->get_field_id($this->widget->image_field) . "' name='" . $this->widget->get_field_name($this->widget->image_field) . "' value='" . $this->image_id . "' />";

			if ($this->image_id) {
				$field .= "<img src='" . $this->src . "' alt='' />";
			}
			$field .= "</div>";

			$field .= "<a class='button iti-image-widget-trigger' href='media-upload.php?TB_iframe=1&amp;width=640&amp;height=1500' title='Choose Image'>";
			$field .= "Choose Image";
			$field .= "</a></div>";
		}
		return $field;
	}
}
