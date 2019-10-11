<?php
/**
 * Blog Widget 
 */
class Aletheme_Blog_Widget extends WP_Widget 
{
	/**
	 * General Setup 
	 */
	public function __construct() {
	
		/* Widget settings. */
		$widget_ops = array(
			'classname' => 'ale_blog_widget', 
			'description' => __('A widget that displays your latest posts with image.', 'aletheme') 
		);

		/* Widget control settings. */
		$control_ops = array(
			'width'		=> 300, 
			'height'	=> 350, 
			'id_base'	=> 'ale_blog_widget' 
		);

		/* Create the widget. */
		parent::__construct( 'ale_blog_widget', __('Aletheme Recent Posts', 'aletheme'), $widget_ops, $control_ops );
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

		/* Our variables from the widget settings. */
		$number = $instance['number'];

		/* Before widget (defined by themes). */
		echo $before_widget;

		// Display Widget
		?> 
        <?php /* Display the widget title if one was input (before and after defined by themes). */
				if ( $title )
					echo $before_title . $title . $after_title;
				?>
			<div class="aletheme-blog-widget">
                <div class="cf">
                
					<?php 
                    $query = new WP_Query(array(
						'posts_per_page'		=> $number,
						'ignore_sticky_posts'	=> 1,
					));
                    ?>
                    <?php if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post(); ?>
						<?php  $image = ale_get_og_meta_image(); ?>
						<?php if ($image) : /* if post has post thumbnail */ ?>
						<div class="cf">
							<div class="entry-thumb"><a href="<?php the_permalink(); ?>"><img src="<?php echo $image?>" alt="" /></a></div>
						<?php else:?>
							<div class="cf no-thumb">
						<?php endif; ?>
							<div class="detail">
								<h4 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
								<span class="entry-meta"><?php the_time(get_option('date_format')); ?></span>
							</div>
						</div>
                    <?php endwhile; endif; ?>
                    
                    <?php wp_reset_query(); ?>

                </div>
                
            </div><!--blog_widget-->
		
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
		$instance['number'] = strip_tags( $new_instance['number'] );

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
			'title' => __('Latest Blog Posts', 'aletheme'),
			'number' => 4
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'aletheme') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e('Posts to show:', 'aletheme') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo $instance['number']; ?>" />
		</p>
	<?php
	}
}