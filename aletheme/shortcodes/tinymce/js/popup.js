
// start the popup specefic scripts
// safe to use $
jQuery(document).ready(function($) {
    var ales = {
    	loadVals: function()
    	{
    		var shortcode = $('#_ale_shortcode').text(),
    			uShortcode = shortcode;
    		
    		// fill in the gaps eg {{param}}
    		$('.ale-input').each(function() {
    			var input = $(this),
    				id = input.attr('id'),
    				id = id.replace('ale_', ''),		// gets rid of the ale_ prefix
    				re = new RegExp("{{"+id+"}}","g");
    				
    			uShortcode = uShortcode.replace(re, input.val());
    		});
    		
    		// adds the filled-in shortcode as hidden input
    		$('#_ale_ushortcode').remove();
    		$('#ale-sc-form-table').prepend('<div id="_ale_ushortcode" class="hidden">' + uShortcode + '</div>');
    	},
    	cLoadVals: function()
    	{
    		var shortcode = $('#_ale_cshortcode').text(),
    			pShortcode = '';
    			shortcodes = '';
    		
    		// fill in the gaps eg {{param}}
    		$('.child-clone-row').each(function() {
    			var row = $(this),
    				rShortcode = shortcode;
    			
    			$('.ale-cinput', this).each(function() {
    				var input = $(this),
    					id = input.attr('id'),
    					id = id.replace('ale_', '')		// gets rid of the ale_ prefix
    					re = new RegExp("{{"+id+"}}","g");
    					
    				rShortcode = rShortcode.replace(re, input.val());
    			});
    	
    			shortcodes = shortcodes + rShortcode + "\n";
    		});
    		
    		// adds the filled-in shortcode as hidden input
    		$('#_ale_cshortcodes').remove();
    		$('.child-clone-rows').prepend('<div id="_ale_cshortcodes" class="hidden">' + shortcodes + '</div>');
    		
    		// add to parent shortcode
    		this.loadVals();
    		pShortcode = $('#_ale_ushortcode').text().replace('{{child_shortcode}}', shortcodes);
    		
    		// add updated parent shortcode
    		$('#_ale_ushortcode').remove();
    		$('#ale-sc-form-table').prepend('<div id="_ale_ushortcode" class="hidden">' + pShortcode + '</div>');
    	},
    	children: function()
    	{
    		// assign the cloning plugin
    		$('.child-clone-rows').appendo({
    			subSelect: '> div.child-clone-row:last-child',
    			allowDelete: false,
    			focusFirst: false
    		});
    		
    		// remove button
    		$('.child-clone-row-remove').live('click', function() {
    			var	btn = $(this),
    				row = btn.parent();
    			
    			if( $('.child-clone-row').size() > 1 )
    			{
    				row.remove();
    			}
    			else
    			{
    				alert('You need a minimum of one row');
    			}
    			
    			return false;
    		});
    		
    		// assign jUI sortable
    		$( ".child-clone-rows" ).sortable({
				placeholder: "sortable-placeholder",
				items: '.child-clone-row'
				
			});
    	},
    	resizeTB: function()
    	{
			var	ajaxCont = $('#TB_ajaxContent'),
				tbWindow = $('#TB_window'),
				alePopup = $('#ale-popup');

            tbWindow.css({
                height: alePopup.outerHeight() + 50,
                width: alePopup.outerWidth(),
                marginLeft: -(alePopup.outerWidth()/2)
            });

			ajaxCont.css({
				paddingTop: 0,
				paddingLeft: 0,
				paddingRight: 0,
				height: (tbWindow.outerHeight()-47),
				overflow: 'auto', // IMPORTANT
				width: alePopup.outerWidth()
			});
			
			$('#ale-popup').addClass('no_preview');
    	},
    	load: function()
    	{
    		var	ales = this,
    			popup = $('#ale-popup'),
    			form = $('#ale-sc-form', popup),
    			shortcode = $('#_ale_shortcode', form).text(),
    			popupType = $('#_ale_popup', form).text(),
    			uShortcode = '';
    		
    		// resize TB
    		ales.resizeTB();
    		$(window).resize(function() { ales.resizeTB() });
    		
    		// initialise
    		ales.loadVals();
    		ales.children();
    		ales.cLoadVals();
    		
    		// update on children value change
    		$('.ale-cinput', form).live('change', function() {
    			ales.cLoadVals();
    		});
    		
    		// update on value change
    		$('.ale-input', form).change(function() {
    			ales.loadVals();
    		});
    		
    		// when insert is clicked
    		$('.ale-insert', form).click(function() {
                if(parent.tinymce)
                {
                    parent.tinymce.activeEditor.execCommand('mceInsertContent',false,$('#_ale_ushortcode', form).html());
                    tb_remove();
                }
    		});
    	}
	}
    
    // run
    $('#ale-popup').livequery( function() { ales.load(); } );
});