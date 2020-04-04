<?php

/**
 * @link              http://R2S.com
 * @since             1.0.0
 * @package           R2S-merchant
 *
 * @wordpress-plugin
 * Plugin Name:       R2S
 * Plugin URI:        http://R2S.com
 * Description:       made by .
 * Version:           1.0.0
 * Author:            dalia	
 * Author URI:        http://R2S.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl.txt
 * Text Domain:       dalia
 * Domain Path:       /languages
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


/////////////////includes////////////////////////
require_once 'inc/calculate tarrif.php';
require_once 'inc/deliver waypil.php';
require_once 'inc/update waypill.php';
require_once 'inc/waypill create.php';
require_once 'inc/waypill tracking.php';