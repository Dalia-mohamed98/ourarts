<?php
/*
Element: Footer Widgets
*/

class vcj_instagram extends WPBakeryShortCode {

    function __construct() {
        add_action( 'init', array( $this, '_mapping' ), 12 );
        add_shortcode( 'vcj_instagram', array( $this, '_html' ) );
    }


    public function _mapping() {
        if ( !defined( 'WPB_VC_VERSION' ) ) { return; }

        vc_map(
            array(
                'name' => __('Instagram', 'jevelin'),
                'base' => 'vcj_instagram',
                'description' => __('Add instagram widget', 'jevelin'),
                'category' => __('Jevelin Elements', 'jevelin'),
                'icon' => get_template_directory_uri().'/img/builder-icon.png',
                'params' => array(

                    array (
                        'param_name' => 'username',
                        'heading' => 'Username',
                        'description' => 'Enter instagram username',
                        'type' => 'textfield',
                    ),

                    array (
                        'param_name' => 'number',
                        'heading' => 'Number of photos',
                        'description' => 'Enter instagram number of items',
                        'type' => 'textfield',
                        'std' => '6',
                    ),

                    array (
                        'param_name' => 'offset',
                        'heading' => 'Offset',
                        'description' => 'Enter instagram item offest',
                        'type' => 'textfield',
                        'std' => '0',
                    ),

                    array (
                        'param_name' => 'size',
                        'heading' => 'Size',
                        'description' => 'Enter image size',
                        'value' => array (
                            'Thumbnail' => 'thumbnail',
                            'Small' => 'small',
                            'Large' => 'large',
                            'Original' => 'original',
                        ),
                        'type' => 'dropdown',
                        'std' => 'thumbnail',
                    ),

                    array (
                        'param_name' => 'target',
                        'heading' => 'Open links in',
                        'description' => 'Choose color preset, that can be overwritten by color options',
                        'value' => array (
                            'Current window (_self)' => '_self',
                            'New window (_blank)' => '_blank',
                        ),
                        'type' => 'dropdown',
                        'std' => '_self',
                    ),




                    array (
                        'param_name' => 'columns',
                        'heading' => 'Columns',
                        'description' => 'Choose item columns',
                        'value' => array (
                            '1 Column' => '1',
                            '2 Columns' => '2',
                            '3 Columns' => '3',
                            '4 Columns' => '4',
                            '5 Columns' => '5',
                            '6 Columns' => '6',
                        ),
                        'type' => 'dropdown',
                        'std' => '3',
            			'group' => __( 'Styling', 'jevelin' ),
                    ),

                    array (
                        'param_name' => 'spacing',
                        'heading' => 'Spacing',
                        'description' => 'Enter instagram item spacing (with px)',
                        'type' => 'textfield',
                        'std' => '5px',
            			'group' => __( 'Styling', 'jevelin' ),
                    ),

                    array (
                        'param_name' => 'border_radius',
                        'heading' => 'Border Radius',
                        'description' => 'Enter border radius (with px)',
                        'type' => 'textfield',
                        'std' => '0px',
            			'group' => __( 'Styling', 'jevelin' ),
                    ),

                    array (
                        'param_name' => 'overlay_text_color',
                        'heading' => 'Overlay Text/Icon Color',
                        'description' => 'Choose overlay text color',
                        'type' => 'colorpicker',
                        'group' => __( 'Styling', 'jevelin' ),
                    ),

                    array (
                        'param_name' => 'text_font_size',
                        'heading' => 'Text Font Size',
                        'description' => 'Enter icon font size (with px)',
                        'type' => 'textfield',
            			'group' => __( 'Styling', 'jevelin' ),
                        'edit_field_class' => 'vc_col-xs-6',
                    ),

                    array (
                        'param_name' => 'icon_font_size',
                        'heading' => 'Icon Font Size',
                        'description' => 'Enter icon font size (with px)',
                        'type' => 'textfield',
            			'group' => __( 'Styling', 'jevelin' ),
                        'edit_field_class' => 'vc_col-xs-6',
                    ),

                    array (
                        'param_name' => 'overlay_background_color1',
                        'heading' => 'Overlay Background Color',
                        'description' => 'Main background color',
                        'type' => 'colorpicker',
                        'group' => __( 'Styling', 'jevelin' ),
                        'edit_field_class' => 'vc_col-xs-6',
                    ),

                    array (
                        'param_name' => 'overlay_background_color2',
                        'heading' => 'Overlay Background Color 2',
                        'description' => 'Choose for gradient look',
                        'type' => 'colorpicker',
                        'group' => __( 'Styling', 'jevelin' ),
                        'edit_field_class' => 'vc_col-xs-6',
                    ),

                    array (
                        'param_name' => 'overlay_direction',
                        'heading' => 'Overlay Directions',
                        'description' => 'Choose item columns',
                        'value' => array (
                            'To bottom' => 'to bottom',
                            'To right' => 'to right',
                            'To bottom right' => 'to bottom right',
                            'To bottom left' => 'to bottom left',
                        ),
                        'type' => 'dropdown',
                        'std' => '3',
            			'group' => __( 'Styling', 'jevelin' ),
                    ),

            		array(
            			'param_name' => 'css',
            			'type' => 'css_editor',
            			'heading' => __( 'CSS box', 'jevelin' ),
            			'group' => __( 'Design Options', 'jevelin' ),
            		),

                ),
            )
        );

    }


