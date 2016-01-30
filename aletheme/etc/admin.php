<?php
/**
 * Add admin scripts and styles
 */
function ale_add_scripts($hook) {
	
	// Add general scripts & styles
	wp_enqueue_style('aletheme_admin_css', ALETHEME_URL . '/assets/css/admin.css', array(), ALETHEME_THEME_VERSION);
	wp_enqueue_script('aletheme_colorpicker', ALETHEME_URL.'/assets/js/colorpicker.js', array('jquery'));
	wp_enqueue_script('aletheme_admin_js', ALETHEME_URL . '/assets/js/admin.js', array('jquery', 'aletheme_colorpicker'), ALETHEME_THEME_VERSION);
    wp_enqueue_script( 'aletheme_metaboxes', ALETHEME_URL . '/assets/js/metaboxes.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-datepicker', 'media-upload', 'thickbox') );

	// Add scripts for metaboxes
  	if ( $hook == 'post.php' || $hook == 'post-new.php' || $hook == 'page-new.php' || $hook == 'page.php' ) {
		wp_enqueue_script( 'aletheme_metaboxes', ALETHEME_URL . '/assets/js/metaboxes.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-datepicker', 'media-upload', 'thickbox') );
		wp_enqueue_script( 'aletheme_shortcodes', ALETHEME_URL . '/assets/js/shortcodes.js', array( 'jquery', 'thickbox') );
  	}
	
	// Add scripts for Theme Options page
    if (in_array($hook, array('appearance_page_aletheme'))) {
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('options-custom', ALETHEME_URL.'/assets/js/options-custom.js', array('jquery'));

		// Add inline scripts for Theme Options page
		if (function_exists('aletheme_options_custom_scripts')) {
			add_action('admin_head', 'aletheme_options_custom_scripts');
		}
    }
}
add_action( 'admin_enqueue_scripts', 'ale_add_scripts', 10 );

/**
 * Add Aletheme Options to Admin Navigation 
 */
function ale_add_admin_menu() {
    add_theme_page( __('Theme Options', 'aletheme'), __('Theme Options', 'aletheme'), 'edit_theme_options', 'aletheme','optionsframework_page');
    add_theme_page(__('Sliders', 'aletheme'), __('Sliders', 'aletheme'), 'edit_theme_options', 'aletheme_sliders','aletheme_sliders_page');
}
add_action('admin_menu', 'ale_add_admin_menu', 1);

/**
 * Add custom post types to navigation 
 */
function ale_admin_custom_to_navigation() {
	$post_types = get_post_types(array(
		'show_in_nav_menus' => true
	), 'object' );
	
	foreach ( $post_types as $post_type ) {
		if ( $post_type->has_archive ) {
			add_filter( 'nav_menu_items_' . $post_type->name, 'ale_admin_custom_to_navigation_checkbox', null, 3 );
		}
	}
}
add_action( 'admin_head-nav-menus.php', 'ale_admin_custom_to_navigation');

/**
 * Add custom post type to navigation
 * @global int $_nav_menu_placeholder
 * @global object $wp_rewrite
 * @param array $posts
 * @param array $args
 * @param string $post_type
 * @return array 
 */
function ale_admin_custom_to_navigation_checkbox($posts, $args, $post_type) {
	global $_nav_menu_placeholder, $wp_rewrite;
	$_nav_menu_placeholder = ( 0 > $_nav_menu_placeholder ) ? intval($_nav_menu_placeholder) - 1 : -1;

	$archive_slug = $post_type->has_archive === true ? $post_type->rewrite['slug'] : $post_type->has_archive;
	if ( $post_type->rewrite['with_front'] )
		$archive_slug = substr( $wp_rewrite->front, 1 ) . $archive_slug;
	else
		$archive_slug = $wp_rewrite->root . $archive_slug;

	array_unshift( $posts, (object) array(
		'ID' => 0,
		'object_id' => $_nav_menu_placeholder,
		'post_content' => '',
		'post_excerpt' => '',
		'post_title' => $post_type->labels->all_items,
		'post_type' => 'nav_menu_item',
		'type' => 'custom',
		'url' => site_url( $archive_slug ),
	) );
	
	return $posts;
}

