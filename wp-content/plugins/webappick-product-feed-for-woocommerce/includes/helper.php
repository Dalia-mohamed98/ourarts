<?php
/**
 * Helper Functions
 * @package WooFeed
 * @subpackage WooFeed_Helper_Functions
 * @version 1.0.0
 * @since WooFeed 3.1.40
 * @author KD <mhamudul.hk@gmail.com>
 * @copyright WebAppick
 */
if( ! defined( 'ABSPATH' ) ) die(); // Silence...
if( ! function_exists( 'wooFeed_is_plugin_active' ) ) {
	/**
	 * Determines whether a plugin is active.
	 * @since 3.1.41
	 * @see is_plugin_active()
	 * @param string $plugin Path to the plugin file relative to the plugins directory.
	 * @return bool True, if in the active plugins list. False, not in the list.
	 */
	function wooFeed_is_plugin_active( $plugin ) {
		if( !function_exists('is_plugin_active') ) include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		return is_plugin_active( $plugin );
	}
}
if( ! function_exists( 'wooFeed_is_plugin_inactive' ) ) {
	/**
	 * Determines whether the plugin is inactive.
	 * @since 3.1.41
	 * @see wooFeed_is_plugin_inactive()
	 *
	 * @param string $plugin Path to the plugin file relative to the plugins directory.
	 * @return bool True if inactive. False if active.
	 */
	function wooFeed_is_plugin_inactive( $plugin ) {
		return ! wooFeed_is_plugin_active( $plugin );
	}
}
if( ! function_exists( 'wooFeed_deactivate_plugins' ) ) {
	/**
	 * Deactivate a single plugin or multiple plugins.
	 * Wrapper for core deactivate_plugins() function
	 * @see deactivate_plugins()
	 * @param string|array $plugins Single plugin or list of plugins to deactivate.
	 * @param bool $silent Prevent calling deactivation hooks. Default is false.
	 * @param mixed $network_wide Whether to deactivate the plugin for all sites in the network.
	 * @return void
	 */
	function wooFeed_deactivate_plugins( $plugins, $silent = false, $network_wide = null ) {
		if ( ! function_exists( 'deactivate_plugins' ) ) require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		deactivate_plugins( $plugins, $silent, $network_wide );
	}
}
if( ! function_exists( 'wooFeed_is_supported_php' ) ) {
	/**
	 * Check if server php version meet minimum requirement
	 * @since 3.1.41
	 * @return bool
	 */
	function wooFeed_is_supported_php(){
		// PHP version need to be => WOO_FEED_MIN_PHP_VERSION
		return ! version_compare( PHP_VERSION, WOO_FEED_MIN_PHP_VERSION, '<' );
	}
}
if( ! function_exists( 'wooFeed_check_WC' ) ) {
	function wooFeed_check_WC(){
		return class_exists( 'WooCommerce', false );
	}
}
if( ! function_exists( 'wooFeed_is_WC_supported' ) ) {
	function wooFeed_is_WC_supported() {
		// Ensure WC is loaded before checking version
		return ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, WOO_FEED_MIN_WC_VERSION, '>=' ) );
	}
}
if( ! function_exists( 'woo_feed_wc_version_check' ) ) {
	/**
	 * Check WooCommerce Version
	 * @param string $version
	 * @return bool
	 */
	function woo_feed_wc_version_check( $version = '3.0' ) {
		// calling this function too early (before wc loaded) will not give correct output
        $plugins=get_plugins();
		if ( array_key_exists('woocommerce/woocommerce.php',$plugins)) {
            $currentVersion=$plugins['woocommerce/woocommerce.php']['Version'];
			if ( version_compare( $currentVersion, $version, ">=" ) ) {
				return true;
			}
		}
		return false;
	}
}
if( ! function_exists( 'wooFeed_Admin_Notices' ) ) {
	/**
	 * Display Admin Messages
	 * @hooked admin_notices
	 * @since 3.1.41
	 * @return void
	 */
	function wooFeed_Admin_Notices(){
		//@TODO Refactor this function with admin message class
		// WC Missing Notice..
		if ( ! wooFeed_check_WC() ) {
			$plugin_url = self_admin_url( 'plugin-install.php?s=woocommerce&tab=search&type=term' );
			/** @noinspection HtmlUnknownTarget */
			$plugin_url = sprintf( '<a href="%s">%s</a>', $plugin_url, esc_html__( 'WooCommerce', 'woocommerce' ) );
			$plugin_name = sprintf( '<code>%s</code>', esc_html__( 'WooCommerce Product Feed', 'woo-feed' ) );
			$wc_name = sprintf( '<code>%s</code>', esc_html__( 'WooCommerce', 'woocommerce' ) );
			$message    = sprintf( esc_html__( '%s requires %s to be installed and active. You can installed/activate %s here.', 'woo-feed' ), $plugin_name, $wc_name, $plugin_url );
			printf( '<div class="error"><p><strong>%1$s</strong></p></div>', $message );
		}
		if( wooFeed_check_WC() && ! wooFeed_is_WC_supported() ) {
			$plugin_url = self_admin_url( 'plugin-install.php?s=woocommerce&tab=search&type=term' );
			$wcVersion = defined( 'WC_VERSION' )  ? '<code>'.WC_VERSION.'</code>' : '<code>UNKNOWN</code>';
			$minVersion = '<code>'.WOO_FEED_MIN_WC_VERSION.'</code>';
			/** @noinspection HtmlUnknownTarget */
			$plugin_url = sprintf( '<a href="%s">%s</a>', $plugin_url, esc_html__( 'WooCommerce', 'woocommerce' ) );
			$plugin_name = sprintf( '<code>%s</code>', esc_html__( 'WooCommerce Product Feed', 'woo-feed' ) );
			$wc_name = sprintf( '<code>%s</code>', esc_html__( 'WooCommerce', 'woocommerce' ) );
			$message    = sprintf( esc_html__( '%1$s requires %2$s version %3$s or above and %4$s found. Please upgrade %2$s to the latest version here %5$s', 'woo-feed' ),
				$plugin_name, $wc_name, $minVersion, $wcVersion, $plugin_url );
			printf( '<div class="error"><p><strong>%1$s</strong></p></div>', $message );
		}
	}
}
if( ! function_exists( 'checkFTP_connection' ) ) {
	/**
	 * Verify if ftp module enabled
	 * @TODO improve module detection
	 * @return bool
	 */
	function checkFTP_connection() {
		return ( extension_loaded('ftp' ) || function_exists( 'ftp_connect' ) );
	}
}
if( ! function_exists( 'checkSFTP_connection' ) ) {
	/**
	 * Verify if ssh/sftp module enabled
	 * @TODO improve module detection
	 * @return bool
	 */
	function checkSFTP_connection() {
		return ( extension_loaded('ssh2' ) || function_exists( 'ssh2_connect' ) );
	}
}
if( ! function_exists( 'array_splice_assoc' ) ) {
	/**
	 * Array Splice Associative Array
	 * @see https://www.php.net/manual/en/function.array-splice.php#111204
	 * @param array $input
	 * @param int   $offset
	 * @param int   $length
	 * @param array $replacement
	 *
	 * @return array
	 */
	function array_splice_assoc( $input, $offset, $length, $replacement) {
		$replacement = (array) $replacement;
		$key_indices = array_flip(array_keys($input));
		if (isset($input[$offset]) && is_string($offset)) {
			$offset = $key_indices[$offset];
		}
		if (isset($input[$length]) && is_string($length)) {
			$length = $key_indices[$length] - $offset;
		}
		
		$input = array_slice($input, 0, $offset, TRUE)
		         + $replacement
		         + array_slice($input, $offset + $length, NULL, TRUE);
		return $input;
	}
}

