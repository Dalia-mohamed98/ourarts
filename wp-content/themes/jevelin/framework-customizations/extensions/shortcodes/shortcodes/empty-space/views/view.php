<?php
/*-----------------------------------------------------------------------------------*/
/* Empty Space HTML
/*-----------------------------------------------------------------------------------*/

if ( ! defined( 'FW' ) ) { die( 'Forbidden' ); }
$class = ( $atts['class'] ) ? ' '.$atts['class'] : '';
$height = ( $atts['height'] ) ? jevelin_addpx( $atts['height'] ) : '';
?>

<div class="sh-empty-space<?php echo esc_attr( $class ); ?>" id="empty-space-<?php echo esc_attr( $atts['id'] ); ?>"></div>
