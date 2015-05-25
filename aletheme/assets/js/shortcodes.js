jQuery(document).ready(function ($) {
	$.fn.aletheme_sliders_editor_shortcode = function() {
		$(this).each(function(){
			var select = $('#aletheme-slider-editor-shortcode-select');
			var settings = $('#aletheme-slider-editor-shortcode-settings');
			settings.hide();
			select.change(function(){
				if ('' == select.val()) {
					settings.hide();
					return;
				}
				settings.show();
			});
			$('#aletheme-slider-editor-shortcode-insert').click(function(e){
				e.preventDefault();
				window.send_to_editor(aletheme_generate_slider_shortcode(select.val(), settings.find('select')));
			});
		});		
	}
	$('#aletheme-slider-editor-shortcode-wrap').aletheme_sliders_editor_shortcode();
});