if( ! function_exists( 'woo_feed_get_variable_visibility_options' ) ) {
	/**
	 * Get Variable visibility options for feed editor
	 * @return array
	 */
	function woo_feed_get_variable_visibility_options(){
		return apply_filters( 'woo_feed_variable_visibility_options', [
			'n' => __( 'Only Variable Products', 'woo-feed' ),
			'y'   => __( 'Only Product Variations', 'woo-feed' ),
			'both'   => __( 'Both Variable Products and Product Variations', 'woo-feed' ),
		] );
	}
}
if( ! function_exists( 'woo_feed_get_variable_price_options' ) ) {
	/**
	 * Get Variable price options for feed editor
	 * @return array
	 */
	function woo_feed_get_variable_price_options(){
		return apply_filters( 'woo_feed_variable_price_options', [
			'first' => __( 'First Variation Price', 'woo-feed' ),
			'max'   => __( 'Max Variation Price', 'woo-feed' ),
			'min'   => __( 'Min Variation Price', 'woo-feed' ),
		] );
	}
}
if( ! function_exists( 'woo_feed_get_variable_quantity_options' ) ) {
	/**
	 * Get Variable quantity options for feed editor
	 * @return array
	 */
	function woo_feed_get_variable_quantity_options(){
		return apply_filters( 'woo_feed_variable_quantity_options', [
			'first' => __( 'First Variation Quantity', 'woo-feed' ),
			'max'   => __( 'Max Variation Quantity', 'woo-feed' ),
			'min'   => __( 'Min Variation Quantity', 'woo-feed' ),
			'sum'   => __( 'Sum of Variation Quantity', 'woo-feed' ),
		] );
	}
}
if( ! function_exists( 'woo_feed_get_schedule_interval_options' ) ) {
	function woo_feed_get_schedule_interval_options(){
		return apply_filters( 'woo_feed_schedule_interval_options', [
			WEEK_IN_SECONDS         => esc_html__( '1 Week', 'woo-feed' ),
			DAY_IN_SECONDS          => esc_html__( '24 Hours', 'woo-feed' ),
			12 * HOUR_IN_SECONDS    => esc_html__( '12 Hours', 'woo-feed' ),
			6 * HOUR_IN_SECONDS     => esc_html__( '6 Hours', 'woo-feed' ),
			HOUR_IN_SECONDS         => esc_html__( '1 Hours', 'woo-feed' ),
			15 * MINUTE_IN_SECONDS  => esc_html__( '15 Minutes', 'woo-feed' ),
			5 * MINUTE_IN_SECONDS   => esc_html__( '5 Minutes', 'woo-feed' ),
		] );
	}
}
if( ! function_exists( 'wooFeed_is_multilingual' ) ) {
	/**
	 * Check if if site is multilingual
	 * @TODO add common language handler for all multilingual plugins
	 * @return bool
	 */
	function wooFeed_is_multilingual() {
		return ( class_exists('SitePress' ) );
	}
}
if( ! function_exists( 'woo_feed_sanitize_custom_template2_config_field' ) ) {
	/**
	 * filter callback to allow un-sanitized data for custom template 2 config textarea
	 * @param bool   $status
	 * @param string $key
	 *
	 * @return bool
	 */
	function woo_feed_sanitize_custom_template2_config_field( $status, $key ){
		if( $key === 'feed_config_custom2' ) return false;
		return $status;
	}
	add_filter( 'woo_feed_sanitize_form_fields', 'woo_feed_sanitize_custom_template2_config_field', 10, 2 );
}
// End of file helper.php