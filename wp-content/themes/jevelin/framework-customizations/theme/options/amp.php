<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$amp_options = array(
    'amp_post_content_color' => array(
	    'type'  => 'color-picker',
	    'label' => esc_html__( 'Content Color', 'jevelin' ),
	),

    'amp_post_content_size' => array(
		'type'  => 'text',
		'value' => '',
		'label' => esc_html__( 'Post Content Size', 'jevelin' ),
		'desc'  => esc_html__( 'Enter post content size with PX', 'jevelin' ),
	),

    'amp_logo_size_desktop' => array(
	    'type'  => 'slider',
	    'value' => 40,
	    'properties' => array(
	        'min' => 1,
	        'max' => 100,
	    ),
	    'label' => esc_html__( 'Logo Size (desktop)', 'jevelin' ),
	),

    'amp_logo_size_mobile' => array(
	    'type'  => 'slider',
	    'value' => 22,
	    'properties' => array(
	        'min' => 1,
	        'max' => 100,
	    ),
	    'label' => esc_html__( 'Logo Size (mobile)', 'jevelin' ),
	),
);


$options = array(
	'amp' => array(
		'title'   => esc_html__( 'AMP', 'jevelin' ),
		'type'    => 'tab',
		'icon'	  => 'fa fa-phone',
		'options' => array(
			'amp-box' => array(
				'title'   => esc_html__( 'AMP Options', 'jevelin' ),
				'type'    => 'box',
				'options' => $amp_options
			),
		)
	)
);
