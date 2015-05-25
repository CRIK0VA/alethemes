<?php
/**
 * Blog Widget 
 */
class Aletheme_About_Widget extends WP_Widget 
{
	public $image_field = 'image';
	
	/**
	 * General Setup 
	 */
	public function __construct() {
	
		/* Widget settings. */
		$widget_ops = array(
			'classname' => 'ale_about_widget', 
			'description' => __('A widget that displays a short information about you.', 'aletheme') 
		);

		/* Widget control settings. */
		$control_ops = array(
			'width'		=> 500, 
			'height'	=> 450, 
			'id_base'	=> 'ale_about_widget' 
		);

		/* Create the widget. */
		$this->WP_Widget( 'ale_about_widget', __('Aletheme About Me', 'aletheme'), $widget_ops, $control_ops );
	}

	/**
	 * Display Widget
	 * @param array $args
	 * @param array $instance 
	 */
	public function widget( $args, $instance ) 
	{
		extract( $args );
		
		$title = apply_filters('widget_title', $instance['title'] );
		
		$text = apply_filters('the_content', $instance['text']);
		
		/* Our variables from the widget settings. */
		$image_id = $instance[$this->image_field];
		
		$image = new Aletheme_WidgetImageField( $this, $image_id );
		
		/* Before widget (defined by themes). */
		echo $before_widget;
		
		// Display Widget
		?> 
        <?php /* Display the widget title if one was input (before and after defined by themes). */
				if ( $title )
					echo $before_title . $title . $after_title;
				?>
			<div class="aletheme-about-widget">
				<?php if( !empty( $image_id ) ) : ?>
					<figure>
						<img src="<?php echo $image->get_image_src(); ?>" alt="<?php echo $title ?>" />
					</figure>
				<?php endif; ?>
				<div class="text">
					<?php echo $text; ?>
				</div>	
            </div>
		<?php
		/* After widget (defined by themes). */
		echo $after_widget;
	}

	/**
	 * Update Widget
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array 
	 */
	public function update( $new_instance, $old_instance ) 
	{
		$instance = $old_instance;
		
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['text'] = strip_tags( $new_instance['text'] );
		
		$instance[$this->image_field] = (int) $new_instance[$this->image_field];

		return $instance;
	}
	
	/**
	 * Widget Settings
	 * @param array $instance 
	 */
	public function form( $instance ) 
	{
		//default widget settings.
		$defaults = array(
			'title'		=> __('About Me', 'aletheme'),
			'text'		=> "",
			'image'		=> "",
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		
		$image_id   = isset( $instance[$this->image_field]) ? (int) $instance[$this->image_field] : 0;
		$image      = new Aletheme_WidgetImageField( $this, $image_id );
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'aletheme') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label>Image: </label>
			<?php echo $image->get_widget_field(); ?>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e('Posts to show:', 'aletheme') ?></label>
			<textarea class="widefat" cols="100" rows="5" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" ><?php echo $instance['text']; ?></textarea>
		</p>
	<?php
	}
}