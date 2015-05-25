<?php
class Aletheme_Sliders
{
	const POST_TYPE = '_aletheme_slider_';
	
	protected $action;
	
	protected $wpdb;
	
	public $slider;
	
	public $id;
	
	public function __construct() 
	{
		global $wpdb;
		$this->wpdb = $wpdb;
		add_action('init', array($this, 'generalInit'));
		add_action('admin_init', array($this, 'adminInit'));
	}
	
	public function generalInit() 
	{
		add_shortcode('ale-slider', array($this, 'shortcodeSliderInit'));
	}
	
	public function adminInit()
	{
		if (isset($_GET['page']) && $_GET['page'] == 'aletheme_sliders') {
			if (count($_POST) && isset($_POST['title'])) {
				$title = (string) $_POST['title'];
				$id = $this->create($title);
				if ($id) {
					wp_redirect(self::slidersUrl(array('action' => 'edit', 'id' => $id)));
				} else {
					wp_redirect(self::slidersUrl(array('action' => 'create', 'state' => 'error')));
				}
				exit;
			}
            if(isset($_GET['action'])){
                if ($_GET['action'] == 'delete') {
                    if (wp_verify_nonce($_GET['_wpnonce'], 'aletheme_slider_delete_nonce')) {
                        $this->delete($_GET['id']);
                    }
                    wp_redirect(self::slidersUrl());
                    exit;
                }
                add_action( 'admin_print_scripts', array($this, 'adminScripts') );
            }
		}
		
		add_action('wp_ajax_save_slider', array($this, 'save'));
		add_action('wp_ajax_delete_slide', array($this, 'deleteSlide'));
		
		add_action( 'media_buttons', array($this, 'addMediaButton'), 100 );
		add_action( 'admin_footer', array($this, 'editorPopup') );
		
	}
	
	public function adminScripts() 
	{
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('jquery-ui-selectable');
		wp_enqueue_script('jquery-ui-tabs');
		wp_enqueue_script('jquery-ui-dialog');		

		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');

		wp_enqueue_style( 'thickbox' );

		wp_enqueue_script('aletheme_sliders_js', ALETHEME_URL . '/assets/js/sliders.js', array('jquery'), ALETHEME_THEME_VERSION);

		wp_localize_script('aletheme_sliders_js', 'aletheme_sliders_js', array(
			'ajaxurl'	=> admin_url( 'admin-ajax.php' ),
			'assetsurl' => ALETHEME_URL . '/assets',
			'nonce'		=> wp_create_nonce( 'aletheme_slider_ajax_nonce' ),
		));	
	}
	
	public function init()
	{
        if(!isset($_GET['action'])) {$_GET['action']='';}

		$this->action = (string) $_GET['action'];
		if($this->action == 'create') {
			include_once ALETHEME_PATH . '/sliders/pages/edit-slider.php';
		} else if ($this->action == 'edit') {
			
			$this->id = (int) $_GET['id'];
			
			$this->slider = $this->get($this->id);
			
			if (!$this->slider) {
				$this->id = 0;
			}
			
			include_once ALETHEME_PATH . '/sliders/pages/edit-slider.php';
		} else if ($this->action == 'delete') {
			// delete existing slider
			if (!wp_verify_nonce($_GET['_wpnonce'], 'aletheme_slider_delete_nonce')) {
				die('not allowed');
			}
		} else {
			include_once ALETHEME_PATH . '/sliders/pages/manage-sliders.php';
		}
	}
	
	static public function slidersUrl($actions = array())
	{
		$url = admin_url('themes.php?page=aletheme_sliders');
		foreach ($actions as $act => $val) {
			$url .= '&' . $act . '=' . $val;
		}
		return $url;
	}
	
