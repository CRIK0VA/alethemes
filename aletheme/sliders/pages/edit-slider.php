<?php global $aletheme_sliders; ?>
<div class="wrap" id="aletheme-edit-slider-page" data-id="<?php echo (int) $aletheme_sliders->id ?>">
	<div id="icon-aletheme" class="icon32"><br/></div>
	<h2><?php echo $aletheme_sliders->id ? 'Edit' : 'Add New' ?> Slider</h2>
	
	<?php if (!$aletheme_sliders->slider) : ?>
		<form action="" method="post">
	<?php endif;?>
	
	<div id="aletheme-edit-slider-wrapper" class="metabox-holder aletheme-slider-edit">
		<p class="alignleft"><a href="<?php echo Aletheme_Sliders::slidersUrl() ?>">Return to Sliders Listing</a></p>
		<?php if ($aletheme_sliders->slider && $aletheme_sliders->slider->post_status == 'publish') : ?>
			<p class="alignright submitbox"><a href="<?php echo Aletheme_Sliders::slidersUrl(array('action' => 'delete', 'id' => $aletheme_sliders->slider->ID, '_wpnonce' => wp_create_nonce('aletheme_slider_delete_nonce'))) ?>" id="aletheme-slider-delete" class="submitdelete">Delete Slider</a></p>
		<?php endif; ?>
		
		<div class="clear"></div>
		
		<div id="titlediv">
			<div id="titlewrap">						
				<input type="text" name="title" size="40" maxlength="255" placeholder="Type slider name here" required="required" id="title" value="<?php if(isset($aletheme_sliders->slider->post_title)){ echo $aletheme_sliders->slider->post_title; } ?>" />
			</div>
		</div>
		<?php if ($aletheme_sliders->slider) : ?>
			<?php $slider = $aletheme_sliders->slider; ?>
		
			<?php if ($slider->post_status == 'publish') : ?>
				<div id="aletheme-slider-info-hide"></div>
				<div class="clear"></div>
				<div id="aletheme-slider-info">
					
					<h2>Slider Shortcode</h2>
					<dl class="shortcode" data-slug="<?php echo $slider->post_name ?>">
						<dt>Slider Slug</dt>
						<dd><?php echo $slider->post_name ?></dd>

						<dt>Slider Shortcode</dt>
						<dd><cite><input type="text" name="shortcode" id="aletheme-slider-shortcode" /></cite></dd>
					</dl>
					<div class="clear"></div>
				</div>
			<?php endif; ?>
		
			<div id="aletheme-slides-sortable" class="meta-box-sortables ui-sortable">
					<?php foreach ($aletheme_sliders->getSlides($slider->ID) as $k => $slide) : ?>
						<div class="slide" id="aletheme-slide-<?php echo $k ?>" data-id="<?php echo $slide->ID ?>">
							<div class="handle" title="Click and drag to reorder">SORT</div>
							<a href="#" class="delete">Delete</a>
							<div class="box-image">
								<div class="image"><img /></div>
								<span><input type="text" name="image" placeholder="Or enter an image URL" value="<?php echo $slide->post_content_filtered ?>" /></span>
							</div>
							<div class="box-content">
								<div class="box-title"><span>Title</span><input type="text" value="<?php echo $slide->post_title ?>" name="title" /></div>
								<div class="box-url"><span>URL</span><input type="text" value="<?php echo $slide->pinged ?>" name="url" /></div>
								<div class="box-description"><span>Description</span><textarea cols="30" rows="4" name="description" class="description"><?php echo $slide->post_content ?></textarea></div>
								<div class="box-html"><span>HTML</span><textarea cols="30" rows="4" name="html" class="html"><?php echo $slide->post_excerpt ?></textarea></div>
							</div>
						</div>
					<?php endforeach; ?>
			</div>
			<p class="alignleft"><a id="aletheme-add-new-slide-button" class="button-secondary button80" href="#">Add a New Slide</a></p>
			<p class="alignright" id="aletheme-save-slider-container">
				<img src="<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ); ?>" class="ajax-loading" id="aletheme-sliders-loading" alt="">
				<a class="button-primary button80" id="aletheme-save-slider-button" href="#">Save Slider</a>
			</p>
		<?php else: ?>
			<p class="alignright">
				<a class="button-primary button80" href="#" id="aletheme-create-slider">Create Slider</a>
			</p>			
		<?php endif; ?>
		<div class="clear"></div>
		<?php if (!$aletheme_sliders->slider) : ?>
			</form>
		<?php endif;?>
	</div>
</div>