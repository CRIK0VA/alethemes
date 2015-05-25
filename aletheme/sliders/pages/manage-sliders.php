<?php global $aletheme_sliders; ?>
<div class="wrap" id="aletheme-sliders-page">
	<div id="icon-aletheme" class="icon32"><br/></div>
	<h2>Sliders</h2>
	<div id="aletheme-manage-sliders-wrapper">
		<div id="aletheme-create-slider-container">
			<h3>Manage Sliders</h3>
			<a class="button-primary button80 alignright" id="aletheme-add-slider-button" href="<?php echo Aletheme_Sliders::slidersUrl(array('action' => 'create')) ?>">Create New Slider</a>
			<div class="clear"></div>
		</div>
		<div id="aletheme-manage-sliders">		
			<ul>
				<?php foreach ($aletheme_sliders->getList() as $slider) : ?>
					<?php
						$slide = $aletheme_sliders->getFirstSlide($slider->ID);
					?>
					<li>
						<a href="<?php echo $aletheme_sliders->slidersUrl(array('action' => 'edit', 'id' => $slider->ID)) ?>">
							<span class="image">
								<?php if ($slide) : ?>
									<img src="<?php echo $slide->post_content_filtered ?>" alt="<?php echo $slider->post_title; ?>" />
								<?php endif; ?>
							</span>
							<span class="title"><?php echo $slider->post_title; ?></span>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
			<div class="clear"></div>
		</div>
	</div>
</div>