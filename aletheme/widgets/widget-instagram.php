<?php
class Aletheme_Instagram_Widget extends WP_Widget 
{
	public $cache_key;

	public function __construct() 
	{
		/* Widget settings. */
		$widget_ops = array('classname' => 'aletheme-instagram', 'description' => __('Displays latest instagrams.', 'aletheme'));

		/* Widget control settings. */
		$control_ops = array('width' => 300, 'height' => 350);
		/* Create the widget. */
		$this->WP_Widget( 'Aletheme_Instagram_Widget-widget', 'Aletheme Instagram', $widget_ops, $control_ops);
		
		$this->cache_key = 'alethemeinstagramcache';
	}
	
	public function widget($args, $instance) {
		extract( $args);
		$title = apply_filters('widget_title', $instance['title']);
		$cacheduration = 3600;
		
		if(isset($instance['access_token'])) {
			$images = wp_cache_get($this->id, $this->cache_key);
			
			$imagesize = 100;
			
			if(false == $images) {
				$images = $this->instagram_get_latest($instance);
				wp_cache_set($this->id, $images, $this->cache_key, $cacheduration);
			}
			
			if(!empty($images)) {
				echo $before_widget;
				if ( $title ) echo $before_title . $title . $after_title;
				?>
				<ul class="aletheme-instagram-widget cf">
					<?php foreach ($images as $image) : ?>
						<li class="picture">
							<a href="<?php echo $image['link']; ?>" title="<?php echo $image['title'] ?>" rel="external">
								<img src="<?php echo $image['image_small'] ?>" alt="<?php echo $image['title'] ?>" />
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
				<?php
				echo $after_widget;
			}
		}
	}
	
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		if(isset($new_instance['dologout']) && $new_instance['dologout'] == 1) {
			$instance['access_token'] = null;
			$instance['login'] = null;
			wp_cache_delete($this->id, $this->cache_key);
		}

		if(isset($new_instance['login'], $new_instance['pass']) && trim($new_instance['login']) != "" && trim($new_instance['pass']) != "") {
			wp_cache_delete($this->id, 'wpinstagram_cache');
			$auth = $this->instagram_login($new_instance['login'], $new_instance['pass']);
			$instance['access_token'] = $auth->access_token;
			$instance['login'] = $auth->user->username;
		}
		
		if(($new_instance['count'] != $old_instance['count'])||($new_instance['hashtag'] != $instance['hashtag'])) {
			wp_cache_delete($this->id, $this->cache_key);
		}
		
		if(preg_match("/[a-zA-Z0-9_\-]+/i", $new_instance['hashtag'])) {
			$instance['hashtag'] = $new_instance['hashtag'];
		} else {
			unset($instance['hashtag']);
		}
		
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['count'] = strip_tags($new_instance['count']);
		$instance['count'] = intval($instance['count'])?$instance['count']:9;
		return $instance;
	}
	
	public function form($instance ) {
		$defaults = array(
			'title' => __('My instagrams', 'aletheme'),
			'login' => '',
			'pass' => '',
			'hashtag' => '',
			'dologout' => 0,
			'count' => 9,
		);
		$instance = wp_parse_args((array)$instance, $defaults);
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'aletheme'); ?></label>
			<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
		</p>
		<?php if(!isset($instance['access_token'])): ?>
			<p>
				<label for="<?php echo $this->get_field_id('login'); ?>"><?php _e('Instagram username:', 'aletheme'); ?></label>
				<input id="<?php echo $this->get_field_id('login'); ?>" name="<?php echo $this->get_field_name('login'); ?>" type="text" value="" class="widefat" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('pass'); ?>"><?php _e('Password:', 'aletheme'); ?></label>
				<input id="<?php echo $this->get_field_id('pass'); ?>" name="<?php echo $this->get_field_name('pass'); ?>" type="password" class="widefat" />
			</p>
		<?php else: ?>
			<p>
				<label for="<?php echo $this->get_field_id('hashtag'); ?>"><?php _e('Show latest public instagrams with following hashtag (without "#"; if empty, will show your recent instagrams):', 'aletheme'); ?></label>
				<input id="<?php echo $this->get_field_id('hashtag'); ?>" name="<?php echo $this->get_field_name('hashtag'); ?>" type="text" value="<?php echo $instance['hashtag'];?>" class="widefat" />
			</p>
			<p>
				<input type="hidden" value="0" name="<?php echo $this->get_field_name('dologout'); ?>" id="<?php echo $this->get_field_id('dologout'); ?>" />
				<label for="<?php echo $this->get_field_id('logoutbutton'); ?>"><?php _e('Logged in as: ', 'aletheme'); echo $instance['login']; ?></label>
				<a id="<?php echo $this->get_field_id('logoutbutton'); ?>" class="button-secondary"><?php _e('Logout from Instagram', 'aletheme'); ?></a>
				<script>
					jQuery(document).ready(function($){
						$("#<?php echo $this->get_field_id('logoutbutton'); ?>").click(function(){
							$("#<?php echo $this->get_field_id('dologout'); ?>").val("1");
							$(this).parents("form").find("input[type=submit]").click();
							return false;
						});
					});
				</script>
			</p>
		<?php endif; ?>
	<?php
	}
	
	function instagram_login($login, $pass){
		$response = wp_remote_post("https://api.instagram.com/oauth/access_token",
			array(
				'body' => array(
					'username' => $login,
					'password' => $pass,
					'grant_type' => 'password',
					'client_id' => '90c2afb9762041138b620eb56710ca39',
					'client_secret' => 'c605ec6443e348e68643470fdc3ef02a'
				),
				'sslverify' => apply_filters('https_local_ssl_verify', false)
			)
		);
		if(!is_wp_error($response) && $response['response']['code'] < 400 && $response['response']['code'] >= 200) {
			$auth = json_decode($response['body']);
			if(isset($auth->access_token)) {
				return $auth;
			} else {
				return null;
			}
		} else {
			return null;
		}
	}
	
	function instagram_get_latest($instance){
		$images = array();
		if($instance['access_token'] != null) {
			if(isset($instance['hashtag']) && trim($instance['hashtag']) != "" && preg_match("/[a-zA-Z0-9_\-]+/i", $instance['hashtag'])) {
				$apiurl = "https://api.instagram.com/v1/tags/".$instance['hashtag']."/media/recent?count=".$instance['count']."&access_token=".$instance['access_token'];
			} else {
				$apiurl = "https://api.instagram.com/v1/users/self/media/recent?count=".$instance['count']."&access_token=".$instance['access_token'];
			}
			$response = wp_remote_get($apiurl,
				array(
					'sslverify' => apply_filters('https_local_ssl_verify', false)
				)
			);
			if(!is_wp_error($response) && $response['response']['code'] < 400 && $response['response']['code'] >= 200) {
				$data = json_decode($response['body']);
				if($data->meta->code == 200) {
					foreach($data->data as $item) {
						if(isset($instance['hashtag'], $item->caption->text)) {
							$image_title = $item->user->username.': &quot;'.filter_var($item->caption->text, FILTER_SANITIZE_STRING).'&quot;';
						} elseif(isset($instance['hashtag']) && !isset($item->caption->text)) {
							$image_title = "instagram by ".$item->user->username;
						} else {
							$image_title = filter_var($item->caption->text, FILTER_SANITIZE_STRING);
						}
						$images[] = array(
							"link"	=> $item->link,
							"title" => $image_title,
							"image_small" => $item->images->thumbnail->url,
							"image_middle" => $item->images->low_resolution->url,
							"image_large" => $item->images->standard_resolution->url
						);
					}
				}
			}
		}
		return $images;
	}
}