	public function save()
	{
		$post = $this->get($_POST['id']);
		
		$data = array();
		if ($post) {
			$update = array(
				'ID'			=> $post->ID,
				'post_title'	=> $_POST['title'],
			);
			
			if ($post->post_status != 'private') {
				$update['post_name'] = sanitize_title($update['title']);
			}
			
			$id = wp_update_post($update);
			
			if (isset($_POST['entries']) && is_array($_POST['entries'])) {
				foreach ($_POST['entries'] as $k => $entry) {
					if (isset($entry['id']) && $entry['id']) {
						$slide_id = wp_update_post(array(
							'ID'					=> $entry['id'],
							'post_type'				=> self::POST_TYPE,
							'post_title'			=> (string) $entry['title'],
							'post_name'				=> sanitize_title($entry['title']),
							'menu_order'			=> $k + 1,
							'pinged'				=> $entry['url'],
							'post_content'			=> $entry['description'],
							'post_excerpt'			=> $entry['html'],
							'post_content_filtered' => $entry['image'],
						));
					} else {
						$slide_id = wp_insert_post(array(
							'post_type'				=> self::POST_TYPE,
							'post_title'			=> (string) $entry['title'],
							'post_name'				=> sanitize_title($entry['title']),
							'post_parent'			=> $post->ID,
							'menu_order'			=> $k + 1,
							'pinged'				=> $entry['url'],
							'post_content'			=> $entry['description'],
							'post_excerpt'			=> $entry['html'],
							'post_content_filtered' => $entry['image'],
							'post_author'			=> 1,
							'comment_status'		=> 'closed',
							'ping_status'			=> 'closed',
							'post_status'			=> 'inherit', // for created sliders
							'post_date'				=> date('Y-m-d H:i:s'),
						));
					}
					$data['slides'][$k] = $slide_id;
				}
			}
			
			$data['id'] = $id;
			$data['state'] = 'success';
		} else {
			$data['state'] = 'error';
			$data['msg'] = 'No posts found.';
		}
		
		echo json_encode($data);
		die;
	}
	
	public function create($title = '')
	{
		if (!$title) {
			$title = 'Unnamed Slider';
		}
		
		$id = wp_insert_post(array(
			'post_type'		=> self::POST_TYPE,
			'post_title'	=> $title,
			'post_name'		=> sanitize_title($title),
			'post_parent'	=> 0,
			'post_content'	=> '',
			'post_author'	=> 1,
			'comment_status'=> 'closed',
			'ping_status'	=> 'closed',
			'post_status'	=> 'publish', // for created sliders
			'menu_order'	=> 1,
			'post_date'		=> date('Y-m-d H:i:s'),
			'guid'          => '',
		));
		
		return $id;
	}
	
	/**
	 * Delete single slide 
	 */
	public function deleteSlide()
	{
		$id = (int) $_POST['id'];
		$slide = $this->get($id);
		if ($slide->ID && $slide->post_status == 'inherit' && $slide->post_type == self::POST_TYPE) {
			wp_delete_post($id, 1);
			echo json_encode(array(
				'state' => 'success',
			));
		} else {
			echo json_encode(array(
				'state' => 'error',
			));			
		}
		die;
	}
	
	/**
	 * Delete slider
	 */
	public function delete($id)
	{
		$id = (int) $id;
		$slider = $this->get($id);
		if ($slider->ID && $slider->post_status == 'publish' && $slider->post_type == self::POST_TYPE) {
			wp_delete_post($id, 1);
			return true;
		} else {
			return false;
		}
	}
	
	public function get($id)
	{
		$id = (int) $id;
		
		return get_post($id);
	}
	
	public function getBySlug($slug)
	{
		$id = $this->wpdb->get_var($this->wpdb->prepare("SELECT ID FROM {$this->wpdb->posts} WHERE post_name = %s", $slug));
		
		return $id ? $this->get($id) : false;
	}
	
	public function getFirstSlide($parent_id)
	{
		$slides = $this->getSlides($parent_id);
		
		return count($slides) ? $slides[0] : false;
	}


	public function getSlides($parent_id)
	{
		$parent_id = (int) $parent_id;
		
		if (!$parent_id) {
			return false;
		}
		
		return get_posts(array(
			'numberposts'		=> -1,
			'orderby'			=> 'menu_order',
			'order'				=> 'ASC',
			'post_type'			=> self::POST_TYPE,
			'post_status'		=> 'inherit',
			'post_parent'		=> $parent_id,
		));
	}
	
	public function isSliderAvailable($slug)
	{
		return (int) $this->wpdb->get_var($this->wpdb->prepare("SELECT COUNT(*) FROM {$this->wpdb->posts} WHERE post_type=%s AND post_name=%s", self::POST_TYPE, $slug));
	}
	
