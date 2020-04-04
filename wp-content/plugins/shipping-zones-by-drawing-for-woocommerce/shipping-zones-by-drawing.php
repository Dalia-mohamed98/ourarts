<?php
/**
 * Plugin Name: Shipping Zones by Drawing for WooCommerce
 * Plugin URI: https://arosoft.se/product/shipping-zones-drawing-premium/
 * Description: Define your WooCommerce shipping zones by drawing them on a map.
 * Version: 1.1.4
 * Author: Arosoft.se
 * Author URI: https://arosoft.se
 * Developer: Arosoft.se
 * Developer URI: https://arosoft.se
 * Text Domain: szbd
 * Domain Path: /languages
 * WC requires at least: 3.3
 * WC tested up to: 3.7
 * Copyright: Arosoft.se 2019
 * License: GPL v2 or later
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */
if (!defined('ABSPATH'))
  {
  exit;
  }

define('SZBD_VERSION', '1.1.4');
define('SZBD_PLUGINDIRURL', plugin_dir_url(__FILE__));
define('SZBD_PLUGINDIRPATH', plugin_dir_path(__FILE__));

// Register hook for activation
register_activation_hook(__FILE__, array(
  'SZBD',
  'activate'
));

// Register hook for uninstallation
register_uninstall_hook(__FILE__, array(
  'SZBD',
  'uninstall'
));

if ( !class_exists( 'SZBD' ) ){
     /**
		 * Main Class SZBD
		 *
		 * @since 1.0.0
		 */

class SZBD
  {
  const TEXT_DOMAIN = 'szbd';
  const POST_TITLE = 'szbdzones';
  protected static $_instance = null;
  public $notices;

   // to be run on plugin activation
  static public function activate()
    {
    $admin = get_role('administrator');
    flush_rewrite_rules();
    $admin_capabilities = array(
      'delete_szbdzones',
      'delete_others_szbdzones',
      'delete_private_szbdzones',
      'delete_published_szbdzones',
      'edit_szbdzones',
      'edit_others_szbdzones',
      'edit_private_szbdzones',
      'edit_published_szbdzones',
      'publish_szbdzones',
      'read_private_szbdzones'
    );
    foreach ($admin_capabilities as $capability)
      {
      $admin->add_cap($capability);
      }
    }

  // to be run on plugin uninstallation
  public static function uninstall()
    {
   
    unregister_post_type('szbdzones');
    flush_rewrite_rules();
    }

  public static function instance()
    {
   NULL === self::$_instance and self::$_instance = new self;
        return self::$_instance;
    }

    // The Constructor
  public function __construct()
    {
    add_filter('plugin_action_links_' . plugin_basename(__FILE__), array(
      $this,
      'add_action_links'
    ));
    add_action('init', array(
      $this,
      'load_text_domain'
    ));
    add_action('admin_init', array(
      $this,
      'check_environment'
    ));
    add_action('plugins_loaded', array(
      $this,
      'init'
    ), 10);
     add_action( 'admin_notices', array(
                 $this,
                'admin_notices'
            ), 15 );
    }

    public function init()
    {
    // check if environment is ok
    if (self::get_environment_warning())
      {
      return;
      }
       $this->includes();
    }

    // Includes plugin files
        public function includes()
        {
            if (is_admin())
      {
      require_once( SZBD_PLUGINDIRPATH. 'classes/class-szbd-settings.php');
      require_once(SZBD_PLUGINDIRPATH. 'classes/class-szbd-admin.php');
      $this->admin = new SZBD_Admin();
      }
    require_once(SZBD_PLUGINDIRPATH. 'classes/class-szbd-shippingmethod.php');
    require_once(SZBD_PLUGINDIRPATH. 'classes/class-szbd-the-post.php');
        }

  // For use in future versions. Loads text domain files
  public function load_text_domain()
    {
    load_plugin_textdomain(SZBD::TEXT_DOMAIN, false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

  // Add setting links to Plugins page
  public function add_action_links($links)
    {
    if (plugin_basename(__FILE__) == "shipping-zones-by-drawing-for-woocommerce/shipping-zones-by-drawing.php")
      {
      $links_add = array(
        '<a href="' . admin_url('admin.php?page=wc-settings&tab=szbdtab') . '">Settings</a>',
        '<a href="https://arosoft.se/product/shipping-zones-drawing-premium/">Go Premium</a>'
      );
      }
    else
      {
      $links_add = array(
        '<a href="' . admin_url('admin.php?page=wc-settings&tab=szbdtab') . '">Settings</a>'
      );
      }
    return array_merge($links, $links_add);
    }

  // Checks if WooCommerce etc. is active and if not returns error message
  static function get_environment_warning()
    {
    include_once(ABSPATH . 'wp-admin/includes/plugin.php');
    if (!defined('WC_VERSION'))
      {
      return __('Shipping Zones by Drawing requires WooCommerce to be activated to work.', SZBD::TEXT_DOMAIN);
      die();
      }
    //if this is Premium
    /*
    else if (is_plugin_active('shipping-zones-by-drawing-for-woocommerce/shipping-zones-by-drawing.php'))
      {
      return __('Shipping Zones by Drawing Premium can not be activated when the free version is active.', SZBD::TEXT_DOMAIN);
      die();
      }*/
    // If this is free version
       else if ( is_plugin_active( 'shipping-zones-by-drawing-premium/shipping-zones-by-drawing.php') ) {
    return __( 'Shipping Zones by Drawing can not be activated when the premuim version is active.', SZBD::TEXT_DOMAIN );
    die();
    }
    return false;
    }

  // Checks if environment is ok
  public function check_environment()
    {
    $environment_warning = self::get_environment_warning();
    if ($environment_warning && is_plugin_active(plugin_basename(__FILE__)))
      {
      $this->add_admin_notice('bad_environment', 'error', $environment_warning);
      deactivate_plugins(plugin_basename(__FILE__));
      }
    }

    // Adds notice if environmet is not ok
     public function add_admin_notice( $slug, $class, $message )
        {
            $this->notices[ $slug ] = array(
                 'class' => $class,
                'message' => $message
            );
        }

     public function admin_notices()
        {
            foreach ( (array) $this->notices as $notice_key => $notice ) {
                echo "<div class='" . esc_attr( $notice[ 'class' ] ) . "'><p>";
                echo wp_kses( $notice[ 'message' ], array(
                     'a' => array(
                         'href' => array ()
                    )
                ) );
                echo '</p></div>';
            }
            unset( $notice_key );
        }


  }
}
$GLOBALS['szbd_item'] = SZBD::instance();
