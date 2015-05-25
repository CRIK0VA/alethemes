<?php

/*-----------------------------------------------------------------------------------*/
/*	Button Config
/*-----------------------------------------------------------------------------------*/

$ale_shortcodes['button'] = array(
	'no_preview' => true,
	'params' => array(
		'url' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Button URL', 'aletheme'),
			'desc' => __('Add the button\'s url eg http://example.com', 'aletheme')
		),
		'style' => array(
			'type' => 'select',
			'label' => __('Button Style', 'aletheme'),
			'desc' => __('Select the button\'s style, ie the button\'s colour', 'aletheme'),
			'options' => array(
				'grey' => 'Grey',
				'black' => 'Black',
				'green' => 'Green',
				'light-blue' => 'Light Blue',
				'blue' => 'Blue',
				'red' => 'Red',
				'orange' => 'Orange',
				'purple' => 'Purple'
			)
		),
		'size' => array(
			'type' => 'select',
			'label' => __('Button Size', 'aletheme'),
			'desc' => __('Select the button\'s size', 'aletheme'),
			'options' => array(
				'small' => 'Small',
				'medium' => 'Medium',
				'large' => 'Large'
			)
		),
		'type' => array(
			'type' => 'select',
			'label' => __('Button Type', 'aletheme'),
			'desc' => __('Select the button\'s type', 'aletheme'),
			'options' => array(
				'round' => 'Round',
				'square' => 'Square'
			)
		),
		'target' => array(
			'type' => 'select',
			'label' => __('Button Target', 'aletheme'),
			'desc' => __('_self = open in same window. _blank = open in new window', 'aletheme'),
			'options' => array(
				'_self' => '_self',
				'_blank' => '_blank'
			)
		),
		'content' => array(
			'std' => 'Button Text',
			'type' => 'text',
			'label' => __('Button\'s Text', 'aletheme'),
			'desc' => __('Add the button\'s text', 'aletheme'),
		)
	),
	'shortcode' => '[ale_button url="{{url}}" style="{{style}}" size="{{size}}" type="{{type}}" target="{{target}}"] {{content}} [/ale_button]',
	'popup_title' => __('Insert Button Shortcode', 'aletheme')
);

/*-----------------------------------------------------------------------------------*/
/*	Alert Config
/*-----------------------------------------------------------------------------------*/

$ale_shortcodes['alert'] = array(
	'no_preview' => true,
	'params' => array(
		'style' => array(
			'type' => 'select',
			'label' => __('Alert Style', 'aletheme'),
			'desc' => __('Select the alert\'s style, ie the alert colour', 'aletheme'),
			'options' => array(
				'white' => 'White',
				'grey' => 'Grey',
				'red' => 'Red',
				'yellow' => 'Yellow',
				'green' => 'Green'
			)
		),
		'content' => array(
			'std' => 'Your Alert!',
			'type' => 'textarea',
			'label' => __('Alert Text', 'aletheme'),
			'desc' => __('Add the alert\'s text', 'aletheme'),
		)
		
	),
	'shortcode' => '[ale_alert style="{{style}}"] {{content}} [/ale_alert]',
	'popup_title' => __('Insert Alert Shortcode', 'aletheme')
);

/*-----------------------------------------------------------------------------------*/
/*	Toggle Config
/*-----------------------------------------------------------------------------------*/

$ale_shortcodes['toggle'] = array(
	'no_preview' => true,
	'params' => array(
		'title' => array(
			'type' => 'text',
			'label' => __('Toggle Content Title', 'aletheme'),
			'desc' => __('Add the title that will go above the toggle content', 'aletheme'),
			'std' => 'Title'
		),
		'content' => array(
			'std' => 'Content',
			'type' => 'textarea',
			'label' => __('Toggle Content', 'aletheme'),
			'desc' => __('Add the toggle content. Will accept HTML', 'aletheme'),
		),
		'state' => array(
			'type' => 'select',
			'label' => __('Toggle State', 'aletheme'),
			'desc' => __('Select the state of the toggle on page load', 'aletheme'),
			'options' => array(
				'open' => 'Open',
				'closed' => 'Closed'
			)
		),
		
	),
	'shortcode' => '[ale_toggle title="{{title}}" state="{{state}}"] {{content}} [/ale_toggle]',
	'popup_title' => __('Insert Toggle Content Shortcode', 'aletheme')
);