	public function addPredefinedSlider($slug, $config = array())
	{
		return wp_insert_post(array(
			'post_type'		=> self::POST_TYPE,
			'post_title'	=> $config['title'] ? $config['title'] : 'Unnamed Slider',
			'post_name'		=> $slug,
			'post_parent'	=> 0,
			'post_content'	=> '',
			'post_author'	=> 1,
			'comment_status'=> 'closed',
			'ping_status'	=> 'closed',
			'post_status'	=> 'private', // for predefined posts
			'menu_order'	=> 1,
			'post_date'		=> date('Y-m-d H:i:s'),
			'guid'          => '',
		));
	}
	
	public function getList()
	{
		foreach ($this->getConfigList() as $slug => $config) {
			if (!$this->isSliderAvailable($slug)) {
				$this->addPredefinedSlider($slug, $config);
			}
		}
		
		return get_posts(array(
			'numberposts'		=> -1,
			'orderby'			=> 'menu_order',
			'order'				=> 'ASC',
			'post_parent'		=> 0,
			'post_type'			=> self::POST_TYPE,
			'post_status'		=> array('private', 'publish'),
		));
	}
	
	public function getConfigList()
	{
		return aletheme_get_sliders();
	}
	
	public function shortcodeSliderInit($atts)
	{
		extract(shortcode_atts( array(
			'name'		=> '',
			'slideshow' => 0,
			'animation' => 'fade',
			'controlnav'=> 0,
			'randomize' => 0,
		), $atts));

		if (!$name) {
			return '';
		}
		
		$slider = ale_sliders_get_slider($name);
		if (!$slider) {
			return '';
		}
		
		$slider_id = sanitize_title('ale-slider-' . $slider['slug'] . '-' . wp_generate_password(5, false));

		?>
		<div id="<?php echo $slider_id ?>" class="ale-slider-slides cf">
			<div class="flexslider loading">
				<ul class="slides">
					<?php foreach ($slider['slides'] as $slide) : ?>
						<li>
							<?php if ($slide['html']): ?>
								<div class="html">
									<?php echo $slide['html']; ?>
								</div>
							<?php elseif ($slide['image']) : ?>
								<figure>
									<?php if ($slide['url']) : ?>
									<a href="<?php echo $slide['url'] ?>">
									<?php endif; ?>
									<img src="<?php echo $slide['image']?>" alt="<?php $slide['title'] ?>" />
									<?php if ($slide['url']) : ?>
										</a>
									<?php endif; ?>
								</figure>
								<?php if ($slide['title'] || $slide['description']) : ?>
									<div class="descr">
										<?php if ($slide['title']) : ?>
											<h3><?php echo $slide['title']; ?></h3>
										<?php endif; ?>
										<?php if ($slide['description']) : ?>
											<div class="text">
												<?php echo apply_filters('the_content', $slide['description']); ?>
											</div>
										<?php endif; ?>
									</div>
								<?php endif; ?>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
		<script type="text/javascript">
            jQuery(window).load(function(){
                jQuery('#<?php echo $slider_id ?> .flexslider').fitVids().flexslider({
					animation: "<?php echo $animation ?>",
					slideshow: <?php echo $slideshow ? 'true' : 'false' ?>,
					controlNav: <?php echo $controlnav ? 'true' : 'false' ?>,
					randomize: <?php echo $randomize ? 'true' : 'false' ?>,
					animationLoop: true,
					smoothHeight: true,
					multipleKeyboard:true,
					video:true,
					start: function(slider){
						slider.removeClass('loading');
					}
				});

			});
		</script>
		<?php
	}
	
