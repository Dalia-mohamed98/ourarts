<?php
class SZBD_Admin
  {
  function __construct()
    {
    add_action( 'admin_enqueue_scripts', array(
       $this,
      'enqueue_scripts'
    ) );
    add_action( 'add_meta_boxes', array(
       $this,
      'add_meta_boxes'
    ) );
    add_action( 'save_post_szbdzones', array(
       $this,
      'save_post'
    ), 10, 3 );
    }
  public function enqueue_scripts()
    {
    global $pagenow, $post;
    if ( isset( get_current_screen()->id ) && ( get_current_screen()->id == 'edit-' . SZBD::POST_TITLE || get_current_screen()->id == SZBD::POST_TITLE ) )
      {
       wp_enqueue_style( 'szbd-order-font-3', SZBD_PLUGINDIRURL .  'assets/fontawesome/css/fontawesome.min.css' ,array(),SZBD_VERSION);
		wp_enqueue_style( 'szbd-order-font-4', SZBD_PLUGINDIRURL .  'assets/fontawesome/css/solid.min.css' ,array(), SZBD_VERSION );
		wp_enqueue_style( 'szbd-order-font-4', SZBD_PLUGINDIRURL .  'assets/fontawesome/css/regular.min.css' ,array(), SZBD_VERSION );
      wp_enqueue_script( 'shipping-del-aro-admin', SZBD_PLUGINDIRURL . '/assets/szbd-admin.js', array(
         'jquery'
      ), true );
      $args = array(
         'screen' => null !== get_current_screen() ? get_current_screen() : false
      );
      wp_localize_script( 'shipping-del-aro-admin', 'szbd', $args );
      }
    if ( get_post_type() !== 'szbdzones' || !in_array( $pagenow, array(
       'post-new.php',
      'edit.php',
      'post.php'
    ) ) )
      {
      return;
      }
    $google_api_key = get_option( 'szbd_google_api_key', '' );
    if ( $google_api_key != '' && get_current_screen()->id == SZBD::POST_TITLE && get_option( 'szbd_deactivate_google', 'no' ) == 'no')
      {
      wp_enqueue_script( 'szbd-script', '//maps.googleapis.com/maps/api/js?key=' . $google_api_key . '&libraries=geometry,places,drawing', array(
         'jquery'
      ), false, true );
      wp_register_script( 'szbd-script-2', SZBD_PLUGINDIRURL. '/assets/szbd-admin-map.js', array(
         'szbd-script',
        'jquery'
      ), false, true );
      $this->szbdzones_js( $post->ID );
      wp_enqueue_script( 'szbd-script-2' );
      }
    }
  public function add_meta_boxes()
    {
    add_meta_box( 'szbdzones_mapmeta', 'Map', array(
       $this,
      'input_map'
    ), 'szbdzones', 'normal', 'high' );
    }
  public function input_map()
    {
    global $post;
    $google_api_key = get_option( 'szbd_google_api_key', '' );
    if ( $google_api_key != '' || get_option( 'szbd_deactivate_google', 'no' ) == 'yes' )
      {
      include SZBD_PLUGINDIRPATH . '/includes/admin-map-template.php';
      }
    else
     { echo sprintf( __( 'Please enter a Google Maps API Key in the <a href="%s" title="settings page">settings page.</a>', SZBD::TEXT_DOMAIN ), admin_url( 'admin.php?page=wc-settings&tab=szbdtab' ) );
    }
     echo '<div class="notice notice-info is-dismissible">

            <div class="fdoe_premium">

            	<table>

                	<tbody><tr>

                    	<td width="100%">

                        	<p style="font-size:1.3em"><strong><i>Upgrade to Premium </i></strong>and get more features</p>

                            <ul class="fa-ul" id="fdoe_premium_ad">

								<li ><span class="fa-li" ><i class="fas fa-check" style="color:#00a0d2"></i></span>	Set a minimum order value per zone</li>

                            	<li ><span class="fa-li" ><i class="fas fa-check" style="color:#00a0d2"></i></span>	Show only the zone with lowest cost at checkout</li>
                                	<li ><span class="fa-li" ><i class="fas fa-check" style="color:#00a0d2"></i></span>	Draw as many zones you like</li>




								 <a target="_blank" rel="noopener noreferrer" href="https://arosoft.se/product/shipping-zones-drawing-premium/" class=" " ><p style="display: inline-block;
    padding: 12px 20px;
    border-radius: 8px;
    border: 0;
    font-weight: bold;
    letter-spacing: 0.0625em;
    text-decoration: none;
    background: #00a0d2;
    color: #fff;
    text-align: center;">Get Premium!</p><p></p></a>


                            </ul>

                        </td>



                    </tr>

                </tbody></table>

            </div>

         </div>';
    }

  public function szbdzones_js( $post_id )
    {
    $settings     = get_post_meta( $post_id, 'szbdzones_metakey', true );
    $lat          = isset( $settings['lat'] ) ? $settings['lat'] : '';
    $lng          = isset( $settings['lng'] ) ? $settings['lng'] : '';
    $zoom         = isset( $settings['zoom'] ) ? $settings['zoom'] : '1.3';
    $geo_coordinates_array = is_array( $settings ) && is_array( $settings['geo_coordinates'] ) ? $settings['geo_coordinates'] : array();
    if ( count( $geo_coordinates_array ) > 0 )
      {
      foreach ( $geo_coordinates_array as $geo_coordinates )
        {
        if ( $geo_coordinates[0] != '' && $geo_coordinates[1] != '' )
          $array_latlng[] = array(
             $geo_coordinates[0],
            $geo_coordinates[1]
          );
        }
      }
    else
      {
      $array_latlng = array();
      }
    $args = array(
       'lat' => $lat,
      'lng' => $lng,
      'zoom' => intval( $zoom ),
      'array_latlng' => $array_latlng
    );
    wp_localize_script( 'szbd-script-2', 'szbd_map', $args );
    //    }
    }
  public function save_post( $post_id, $post, $update )
    {
        if ( is_multisite() && ms_is_switched() ){
    return FALSE;
        }
    if ( $post->post_type != 'szbdzones' )
      return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
      return;
    if ( wp_is_post_revision( $post_id ) )
      return;
    if ( !current_user_can( 'edit_post', $post_id ) )
      return;
    if ( isset( $_POST['szbdzones_geo_coordinates'] ) && !empty( $_POST['szbdzones_geo_coordinates'] ) )
      {
      $array_geo_coordinates = explode( '),(', $_POST['szbdzones_geo_coordinates'] );
      if ( is_array( $array_geo_coordinates ) && count( $array_geo_coordinates ) > 0 )
        {
        foreach ( $array_geo_coordinates as $value_geo_coordinates )
          {
          $latlng         = str_replace( array(
             "(",
            ")"
          ), array(
             "",
            ""
          ), $value_geo_coordinates );
          $array_latlng[] = array_map( 'sanitize_text_field', explode( ',', $latlng ) );
          }
        }
      else
        $array_latlng = array();
      $array_save_post = array(
         'lcolor' => !empty( $_POST['szbdzones_lcolor'] ) ? sanitize_text_field( $_POST['szbdzones_lcolor'] ) : '#0c6e9e',
        'lat' => !empty( $_POST['szbdzones_lat'] ) ? sanitize_text_field( $_POST['szbdzones_lat'] ) : 0,
        'lng' => !empty( $_POST['szbdzones_lng'] ) ? sanitize_text_field( $_POST['szbdzones_lng'] ) : 65,
        'geo_coordinates' => $array_latlng,
        'zoom' => !empty( $_POST['szbdzones_zoom'] ) ? sanitize_text_field( $_POST['szbdzones_zoom'] ) : 1.3
      );
      update_post_meta( $post_id, 'szbdzones_metakey', $array_save_post );
      }
    return $post_id;
    }
  }
?>