/*-----------------------------------------------------------------------------------*/
/*	Tabs Config
/*-----------------------------------------------------------------------------------*/

$ale_shortcodes['tabs'] = array(
    'params' => array(),
    'no_preview' => true,
    'shortcode' => '[ale_tabs] {{child_shortcode}}  [/ale_tabs]',
    'popup_title' => __('Insert Tab Shortcode', 'aletheme'),

    'child_shortcode' => array(
        'params' => array(
            'title' => array(
                'std' => 'Title',
                'type' => 'text',
                'label' => __('Tab Title', 'aletheme'),
                'desc' => __('Title of the tab', 'aletheme'),
            ),
            'content' => array(
                'std' => 'Tab Content',
                'type' => 'textarea',
                'label' => __('Tab Content', 'aletheme'),
                'desc' => __('Add the tabs content', 'aletheme')
            )
        ),
        'shortcode' => '[ale_tab title="{{title}}"] {{content}} [/ale_tab]',
        'clone_button' => __('Add Tab', 'aletheme')
    )
);

/*-----------------------------------------------------------------------------------*/
/*	Columns Config
/*-----------------------------------------------------------------------------------*/

$ale_shortcodes['columns'] = array(
	'params' => array(),
	'shortcode' => ' {{child_shortcode}} ', // as there is no wrapper shortcode
	'popup_title' => __('Insert Columns Shortcode', 'aletheme'),
	'no_preview' => true,
	
	// child shortcode is clonable & sortable
	'child_shortcode' => array(
		'params' => array(
			'column' => array(
				'type' => 'select',
				'label' => __('Column Type', 'aletheme'),
				'desc' => __('Select the type, ie width of the column.', 'aletheme'),
				'options' => array(
					'ale_one_third' => 'One Third',
					'ale_one_third_last' => 'One Third Last',
					'ale_two_third' => 'Two Thirds',
					'ale_two_third_last' => 'Two Thirds Last',
					'ale_one_half' => 'One Half',
					'ale_one_half_last' => 'One Half Last',
					'ale_one_fourth' => 'One Fourth',
					'ale_one_fourth_last' => 'One Fourth Last',
					'ale_three_fourth' => 'Three Fourth',
					'ale_three_fourth_last' => 'Three Fourth Last',
					'ale_one_fifth' => 'One Fifth',
					'ale_one_fifth_last' => 'One Fifth Last',
					'ale_two_fifth' => 'Two Fifth',
					'ale_two_fifth_last' => 'Two Fifth Last',
					'ale_three_fifth' => 'Three Fifth',
					'ale_three_fifth_last' => 'Three Fifth Last',
					'ale_four_fifth' => 'Four Fifth',
					'ale_four_fifth_last' => 'Four Fifth Last',
					'ale_one_sixth' => 'One Sixth',
					'ale_one_sixth_last' => 'One Sixth Last',
					'ale_five_sixth' => 'Five Sixth',
					'ale_five_sixth_last' => 'Five Sixth Last'
				)
			),
			'content' => array(
				'std' => '',
				'type' => 'textarea',
				'label' => __('Column Content', 'aletheme'),
				'desc' => __('Add the column content.', 'aletheme'),
			)
		),
		'shortcode' => '[{{column}}] {{content}} [/{{column}}] ',
		'clone_button' => __('Add Column', 'aletheme')
	)
);

/*-----------------------------------------------------------------------------------*/
/*	Divider Config
/*-----------------------------------------------------------------------------------*/

