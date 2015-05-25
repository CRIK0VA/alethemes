(function($) {
    "use strict";



    //Shortcodes
    tinymce.PluginManager.add( 'aleShortcodes', function( editor, url ) {

        editor.addCommand("alePopup", function ( a, params )
        {
            var popup = params.identifier;
            tb_show("Insert Ale Shortcode", url + "/popup.php?popup=" + popup + "&width=" + 800);
        });

        editor.addButton( 'ale_button', {
            type: 'splitbutton',
            icon: false,
            title:  'Ale Shortcodes',
            onclick : function(e) {},
            menu: [
                {text: 'Alerts',onclick:function(){
                    editor.execCommand("alePopup", false, {title: 'Alerts',identifier: 'alert'})
                }},
                {text: 'Buttons',onclick:function(){
                    editor.execCommand("alePopup", false, {title: 'Buttons',identifier: 'button'})
                }},
                {text: 'Columns',onclick:function(){
                    editor.execCommand("alePopup", false, {title: 'Columns',identifier: 'columns'})
                }},
                {text: 'Tabs',onclick:function(){
                    editor.execCommand("alePopup", false, {title: 'Tabs',identifier: 'tabs'})
                }},
                {text: 'Toggle',onclick:function(){
                    editor.execCommand("alePopup", false, {title: 'Toggle',identifier: 'toggle'})
                }},
                {text: 'Divider',onclick:function(){
                    editor.execCommand("alePopup", false, {title: 'Divider',identifier: 'divider'})
                }},
                {text: 'Testimonial',onclick:function(){
                    editor.execCommand("alePopup", false, {title: 'Testimonial',identifier: 'testimonial'})
                }},
                {text: 'Team',onclick:function(){
                    editor.execCommand("alePopup", false, {title: 'Team',identifier: 'team'})
                }},
                {text: 'Partner',onclick:function(){
                    editor.execCommand("alePopup", false, {title: 'Partner',identifier: 'partner'})
                }},
                {text: 'Service',onclick:function(){
                    editor.execCommand("alePopup", false, {title: 'Service',identifier: 'service'})
                }},
                {text: 'Map',onclick:function(){
                    editor.execCommand("alePopup", false, {title: 'Map',identifier: 'map'})
                }}
            ]


        });



    });



})(jQuery);