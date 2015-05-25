var iti_widget_image_context = false;

function iti_widget_image_return(iti_widget_image_id,iti_widget_image_thumb){
    // show our image for reference
    iti_widget_image_context.find('img').remove();
    iti_widget_image_context.append('<img src="' + iti_widget_image_thumb + '" alt="Image" />');

    // save our image id
    iti_widget_image_context.find('input').val(iti_widget_image_id);
}

function iti_widget_image_update_thickbox(){
    if(iti_widget_image_context){

        // need to add our own button
        if(jQuery('#TB_iframeContent').contents().find('td.savesend').length){
            jQuery('#TB_iframeContent').contents().find('td.savesend').each(function(){
                if(jQuery(this).find('input.iti-widget-image-choose').length==0){
                    jQuery(this).find('input').hide();
                    jQuery(this).prepend('<input type="submit" name="itiwidgetimagechoose" class="iti-widget-image-choose button" value="Use this image" />');
                }
            });
        }

        // need to handle the click event
        jQuery('#TB_iframeContent').contents().find('td.savesend input.iti-widget-image-choose').unbind('click').click(function(e){
            e.preventDefault();
            iti_widget_image_parent = jQuery(this).parent().parent().parent();
            iti_widget_image_id = iti_widget_image_parent.find('td.imgedit-response').attr('id').replace('imgedit-response-','');
            iti_widget_image_thumb = iti_widget_image_parent.parent().parent().find('img.pinkynail').attr('src');
            iti_widget_image_ref = iti_widget_image_parent.clone();

            iti_widget_image_return(iti_widget_image_id,iti_widget_image_thumb);

            // close everything and wrap up
            iti_widget_image_context = false;
            tb_remove();
        });

        // update button
        if(jQuery('#TB_iframeContent').contents().find('.media-item .savesend input[type=submit], #insertonlybutton').length){
            jQuery('#TB_iframeContent').contents().find('.media-item .savesend input[type=submit], #insertonlybutton').val('Use this image');
        }
        if(jQuery('#TB_iframeContent').contents().find('#tab-type_url').length){
            jQuery('#TB_iframeContent').contents().find('#tab-type_url').hide();
        }
        if(jQuery('#TB_iframeContent').contents().find('tr.post_title').length){
            // we need to ALWAYS get the fullsize since we're retrieving the guid
            // if the user inserts an image somewhere else and chooses another size, everything breaks
            jQuery('#TB_iframeContent').contents().find('tr.image-size input[value="full"]').prop('checked', true);
            jQuery('#TB_iframeContent').contents().find('tr.post_title,tr.image_alt,tr.post_excerpt,tr.image-size,tr.post_content,tr.url,tr.align,tr.submit>td>a.del-link').hide();
        }
    }

    if(jQuery('#TB_iframeContent').contents().length==0&&iti_widget_image_context){
        // the thickbox was closed
        clearInterval(iti_widget_image_thickbox_updater);
        iti_widget_image_context = false;
    }
}

jQuery(document).ready(function(){
    jQuery('.widgets-holder-wrap').on('click', 'a.iti-image-widget-trigger', function(e){
        e.preventDefault();
        var href = jQuery(this).attr('href'), width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
        if ( ! href ) return;
        href = href.replace(/&width=[0-9]+/g, '');
        href = href.replace(/&height=[0-9]+/g, '');
        jQuery(this).attr( 'href', href + '&width=' + ( W - 80 ) + '&height=' + ( H - 85 ) );
        iti_widget_image_context = jQuery(this).parent().find('.iti-image-widget-image');
        jQuery('#TB_title').remove();       // TODO: why is this necessary?
        tb_show(jQuery(this).attr('title'), event.target.href, false);
        iti_widget_image_thickbox_updater = setInterval( iti_widget_image_update_thickbox, 500 );
    });
});