$ale_shortcodes['divider'] = array(
    'no_preview' => true,
    'params' => array(
        'style' => array(
            'type' => 'select',
            'label' => __('Divider Style', 'aletheme'),
            'desc' => __('Select the divider\'s style', 'aletheme'),
            'options' => array(
                'bold' => 'Bold Line',
                'thin' => 'Thin Line',
                'medium' => 'Medium Line',
                'dashed' => 'Dashed Line',
                'dark' => 'Dark Line',
                'light' => 'Light Line'
            )
        ),
        'text' => array(
            'type' => 'select',
            'label' => __('Divider Text', 'aletheme'),
            'desc' => __('Select the divider\'s text option', 'aletheme'),
            'options' => array(
                'notext' => 'No Text',
                'textleft' => 'Text Left',
                'textright' => 'Text Right',
                'textcenter' => 'Text Center'
            )
        ),
        'content' => array(
            'std' => 'Divider Text',
            'type' => 'text',
            'label' => __('Divider Text', 'aletheme'),
            'desc' => __('Add the divider\'s text', 'aletheme'),
        )

    ),
    'shortcode' => '[ale_divider style="{{style}}" text="{{text}}"] {{content}} [/ale_divider]',
    'popup_title' => __('Insert Divider Shortcode', 'aletheme')
);

/*-----------------------------------------------------------------------------------*/
/*	Testimonial Config
/*-----------------------------------------------------------------------------------*/

$ale_shortcodes['testimonial'] = array(
    'no_preview' => true,
    'params' => array(
        'style' => array(
            'type' => 'select',
            'label' => __('Testimonial Style', 'aletheme'),
            'desc' => __('Select the testimonial\'s style', 'aletheme'),
            'options' => array(
                'dark' => 'Dark Style',
                'light' => 'Light Style'
            )
        ),
        'avatar' => array(
            'std' => '',
            'type' => 'text',
            'label' => __('Testimonial Author photo link', 'aletheme'),
            'desc' => __('Add the testimonial\'s author photo link.', 'aletheme'),
        ),
        'name' => array(
            'std' => 'Testimonial Author',
            'type' => 'text',
            'label' => __('Testimonial Author', 'aletheme'),
            'desc' => __('Add the testimonial\'s author', 'aletheme'),
        ),
        'link' => array(
            'std' => '',
            'type' => 'text',
            'label' => __('Testimonial Link', 'aletheme'),
            'desc' => __('Add the testimonial\'s link', 'aletheme'),
        ),
        'content' => array(
            'std' => 'Testimonial Text',
            'type' => 'textarea',
            'label' => __('Testimonial Text', 'aletheme'),
            'desc' => __('Add the testimonial\'s text', 'aletheme'),
        )

    ),
    'shortcode' => '[ale_testimonial link="{{link}}" style="{{style}}" avatar="{{avatar}}" name="{{name}}"] {{content}} [/ale_testimonial]',
    'popup_title' => __('Insert Testimonial Shortcode', 'aletheme')
);


/*-----------------------------------------------------------------------------------*/
/*	Team Config
/*-----------------------------------------------------------------------------------*/

$ale_shortcodes['team'] = array(
    'no_preview' => true,
    'params' => array(
        'style' => array(
            'type' => 'select',
            'label' => __('Team box Style', 'aletheme'),
            'desc' => __('Select the team box\'s style', 'aletheme'),
            'options' => array(
                'dark' => 'Dark Style',
                'light' => 'Light Style'
            )
        ),
        'avatar' => array(
            'std' => '',
            'type' => 'text',
            'label' => __('Team Author photo link', 'aletheme'),
            'desc' => __('Add the team\'s author photo link.', 'aletheme'),
        ),
        'name' => array(
            'std' => '',
            'type' => 'text',
            'label' => __('Team Author', 'aletheme'),
            'desc' => __('Add the team\'s author', 'aletheme'),
        ),
        'prof' => array(
            'std' => 'Designer',
            'type' => 'text',
            'label' => __('Team Prof', 'aletheme'),
            'desc' => __('Add the prof. team\'s author', 'aletheme'),
        ),
        'fblink' => array(
            'std' => '',
            'type' => 'text',
            'label' => __('Facebook Link', 'aletheme'),
            'desc' => __('Add the facebook link', 'aletheme'),
        ),
        'twilink' => array(
            'std' => '',
            'type' => 'text',
            'label' => __('Twitter Link', 'aletheme'),
            'desc' => __('Add the twitter link', 'aletheme'),
        ),
        'glink' => array(
            'std' => '',
            'type' => 'text',
            'label' => __('Google+ Link', 'aletheme'),
            'desc' => __('Add the google+ link', 'aletheme'),
        ),
        'content' => array(
            'std' => 'Testimonial Text',
            'type' => 'textarea',
            'label' => __('Testimonial Text', 'aletheme'),
            'desc' => __('Add the testimonial\'s text', 'aletheme'),
        )

    ),
    'shortcode' => '[ale_team style="{{style}}" avatar="{{avatar}}" name="{{name}}" prof="{{prof}}" fblink="{{fblink}}" twilink="{{twilink}}" glink="{{glink}}"] {{content}} [/ale_team]',
    'popup_title' => __('Insert Team Shortcode', 'aletheme')
);

