<?php
class Aletheme_Flickr_Widget extends WP_Widget 
{
	public $cache_key;
	
	public function __construct() 
	{
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'aletheme-flickr', 'description' => 'Display a Flickr Photostream' );

		/* Widget control settings. */
		$control_ops = array( 'width' => 500);
		/* Create the widget. */
		$this->WP_Widget( 'Aletheme_Flickr_Widget-widget', 'Aletheme Flickr', $widget_ops, $control_ops);
		
		$this->cache_key = 'alethemeflickrcache';
	}
	
	public function widget($args, $instance) 
	{
		extract($args);

		$title = apply_filters('widget_title', $instance['title'] );
		$do_cache = $instance['do_cache'];
		$num_items = $instance['num_items'];
		
		$pix = wp_cache_get($this->cache_key);
		
		if($do_cache && $pix) {
			
		} else {
			$rss = $this->getRSS($instance);
			
			if (!$rss) {
				echo '<p>No content found</p>';
				return;
			}
			
			$pix = array();

			if($num_items != "random") {
				$items = array_slice($rss['items'], 0, $num_items);
			} else {
				$rand_keys = array_rand($rss['items'], 1);
				$items = array($rss['items'][$rand_keys]);
			}
			
			
			# builds html from array
			foreach ( $items as $item ) {
				$baseurl = str_replace("_m.jpg", "", $item["m_url"]);
				$thumbnails = array(
					'small' => $baseurl . "_m.jpg",
					'square' => $baseurl . "_s.jpg",
					'thumbnail' => $baseurl . "_t.jpg",
					'medium' => $baseurl . ".jpg",
					'large' => $baseurl . "_b.jpg"
				);
				
				#check if there is an image title (for html validation purposes)
				if($item['title'] !== "") {
					$pic_title = htmlspecialchars(stripslashes($item['title']));
				} else {
					$pic_title = 'Untitled Image';
				}
				
				$pix[] = array(
					'title'			=> $pic_title,
					'author_name'	=> $item['author_name'],
					'author_url'	=> $item['author_url'],
					'thumb'			=> $thumbnails['square'],
					'full'			=> $thumbnails['large'],
					'url'			=> $item['url'],
				);
			}
			
			if ($do_cache) {
				wp_cache_set($this->cache_key, $pix);
			}
		}
		
		echo $before_widget;
		if ( $title ) echo $before_title . $title . $after_title;
		?>
		<div class="aletheme-flickr-widget cf">
			<?php foreach ($pix as $item) : ?>
				<div class="picture">
					<a href="<?php echo $item['url']?>" title="<?php echo $item['title']?> <?php _e('by', 'aletheme')?> <?php echo $item['author_name']?>" rel="external">
						<img src="<?php echo $item['thumb']?>" alt="<?php echo $item['title']?> <?php _e('by', 'aletheme')?> <?php echo $item['author_name']?> " />
					</a>
				</div>
			<?php endforeach; ?>
            <div class="cf"></div>
		</div>
		<?php
		echo $after_widget;
		return;
	}
	
	public function update($new_instance, $old_instance) 
	{
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['type'] = strip_tags( $new_instance['type']);
		$instance['tags'] = strip_tags( $new_instance['tags']);
		$instance['set'] = strip_tags( $new_instance['set']);
		$instance['id'] = strip_tags( $new_instance['id']);
		$instance['do_cache'] = strip_tags( $new_instance['do_cache']);
		$instance['num_items'] = strip_tags( $new_instance['num_items']);
		
		wp_cache_delete($this->cache_key);

		return $instance;
	}
	
	public function form($instance) 
	{
		$defaults = array(
			'title' => 'Flickr Photos',
			'type' => 'public',
			'tags' => '',
			'set' => '',
			'id' => '',
			'do_cache' => false,
			'num_items' => 9,
		);

		$instance = wp_parse_args( (array) $instance, $defaults );
		?>

			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'num_items' ); ?>">Display</label>
				<select name="<?php echo $this->get_field_name( 'num_items' ); ?>" id="<?php echo $this->get_field_id( 'num_items' ); ?>">
					<option <?php if ($instance['num_items'] == "random") { echo 'selected'; } ?> value="random">Random (1)</option>
					<?php for ($i=1; $i<=20; $i++) { ?>
						<option <?php if ($instance['num_items'] == $i) { echo 'selected'; } ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
					<?php } ?>
				</select>
				<select name="<?php echo $this->get_field_name( 'type' ); ?>" id="<?php echo $this->get_field_id( 'type' ); ?>">
					<option <?php if($instance['type'] == 'user') { echo 'selected'; } ?> value="user">user</option>
					<option <?php if($instance['type'] == 'set') { echo 'selected'; } ?> value="set">set</option>
					<option <?php if($instance['type'] == 'favorite') { echo 'selected'; } ?> value="favorite">favorite</option>
					<option <?php if($instance['type'] == 'group') { echo 'selected'; } ?> value="group">group</option>
					<option <?php if($instance['type'] == 'public') { echo 'selected'; } ?> value="public">community</option>
				</select>
				photos.
			</p>
			
			<p class="id_parent">
				<label for="<?php echo $this->get_field_id( 'id' ); ?>">User or Group ID</label>
				<input name="<?php echo $this->get_field_name( 'id' ); ?>" type="text" id="<?php echo $this->get_field_id( 'id' ); ?>" value="<?php echo $instance['id']; ?>" size="20" />
			</p>
			<p class="set_parent">
				<label for="<?php echo $this->get_field_id( 'set' ); ?>">Set ID</label>
				<input name="<?php echo $this->get_field_name( 'set' ); ?>" type="text" id="<?php echo $this->get_field_id( 'set' ); ?>" value="<?php echo $instance['set']; ?>" size="40" />
				<small>Use number from the set url</small>
			</p>
			<p class="tags_parent">
				<label for="<?php echo $this->get_field_id( 'tags' ); ?>">Tags (optional)</label>
				<input class="widefat" name="<?php echo $this->get_field_name( 'tags' ); ?>" type="text" id="<?php echo $this->get_field_id( 'tags' ); ?>" value="<?php echo $instance['tags']; ?>" size="40" />
				<small>Comma separated, no spaces</small>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'do_cache' ); ?>">Turn on caching:</label>
				<input name="<?php echo $this->get_field_name( 'do_cache' ); ?>" type="checkbox" id="<?php echo $this->get_field_id( 'do_cache' ); ?>" <?php echo $instance['do_cache']==1?'checked="checked"':''; ?> value="1" />
			</p>
		<?php
	}
	
	public function getRSS($instance)
	{
		$format = "php_serial";
		
		if ($instance['type'] == "user") {
			$rss_url = 'http://api.flickr.com/services/feeds/photos_public.gne?id=' . $instance['id'] . '&tags=' . $instance['tags'] . '&format=' . $format;
		} elseif ($instance['type'] == "favorite") {
			$rss_url = 'http://api.flickr.com/services/feeds/photos_faves.gne?id=' . $instance['id'] . '&format=' . $format;
		} elseif ($instance['type'] == "set") {
			$rss_url = 'http://api.flickr.com/services/feeds/photoset.gne?set=' . $instance['set'] . '&nsid=' . $instance['id'] . '&format=' . $format;
		} elseif ($instance['type'] == "group") {
			$rss_url = 'http://api.flickr.com/services/feeds/groups_pool.gne?id=' . $instance['id'] . '&format=' . $format;
		} elseif ($instance['type'] == "public" || $instance['type'] == "community") {
			$rss_url = 'http://api.flickr.com/services/feeds/photos_public.gne?tags=' . $instance['tags'] . '&format=' . $format;
		} else {
			print '<strong>No "type" parameter has been setup. Check your settings, or provide the parameter as an argument.</strong>';
			die();
		}
		
		$result = wp_remote_get($rss_url);
		
		if (!isset($result['body'])) {
			return false;
		}
		
		$response = unserialize($result['body']);
		
		return $response;
	}
}