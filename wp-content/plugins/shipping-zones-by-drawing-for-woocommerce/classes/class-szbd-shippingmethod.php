<?php
if (!defined('ABSPATH'))
  {
  exit;
  }

if (is_plugin_active_for_network('woocommerce/woocommerce.php' ) || in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))))
  {

  function szbd_shipping_method_init()
    {
    if (!class_exists('WC_SZBD_Shipping_Method'))
      {
      class WC_SZBD_Shipping_Method extends WC_Shipping_Method
        {
        /**
         * Constructor for your shipping class
         *
         * @access public
         * @return void
         */
        public function __construct($instance_id = 0)
          {
          $this->id                 = 'szbd-shipping-method';
          $this->instance_id        = absint($instance_id);
          $this->method_title       = __('Shipping Zones by Drawing', SZBD::TEXT_DOMAIN);
          $this->method_description = __('Shipping method to be used with a drawn delivery zone', SZBD::TEXT_DOMAIN);
          $this->supports           = array(
            'shipping-zones',
            'instance-settings',
            'instance-settings-modal'
          );


          $this->init();
          add_action('woocommerce_update_options_shipping_' . $this->id, array(
            $this,
            'process_admin_options'
          ));
          }
        /**
         * Init your settings
         *
         * @access public
         * @return void
         */
        function init()
          {
          // Load the settings API
          $this->init_form_fields();
          $this->init_instance_settings();
          $this->enabled = $this->get_option('enabled');
          $this->title   = get_the_title($this->get_option('title'));
          $this->info    = $this->get_option('info');
          $this->rate    = $this->get_option('rate');


          }
        function init_form_fields()
          {
          $args           = array(
            'numberposts' => 5,
            'post_type' => 'szbdzones',
            'post_status'      => 'publish'
          );
          $delivery_zoons = get_posts($args);
          if (is_array($delivery_zoons) || is_object($delivery_zoons))
            {
            $attr_option = array();
            $calc_1      = array();
            foreach ($delivery_zoons as $calc_2)
              {
              $calc_3 = get_the_title($calc_2);
              $calc_1 += array(
                $calc_2->ID => ($calc_3)
              );
              $attr_option = $calc_1;
              }
            $attr_option += array(
              "None" => esc_html__("None", SZBD::TEXT_DOMAIN)
            );

            }
          else
            {
            $attr_option = array(
              "None" => esc_html__("None", SZBD::TEXT_DOMAIN)
            );
            }
          $this->instance_form_fields = array(
            'rate' => array(
              'title' => __('Rate', SZBD::TEXT_DOMAIN),
              'type' => 'text',
              'description' => __('Enter a shipping rate.', SZBD::TEXT_DOMAIN),
              'default' => '0'
            ),
            'title' => array(
              'title' => __('Title', SZBD::TEXT_DOMAIN),
              'type' => 'select',
              'description' => __('Select your delivery map', SZBD::TEXT_DOMAIN),

              'options' =>  ($attr_option)
            )
          );
          }
        public function calculate_shipping($package = array())
          {

          $rate = array(
            'label' => $this->title,
            'cost' => $this->rate,
            'calc_tax' => 'per_order'
          );
          $this->add_rate($rate);
          }
        }
      }
    }
  add_action('woocommerce_shipping_init', 'szbd_shipping_method_init');

  function add_szbd_shipping_method($methods)
    {
    $methods['szbd-shipping-method'] = new WC_SZBD_Shipping_Method();
    return $methods;
    }
  add_filter('woocommerce_shipping_methods', 'add_szbd_shipping_method');
  function szbd_in_array_field($needle, $needle_field, $haystack, $strict = false)
    {
    if ($strict)
      {
      foreach ($haystack as $item)
        if (isset($item->$needle_field) && $item->$needle_field === $needle)
          return true;
      }
    else
      {
      foreach ($haystack as $item)
        if (isset($item->$needle_field) && $item->$needle_field == $needle)
          return true;
      }
    return false;
    }
  function check_address_2()
    {
    global $wpdb;
    $country            = strtoupper(wc_clean(WC()->customer->get_shipping_country()));
    $state              = strtoupper(wc_clean(WC()->customer->get_shipping_state()));
    $continent          = strtoupper(wc_clean(WC()->countries->get_continent_code_for_country($country)));
    $postcode           = wc_normalize_postcode(wc_clean(WC()->customer->get_shipping_postcode()));
    // Work out criteria for our zone search
    $criteria           = array();
    $criteria[]         = $wpdb->prepare("( ( location_type = 'country' AND location_code = %s )", $country);
    $criteria[]         = $wpdb->prepare("OR ( location_type = 'state' AND location_code = %s )", $country . ':' . $state);
    $criteria[]         = $wpdb->prepare("OR ( location_type = 'continent' AND location_code = %s )", $continent);
    $criteria[]         = "OR ( location_type IS NULL ) )";
    // Postcode range and wildcard matching
    $postcode_locations = $wpdb->get_results("SELECT zone_id, location_code FROM {$wpdb->prefix}woocommerce_shipping_zone_locations WHERE location_type = 'postcode';");
    if ($postcode_locations)
      {
      $zone_ids_with_postcode_rules = array_map('absint', wp_list_pluck($postcode_locations, 'zone_id'));
      $matches                      = wc_postcode_location_matcher($postcode, $postcode_locations, 'zone_id', 'location_code', $country);
      $do_not_match                 = array_unique(array_diff($zone_ids_with_postcode_rules, array_keys($matches)));
      if (!empty($do_not_match))
        {
        $criteria[] = "AND zones.zone_id NOT IN (" . implode(',', $do_not_match) . ")";
        }
      }
    // Get matching zones
    $szbd_zoons = $wpdb->get_results("

            SELECT zones.zone_id FROM {$wpdb->prefix}woocommerce_shipping_zones as zones

            LEFT OUTER JOIN {$wpdb->prefix}woocommerce_shipping_zone_locations as locations ON zones.zone_id = locations.zone_id AND location_type != 'postcode'

            WHERE " . implode(' ', $criteria) . "

           ORDER BY zone_order ASC, zone_id ASC LIMIT 1

        ");
    if (isset($szbd_zoons) && !empty($szbd_zoons))
      {
      $delivery_zones = WC_Shipping_Zones::get_zones();
      $szbd_zone      = array();
      foreach ((array) $delivery_zones as $p => $a_zone)
        {
        if (szbd_in_array_field($a_zone['zone_id'], 'zone_id', $szbd_zoons))
          {
          foreach ((array) $a_zone['shipping_methods'] as $value)
            {
            $array_latlng = array();
            $value_id     = $value->id;
            $enabled      = $value->enabled;
            if ($enabled == 'yes' && $value_id == 'szbd-shipping-method')
              {
              $zone_id = $value->instance_settings['title'];
              $meta    = get_post_meta(intval($zone_id), 'szbdzones_metakey', true);
              if (is_array($meta['geo_coordinates']) && count($meta['geo_coordinates']) > 0)
                {
                $i2 = 0;
                foreach ($meta['geo_coordinates'] as $geo_coordinates)
                  {
                  if ($geo_coordinates[0] != '' && $geo_coordinates[1] != '')
                    {
                    $array_latlng[$i2] = array(
                      $geo_coordinates[0],
                      $geo_coordinates[1]
                    );
                    $i2++;
                    }
                  }
                }
              else
                {
                $array_latlng = null;
                }
              $szbd_zone[] = array(
                'zone_id' => intval($zone_id),
                'cost' => $value->rate,
                'wc_price_cost' => wc_price($value->rate),
                'geo_coordinates' => $array_latlng,
                'value_id' => $value->get_rate_id(),

              );
              }
            }
          }
        }
      wp_send_json(array(
        'szbd_zones' => $szbd_zone,
        'status' => true,
         'exclude' => get_option('szbd_exclude_shipping_methods', 'no'),
      ));
      }
    else
      {
      wp_send_json(array(
        'szbd_zones' => array(),
        'status' => true,
         'exclude' => get_option('szbd_exclude_shipping_methods', 'no'),
      ));
      }
    }
  /* Testing for use with food online free version starts here*/
  add_filter('wp_ajax_nopriv_check_address_2', 'check_address_2');
  add_filter('wp_ajax_check_address_2', 'check_address_2');
  add_action('wp_enqueue_scripts', 'enqueue_scripts_aro', 999);
  function enqueue_scripts_aro()
    {
        if(is_checkout() && get_option( 'szbd_deactivate_google', 'no' ) == 'no'){

    $google_api_key = get_option('szbd_google_api_key', '');

    wp_enqueue_script('szbd-google-autocomplete-2', 'https://maps.googleapis.com/maps/api/js?v=3&libraries=geometry,places&types=address' . '' . '&key=' . $google_api_key);

    wp_enqueue_script('shipping-del-aro', SZBD_PLUGINDIRURL . '/assets/szbd.js', array(
      'jquery',
      'backbone',
      'szbd-google-autocomplete-2'
    ),SZBD_VERSION, true);
     wp_localize_script( 'shipping-del-aro', 'szbd',
                       array(
                             'checkout_string_1'=> __( 'There are no shipping options available. Please ensure that your address has been entered correctly, or contact us if you need any help.', 'woocommerce' ),
                             'checkout_string_2'=> __('Minimum order value is','szbd'),
                      ) );
      wp_enqueue_style('shipping-del-aro-style', SZBD_PLUGINDIRURL . '/assets/szbd.css',SZBD_VERSION);
    }else if(is_checkout() && get_option( 'szbd_deactivate_google', 'no' ) == 'yes'){

         wp_enqueue_script('shipping-del-aro', SZBD_PLUGINDIRURL . '/assets/szbd.js', array(
      'jquery',
      'backbone',

    ),SZBD_VERSION, true);
          wp_localize_script( 'shipping-del-aro', 'szbd',
                       array(
                             'checkout_string_1'=> __( 'There are no shipping options available. Please ensure that your address has been entered correctly, or contact us if you need any help.', 'woocommerce' ),
                             'checkout_string_2'=> __('Minimum order value is','szbd'),
                      ) );
          wp_enqueue_style('shipping-del-aro-style', SZBD_PLUGINDIRURL . '/assets/szbd.css',SZBD_VERSION);





    }
    }
    function disable_shipping_calc_on_cart( $show_shipping ) {


    if( is_cart() && get_option('szbd_hide_shipping_cart','no') == 'yes' ) {
        return false;
    }
    return $show_shipping;
}
add_filter( 'woocommerce_cart_ready_to_calc_shipping', 'disable_shipping_calc_on_cart', 999 );
  }