    public function _html( $atts ) {
        // Params extraction
        extract( shortcode_atts( array(
            'username' => '',
            'number' => '6',
            'offset' => '0',
            'size' => 'thumbnail',
            'target' => '_self',
            'columns' => '3',
            'spacing' => '5px',
            'border_radius' => '0px',
            'overlay_text_color' => '',
            'text_font_size' => '',
            'icon_font_size' => '',
            'overlay_background_color1' => '',
            'overlay_background_color2' => '',
            'overlay_direction' => 'to bottom',
            'css' => '',
        ), $atts ) );


        // HTML
        $id = 'sh-instagram-element-'.jevelin_rand();
        $element_class = array();
        $element_class[] = $id;
        $element_class[] = 'sh-instagram-element';
        $element_class[] = 'sh-instagram-element-columns'.$columns;
        $element_class[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );
        $number = $offset ? ( $number + $offset ) : $number;
        ob_start(); ?>

            <style media="screen">
                .sh-instagram-element {
                    position: relative;
                }

                .sh-instagram-element .instagram-pics {
                    padding-left: 0;
                    margin: 0;
                    font-size: 0px;
                }

                .sh-instagram-element .instagram-pics li {
                    margin-right: 0;
                    padding: 0;
                }

                .sh-instagram-element-content {
                    position: relative;
                    overflow: hidden;
                }

                .sh-instagram-element-columns1 .instagram-pics li { width: 100%;}
                .sh-instagram-element-columns2 .instagram-pics li { width: 50%;}
                .sh-instagram-element-columns3 .instagram-pics li { width: 33.3%;}
                .sh-instagram-element-columns4 .instagram-pics li { width: 25%;}
                .sh-instagram-element-columns5 .instagram-pics li { width: 20%;}
                .sh-instagram-element-columns6 .instagram-pics li { width: 16.6666666667%;}

                @media (max-width: 1025px) {
                    .sh-instagram-element .instagram-pics li {
                        min-width: 25%;
                    }
                }

                @media (max-width: 700px) {
                    .sh-instagram-element .instagram-pics li {
                        min-width: 33.3%;
                    }
                }

                @media (max-width: 550px) {
                    .sh-instagram-element .instagram-pics li {
                        min-width: 50%;
                    }
                }


                .sh-instagram-element .sh-instagram-element-overlay {
                    position: absolute;
                    top: 0; left: 0; right: 0; bottom: 0;
                    background-color: rgba( 0, 0, 0, 0.5 );
                    text-align: center;
                    color: #fff;
                    font-size: 14px;
                    display: flex;
                	justify-content: center;
                    align-items: center;
                    opacity: 0;
                    transition: 0.3s all ease-in-out;
                }

                .sh-instagram-element .sh-instagram-element-overlay:hover {
                    opacity: 1;
                }

                .sh-instagram-element-overlay-item {
                    padding: 0 5px;
                    font-size: 12px;
                    font-weight: 600;
                }

                .sh-instagram-element-overlay-item i {
                    font-size: 11px;
                    padding-left: 2px;
                }


                <?php if( $spacing ) : ?>
                    #<?php echo $id; ?> {
                        margin: -<?php echo jevelin_addpx( $spacing ); ?>;
                    }

                    #<?php echo $id; ?> .sh-instagram-element-item {
                        padding: <?php echo jevelin_addpx( $spacing ); ?>;
                    }
                <?php endif; ?>


                <?php if( $text_font_size ) : ?>
                    #<?php echo $id; ?> .sh-instagram-element-overlay .sh-instagram-element-overlay-text {
                        font-size: <?php echo jevelin_addpx( $text_font_size ); ?>;
                    }
                <?php endif; ?>

                <?php if( $icon_font_size ) : ?>
                    #<?php echo $id; ?> .sh-instagram-element-overlay i {
                        font-size: <?php echo jevelin_addpx( $icon_font_size ); ?>;
                    }
                <?php endif; ?>

                <?php if( $border_radius ) : ?>
                    #<?php echo $id; ?> .sh-instagram-element-content {
                        border-radius: <?php echo jevelin_addpx( $border_radius ); ?>;
                    }
                <?php endif; ?>

                <?php if( $overlay_background_color1 ) : ?>
                    #<?php echo $id; ?>.sh-instagram-element .sh-instagram-element-overlay {
                        background-color: <?php echo $overlay_background_color1; ?>;

                        <?php if( $overlay_background_color2 ) : ?>
                            background: linear-gradient( <?php echo $overlay_direction; ?>, <?php echo $overlay_background_color1; ?>, <?php echo $overlay_background_color2; ?> );
                        <?php endif; ?>
                    }
                <?php endif; ?>

                <?php if( $overlay_text_color ) : ?>
                    #<?php echo $id; ?>.sh-instagram-element .sh-instagram-element-overlay {
                        color: <?php echo $overlay_text_color; ?>;
                    }
                <?php endif; ?>
            </style>

            <div<?php echo $id ? ' id="'.$id.'" ' : ''; ?> class="sh-instagram-element <?php echo implode( ' ', $element_class ); ?>">
                <?php
                    if( class_exists( 'null_instagram_widget' ) ) :
                        set_query_var( 'is_instagram_element', 1 );
                        set_query_var( 'is_instagram_element_offset', $offset );
                        the_widget( 'null_instagram_widget', array(
                            'username' => $username,
                            'number' => $number,
                            'size' => $size,
                            'target' => $target,
                        ));

                        set_query_var( 'is_instagram_element', 0 );
                        set_query_var( 'is_instagram_element_offset', 0 );
                    elseif( current_user_can( 'manage_options' ) ) :
                        echo '<h5>'.__( 'Please enable WP Instagram Widget widget under Appearance > Install plugins', 'jevelin' ).'</h5>';
                    endif;
                ?>
            </div>

        <?php return ob_get_clean();
    }

}
new vcj_instagram();