/*-----------------------------------------------------------------------------------*/
/*	Partner Config
/*-----------------------------------------------------------------------------------*/

$ale_shortcodes['partner'] = array(
    'no_preview' => true,
    'params' => array(
        'style' => array(
            'type' => 'select',
            'label' => __('Partner box Style', 'aletheme'),
            'desc' => __('Select the partner box\'s style', 'aletheme'),
            'options' => array(
                'dark' => 'Dark Style',
                'light' => 'Light Style'
            )
        ),
        'logo' => array(
            'std' => '',
            'type' => 'text',
            'label' => __('Partner logo link', 'aletheme'),
            'desc' => __('Add the partner\'s logo link.', 'aletheme'),
        ),
        'content' => array(
            'std' => '',
            'type' => 'text',
            'label' => __('Partner Title', 'aletheme'),
            'desc' => __('Add the partner\'s title', 'aletheme'),
        ),

        'link' => array(
            'std' => '',
            'type' => 'text',
            'label' => __('Partner Link', 'aletheme'),
            'desc' => __('Add the partner link', 'aletheme'),
        ),

    ),
    'shortcode' => '[ale_partner style="{{style}}" logo="{{logo}}" link="{{link}}"]{{content}}[/ale_partner]',
    'popup_title' => __('Insert Partner Shortcode', 'aletheme')
);


/*-----------------------------------------------------------------------------------*/
/*	Service Config
/*-----------------------------------------------------------------------------------*/

$ale_shortcodes['service'] = array(
    'no_preview' => true,
    'params' => array(
        'style' => array(
            'type' => 'select',
            'label' => __('Partner box Style', 'aletheme'),
            'desc' => __('Select the partner box\'s style', 'aletheme'),
            'options' => array(
                'dark' => 'Dark Style',
                'light' => 'Light Style'
            )
        ),
        'icon' => array(
            'std' => '',
            'type' => 'text',
            'label' => __('Service icon link', 'aletheme'),
            'desc' => __('Add the service\'s icon link.', 'aletheme'),
        ),
        'name' => array(
            'std' => '',
            'type' => 'text',
            'label' => __('Service Title', 'aletheme'),
            'desc' => __('Add the service title', 'aletheme'),
        ),
        'content' => array(
            'std' => '',
            'type' => 'textarea',
            'label' => __('Short Description', 'aletheme'),
            'desc' => __('Add the service\'s description', 'aletheme'),
        ),


    ),
    'shortcode' => '[ale_service style="{{style}}" icon="{{icon}}" name="{{name}}"]{{content}}[/ale_service]',
    'popup_title' => __('Insert Service Shortcode', 'aletheme')
);

/*-----------------------------------------------------------------------------------*/
/*	Map Config
/*-----------------------------------------------------------------------------------*/

$ale_shortcodes['map'] = array(
    'no_preview' => true,
    'params' => array(
        'address' => array(
            'std' => 'Chisinau',
            'type' => 'text',
            'label' => __('Add the Address', 'aletheme'),
            'desc' => __('Add the address', 'aletheme'),
        ),
        'width' => array(
            'std' => '100%',
            'type' => 'text',
            'label' => __('Map width', 'aletheme'),
            'desc' => __('Add the width', 'aletheme'),
        ),
        'height' => array(
            'std' => '400px',
            'type' => 'text',
            'label' => __('Map height', 'aletheme'),
            'desc' => __('Add the map height', 'aletheme'),
        ),


    ),
    'shortcode' => '[ale_map address="{{address}}" width="{{width}}" height="{{height}}"]',
    'popup_title' => __('Insert Map Shortcode', 'aletheme')
);

?>