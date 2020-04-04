<?php

/**
 * @link              http://getvoo.com
 * @since             1.0.0
 * @package           voo-merchant
 *
 * @wordpress-plugin
 * Plugin Name:       getvoo
 * Plugin URI:        http://getvoo.com
 * Description:       made by .
 * Version:           1.0.0
 * Author:            ahmedfarouk	
 * Author URI:        http://getvoo.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ash2osh_faw
 * Domain Path:       /languages
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
/**
 * Check if WooCommerce is active
 * */
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    exit;
}

add_action('plugins_loaded', 'init_ash2osh_faw_gateway_class');

/////////////////includes////////////////////////
require_once 'inc/create order.php';
require_once 'inc/create reverse order.php';