	/**
	 * Adds a button near the media button on the top of the Wordpress editor
	 * @param string $page
	 * @param string $target 
	 */
	public function addMediaButton( $page = null, $target = null ) {
		echo '<a href="#TB_inline?width=640&height=600&inlineId=aletheme-slider-editor-shortcode-wrap" class="thickbox" title="' . __( 'Insert Slider', 'aletheme' ) . '" data-page="' . $page . '" data-target="' . $target . '"><img src="' . ALETHEME_URL . '/assets/images/aletheme_icon_16.png" alt="Insert Slider" /></a>';
	}
	
	
	/**
	 * Popup box for shortcodes
	 */
	public function editorPopup() {
		?>
		<div id="aletheme-slider-editor-shortcode-wrap">
			<div id="aletheme-slider-editor-shortcode">
				<div id="aletheme-slider-editor-shortcode-header">
					<select id="aletheme-slider-editor-shortcode-select">
						<option value=""><?php _e( 'Select Slider', 'shortcodes-ultimate' ); ?></option>
						<?php foreach ($this->getList() as $slider) : ?>
							<?php if ($slider->post_status == 'publish') : ?>
								<option value="<?php echo $slider->post_name ?>"><?php echo $slider->post_title ?></option>
							<?php endif; ?>
						<?php endforeach; ?>
					</select>
				</div>
				<div id="aletheme-slider-editor-shortcode-settings">
					<div id="aletheme-slider-info">

						<h2>Slider Settings</h2>
						<dl class="shortcode" data-slug="<?php echo $slider->post_name ?>">
							<dt>Slideshow</dt>
							<dd>
								<select name="slideshow">
									<option value="1" selected="selected">Yes</option>
									<option value="0">No</option>
								</select>
							</dd>

							<dt>Animation Type</dt>
							<dd>
								<select name="animation">
									<option value="fade" selected="selected">Fade</option>
									<option value="slide">Slide</option>
								</select>
							</dd>

							<dt>Control Navigation</dt>
							<dd>
								<select name="controlNav">
									<option value="1">Yes</option>
									<option value="0" selected="selected">No</option>
								</select>
							</dd>

							<dt>Randomize Slides</dt>
							<dd>
								<select name="randomize">
									<option value="1">Yes</option>
									<option value="0" selected="selected">No</option>
								</select>
							</dd>
							<dt>&nbsp;</dt>
							<dd><input type="button" id="aletheme-slider-editor-shortcode-insert" value="Insert Slider" class="button-primary aligncenter" /></dd>
						</dl>
						<div class="clear"></div>
						
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}

/**
 * Register Sliders post type to make it queriable 
 */
function aletheme_sliders_register_post_type() {
	ale_add_post_type(Aletheme_Sliders::POST_TYPE, array(
		'public' => false,
	), 'Aletheme Slider', 'Aletheme Sliders');
}
add_action( 'init', 'aletheme_sliders_register_post_type' );

/**
 * Initialize Sliders 
 */
$aletheme_sliders = new Aletheme_Sliders();
function aletheme_sliders_page() {
	global $aletheme_sliders;
	$aletheme_sliders->init();
}

/**
 * Find a specific slider by slug
 * @param string $slug
 * @return boolean|array 
 */
function ale_sliders_get_slider($slug) {
	
	$sliders = ale_sliders_get_sliders();
	foreach ($sliders as $s) {
		if ($s->post_name == $slug) {
			$slider = $s;
			break;
		}
	}
	
	if (!isset($slider)) {
		return false;
	}
	
	$data = array(
		'id'		=> $slider->ID,
		'title'		=> $slider->post_title,
		'slug'		=> $slider->post_name,
		'type'		=> $slider->post_status == 'private' ? 'predefined' : 'public',
		'slides'	=> array(),
	);
	
	$sliders_config = aletheme_get_sliders();
	
	$width = false;
	$height = false;
	if (isset($sliders_config[$slider->post_name]) && isset($sliders_config[$slider->post_name]['width']) && isset($sliders_config[$slider->post_name]['height'])) {
		$width = $sliders_config[$slider->post_name]['width'];
		$height = $sliders_config[$slider->post_name]['height'];
	}
	
	foreach (ale_sliders_get_slides($slider->ID) as $slide) {
		$data['slides'][] = array(
			'id'			=> $slide->ID,
			'image'			=> $slide->post_content_filtered,
			'title'			=> $slide->post_title,
			'url'			=> $slide->pinged,
			'description'	=> $slide->post_content,
			'html'			=> $slide->post_excerpt,
		);
	}
	
	return $data;
}

/**
 * Get sliders by slider ID
 * @param integer $slider_id
 * @return array 
 */
function ale_sliders_get_slides($slider_id) {
	return get_posts(array(
		'numberposts'		=> -1,
		'orderby'			=> 'menu_order',
		'order'				=> 'ASC',
		'post_type'			=> Aletheme_Sliders::POST_TYPE,
		'post_status'		=> 'inherit',
		'post_parent'		=> $slider_id,
	));
}

/**
 * Get sliders list
 * @return array
 */
function ale_sliders_get_sliders() {
	return get_posts(array(
		'numberposts'		=> -1,
		'post_parent'		=> 0,
		'post_type'			=> Aletheme_Sliders::POST_TYPE,
		'post_status'		=> array('private', 'publish'),
	));
}