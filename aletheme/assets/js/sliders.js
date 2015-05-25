jQuery(document).ready(function ($) {

	function get_thumb_url(url, width, height) {
		return aletheme_sliders_js.timurl + '?src=' + url  +'&w=' + width + '&h=' + height + '&q=100';
	}

	$.fn.aletheme_edit_slider = function(){
		function create_slide_item(k) {
			return $(
				'<div class="slide" id="aletheme-slide-' + k + ' " data-id="0">' + 
				'<div class="handle" title="Click and drag to reorder">SORT</div>' + 
				'<a href="#" class="delete">Delete</a>' + 
				'<div class="box-image"><div class="image"><img /></div><span><input type="text" name="image" placeholder="Or enter an image URL" /></span></div>' + 
				'<div class="box-content">' +
				'<div class="box-title"><span>Title</span><input type="text" value="" name="title" /></div>' + 
				'<div class="box-url"><span>URL</span><input type="text" value="" name="url" /></div>' + 
				'<div class="box-description"><span>Description</span><textarea cols="30" rows="4" name="description" class="description"></textarea></div>' + 
				'<div class="box-html"><span>HTML</span><textarea cols="30" rows="4" name="html" class="html"></textarea></div>' + 
				'</div></div>'
			);
		}
		
		function ajax_error() {
			alert('Something went wrong. Please reload the page and try again.');
		}
		
		function build_save_json(entries) {
			var data = [];
			entries.each(function(){
				var entry = $(this);
				data.push({
					'id'			: entry.data('id'),
					'image'			: entry.find('input[name=image]').val(),
					'title'			: entry.find('input[name=title]').val(),
					'url'			: entry.find('input[name=url]').val(),
					'description'	: entry.find('textarea[name=description]').val(),
					'html'			: entry.find('textarea[name=html]').val()
				});
			});
			return data;
		}
		
		function add_slide_events(slide) {
			slide.find('.image').click( function(e) {				
				var field = slide.find('input[name="image"]');
				tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');				
				$("#TB_window,#TB_overlay,#TB_HideSelect").one("unload",function(e){
					e.stopPropagation();
					e.stopImmediatePropagation();
					return false;
				});
				window.send_to_editor = function(html) {
					tb_remove();
					html = $(html);
					var source = '';
					if (html.is('img')) {
						source = html.prop('src');
					} else {
						source = jQuery('img', html).prop('src');
					}
					field.val(source).triggerHandler("change");
				};
			});
			
			slide.find("input[name='image']").bind('change', function(e, triggered){
				var self = $(this);
				var path = self.val();
				if(path) {
					var thumbnail_image = path;//get_thumb_url(path, w, h);
					$('<img />').load(function(){
						slide.find('.image').addClass('uploaded').find('img').attr('src', this.src);
					}).error(function(){
						self.addClass("err");
						slide.find('.image').removeClass('uploaded').find('img').attr('src', aletheme_sliders_js.assetsurl + '/images/blank.gif');						
					}).attr('src', thumbnail_image);
				} else {
					slide.find('.image').removeClass('uploaded').find('img').attr('src', aletheme_sliders_js.assetsurl + '/images/blank.gif');
				}
			}).triggerHandler('change', true);
			
			slide.find('a.delete').click(function(e){
				e.preventDefault();
				if (!confirm('Delete this entry?')) {
					return;
				}
				var link = $(this);
				var slide = link.parent();
				var id = slide.data('id');
				
				if (!id) {
					slide.fadeOut(function(){
						$(this).remove();
					});
					return;
				}
				
				$.post(aletheme_sliders_js.ajaxurl, {
					'action':'delete_slide',
					'id':id
				}, function(data){
					if (data.state == 'success') {
						slide.fadeOut(function(){
							$(this).remove();
						});
					} else {
						ajax_error();
					}
				}, 'json');
			})
		}
		
		$(this).each(function(){
			var slider = $(this);
			
			var entries = $('#aletheme-slides-sortable');
			
			entries.sortable({
				placeholder: 'sortable-placeholder',
				items: '.slide',
				handle: '.handle',
				cursor: 'move',
				distance: 2,
				tolerance: 'pointer',
				helper: 'clone',
				forcePlaceholderSize: true,			
				opacity: 0.7,
				change:function(){}
			});
			
			entries.find('.slide').each(function() {
				add_slide_events($(this));
			});
			
			$('#aletheme-add-new-slide-button').click(function(e){
				e.preventDefault();
				var slide = create_slide_item(entries.find('.slide').length);
				entries.append(slide);
				add_slide_events(slide);
			});
			
			$('#aletheme-save-slider-button').click(function(e){
				e.preventDefault();
				var button = $(this);
				var slides = entries.find('.slide');
				var entries_data = build_save_json(slides);
				var id = slider.data('id');
				var loading = $('#aletheme-sliders-loading');
				
				button.addClass('button-primary-disabled');
				loading.css('visibility', 'visible');
				
				$.post(aletheme_sliders_js.ajaxurl, {
					'action':'save_slider',
					'id':id,
					'title':$('#titlewrap input').val(),
					'entries':entries_data
				}, function(data){
					button.removeClass('button-primary-disabled');
					loading.css('visibility', 'hidden');
					
					slider.data('id', data.id);
					slides.each(function(k){
						$(this).data('id', data.slides[k]);
					});
				}, 'json');
			});
			
			$('#aletheme-create-slider').click(function(e){
				$(this).parents('form').submit();
			});
			
			$('#aletheme-slider-delete').click(function(e){
				if (confirm('Are you sure you want to remove this slider?')) {
					return true;
				} else {
					return false;
				}
			});
			
			var shortcode = $('#aletheme-slider-info .shortcode');
			
			if (shortcode.length) {
				var shortcode_input = $('#aletheme-slider-shortcode');
				var generate_shortcode = function() {
					shortcode_input.val(aletheme_generate_slider_shortcode(shortcode.data('slug'), shortcode.find('select')));
				}
				shortcode.find('select').change(generate_shortcode);
				shortcode_input.click(function(){
					$(this).select();
				});
				generate_shortcode();
			}
			
			var hide_area = $('#aletheme-slider-info-hide');
			if (hide_area.length) {
				var hide_area_visible;
				if (typeof(localStorage) != 'undefined' ) {
					hide_area_visible = localStorage.getItem("ale-slider-hide-area-visibile");
				}
				if (hide_area_visible == null) {
					hide_area_visible = 1;
					if (typeof(localStorage) != 'undefined' ) {
						localStorage.setItem("ale-slider-hide-area-visibile", hide_area_visible);
					}
				}
				hide_area_visible = parseInt(hide_area_visible);
				var hide_link = $('<a href="#">' + (hide_area_visible ? 'Hide' : 'Show') + ' Shortcode Generator</a>');
				if (!hide_area_visible) {
					$('#aletheme-slider-info').hide();
				}
				hide_area.prepend(hide_link);
				hide_link.click(function(e){
					e.preventDefault();
					$('#aletheme-slider-info').slideToggle('fast', function(){
						var info = $(this);
						hide_link.text(info.is(':visible') ? 'Hide Shortcode Generator' : 'Show Shortcode Generator');
						if (typeof(localStorage) != 'undefined' ) {
							localStorage.setItem("ale-slider-hide-area-visibile", info.is(':visible') ? 1 : 0);
						}
					})
				})
			}
		});
	}
	$('#aletheme-edit-slider-page').aletheme_edit_slider();
	
	$.fn.aletheme_manage_sliders = function(){
		$(this).each(function(){
			
		});
	}
});