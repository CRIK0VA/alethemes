<?php

// loads the shortcodes class, wordpress is loaded with it
require_once 'shortcodes.class.php';

// get popup type
$popup = trim( $_GET['popup'] );
$shortcode = new ale_shortcodes( $popup );

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head></head>
<body>
<div id="ale-popup">

	<div id="ale-shortcode-wrap">
		
		<div id="ale-sc-form-wrap">
		
			<div id="ale-sc-form-head">
			
				<?php echo $shortcode->popup_title; ?>
			
			</div>
			<!-- /#ale-sc-form-head -->
			
			<form method="post" id="ale-sc-form">
			
				<table id="ale-sc-form-table">
				
					<?php echo $shortcode->output; ?>
					
					<tbody>
						<tr class="form-row">
							<?php if( ! $shortcode->has_child ) : ?><td class="label">&nbsp;</td><?php endif; ?>
							<td class="field"><a href="#" class="button-primary ale-insert">Insert Shortcode</a></td>							
						</tr>
					</tbody>
				
				</table>
				<!-- /#ale-sc-form-table -->
				
			</form>
			<!-- /#ale-sc-form -->
		
		</div>
		<!-- /#ale-sc-form-wrap -->
		
		<div class="clear"></div>
		
	</div>
	<!-- /#ale-shortcode-wrap -->

</div>
<!-- /#ale-popup -->

</body>
</html>