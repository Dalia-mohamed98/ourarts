<?php
/**
 * WooCommerce Product Feed Plugin Uses Tracker
 * Uses Webappick Insights for tracking
 * @since 3.1.41
 * @version 1.0.0
 */
if( ! defined( 'ABSPATH' ) ) die();
/**
 * Class WooFeedTracker
 */
final class WooFeedTracker {
	/**
	 * @var WebAppick\Insights
	 */
	public $insights = null;
	
	/**
	 * Class constructor
	 *
	 * @return void
	 * @since 1.0.0
	 *
	 */
	public function __construct() {
		if ( ! class_exists( 'WebAppick\AppServices\Client' ) ) {
			/** @noinspection PhpIncludeInspection */
			require_once WOO_FEED_LIBS_PATH . 'WebAppick/AppServices/Client.php';
		}
		
		$client = new WebAppick\AppServices\Client( '4e68acba-cbdc-476b-b4bf-eab176ac6a16', 'WooCommerce Product Feed', WOO_FEED_FREE_FILE );
		
		$this->insights = $client->insights();
		// Hide tracker notice until tracking server gets ready...
		// Tracker will not send data until user click Allow (optIn)
		$this->insights->hide_notice();
		/**
		 * @TODO count products by type
		 * @see wc_get_product_types();
		 */
		$this->insights->add_extra( [
			'products' => $this->insights->get_post_count( 'product' ),
		] );
		$this->insights->init_plugin();
		add_filter( 'WebAppick_endpoint', array( $this, 'uninstallStatEndPoint' ) );
	}
	
	/**
	 * Tracker API EndPoint
	 * @return string
	 */
	public function uninstallStatEndPoint() {
		return 'https://track.webappick.com/api/receive-uninstall-tracking';
	}
	
	/**
	 * Get number of orders
	 *
	 * @return integer
	 */
	protected function get_order_count() {
		global $wpdb;
		
		return (int) $wpdb->get_var( "SELECT count(ID) FROM $wpdb->posts WHERE post_type = 'shop_order' and post_status IN ('wc-completed', 'wc-processing', 'wc-on-hold', 'wc-refunded');" );
	}
	
}
// End of file class-woo-feed-tracker.php