/**
 * Show alert message if default blog description is not changed
 * @global object $current_user 
 */
function ale_admin_notice_tagline() {
	if ((get_option('blogdescription') === 'Just another WordPress site')) {
		global $current_user;
		$user_id = $current_user->ID;

		if (!get_user_meta($user_id, 'ignore_tagline_notice')) {
		echo '<style>#blogdescription {border-color:red;}</style>';
		echo '<div class="error">';
			echo '<p><strong>', sprintf(__('Please update your <a href="%s">site tagline</a>', 'aletheme'), admin_url('options-general.php'), '?tagline_notice_ignore=0'), '</strong></p>';
		echo '</div>';
		}
	}
}
add_action('admin_notices', 'ale_admin_notice_tagline');

/**
 * Add custom columns to admin data tables 
 */
function ale_admin_table_columns() {
	if (function_exists('aletheme_get_post_types')) {
		foreach (aletheme_get_post_types() as $type => $config) {
			if (isset($config['columns']) && count($config['columns'])) {
				foreach ($config['columns'] as $column) {
					if (function_exists('ale_admin_posts_' . $column . '_column_head') && function_exists('ale_admin_posts_' . $column . '_column_content')) {
						add_filter('manage_' . $type . '_posts_columns', 'ale_admin_posts_' . $column . '_column_head', 10); 
						add_action('manage_' . $type . '_posts_custom_column', 'ale_admin_posts_' . $column . '_column_content', 10, 2);						
					}
				}
			}
		}
	}
}
add_action('admin_init', 'ale_admin_table_columns', 100);

/**
 * Change footer
 */
function ale_admin_remove_footer_admin() {
	echo '<span id="footer-thankyou">Developed by <a href="'.'http://www.alethemes.com'.'" target="_blank">Alethemes</a></span>';
}
add_filter('admin_footer_text', 'ale_admin_remove_footer_admin');

/**
 * Add custom icons for post types 
 */
function ale_post_type_icons() {
	?>
		<style type="text/css">
		<?php
		if (function_exists('aletheme_get_post_types')) {
			foreach (aletheme_get_post_types() as $type => $config) {
				?>
					#menu-posts-<?php echo $type ?> .wp-menu-image {
						background: url(<?php echo ALETHEME_URL ?>/assets/images/post_type_icons/<?php echo $type ?>.png) no-repeat 6px -17px !important;
					}
					#menu-posts-<?php echo $type ?>:hover .wp-menu-image {
						background-position: 6px 7px !important;
					}
				<?php
			}
		}
		?>
		</style>
	<?php
}
add_action( 'admin_head', 'ale_post_type_icons');

/**
 * Add featured image header column to admin data-table
 * 
 * @param array $defaults
 * @return array 
 */
function ale_admin_posts_featured_column_head($defaults) {
	array_put_to_position($defaults, 'Image', 1, 'featured-image');
	return $defaults;  
}

/**
 * Add featured image data column to admin data-table
 *
 * @param string $column_name
 * @param int $post_id 
 */
function ale_admin_posts_featured_column_content($column_name, $post_id) {
	if ($column_name == 'featured-image') {  
		$post_featured_image = ale_get_featured_image_src($post_id);  
		if ($post_featured_image) {  
			echo '<img src="' . $post_featured_image . '" alt="" width="60" />'; 
		}  
	}
}


/**
 * Add featured image header column to admin data-table
 * 
 * @param array $defaults
 * @return array 
 */
function ale_admin_posts_first_image_column_head($defaults) {  
	array_put_to_position($defaults, 'Image', 1, 'first-image');
	return $defaults;  
}

/**
 * Add featured image data column to admin data-table
 *
 * @param string $column_name
 * @param int $post_id 
 */
function ale_admin_posts_first_image_column_content($column_name, $post_id) {
	if ($column_name == 'first-image') {  
		if (has_post_thumbnail($post_id)) :
			$image = ale_get_featured_image_src($post_id);
		else :
			$image = ale_get_first_attached_image_src($post_id);
		endif;	

		if ($image) {  
			echo '<img src="' . $image . '" alt="" width="60" />'; 
		}  
	}
}
