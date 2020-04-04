<?php
/**
 * Feed Docs Page Renderer
 * @version 1.0.0
 * @package WooFeed
 * @since 3.1.36
 */
if( ! function_exists( 'add_action' ) ) {
	die();
}
if( ! class_exists( 'WooFeedDocs' ) ) {
	class WooFeedDocs {
		/**
		 * Singleton instance holder
		 * @var WooFeedDocs
		 */
		private static $instance;
		
		/**
		 * Get Class Instance
		 * @return WooFeedDocs
		 */
		public static function getInstance() {
			if( self::$instance === null ) self::$instance = new self();
			return self::$instance;
		}
		private function __construct() {
			add_filter( 'removable_query_args', array( $this, 'filter_removable_query_args' ), 10, 1 );
		}
		
		/**
		 * Render Docs Page
		 * @see Woo_Feed_Admin::load_admin_pages()
		 * @return void
		 */
		function woo_feed_docs() {
			$faqs = $this->__get_feed_help();
			?>
			<div class="wrap wapk-admin wapk-feed-docs">
				<div class="wapk-section">
					<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
				</div>
				<?php if( ! empty( $faqs ) ) { ?>
					<div class="wapk-section">
						<?php foreach( $faqs as $faq ) { ?>
							<div class="postbox">
								<button type="button" class="handlediv" aria-expanded="true">
									<span class="screen-reader-text"><?php printf( esc_html__( 'Toggle panel: %s', 'woo-feed' ), $faq->title ) ?></span>
									<span class="toggle-indicator" aria-hidden="true"></span>
								</button>
								<h2 class="hndle"><?php if( isset( $faq->icon ) && ! empty( $faq->icon ) ) {
										?><span class="<?php printf( '%s%s',
											( strpos( $faq->icon, 'dashicons' ) !== false ) ? 'dashicons ' : '',
											esc_attr( $faq->icon ) ); ?>" aria-hidden="true"></span> <?php
									} ?><span><?php echo esc_html( $faq->title ); ?></span></h2>
								<div class="inside">
									<div class="main">
										<ul>
											<?php foreach( $faq->questions as $qa ) { ?>
												<li>
                                    <span class="<?php if( isset( $qa->icon ) ) {
	                                    printf( '%s%s',
		                                    ( strpos( $qa->icon, 'dashicons' ) !== false ) ? 'dashicons ' : '',
		                                    esc_attr( $qa->icon ) );
                                    } else { ?>dashicons dashicons-media-text<?php } ?>" aria-hidden="true"></span>
													<a href="<?php echo esc_url( $qa->link ); ?>" target="_blank"><?php echo esc_html( $qa->title ); ?></a>
												</li>
											<?php } ?>
										</ul>
									</div>
								</div>
							</div>
						<?php } ?>
					</div>
					<div class="clear"></div>
					<div class="wapk-section wapk-feed-cta">
						<div class="wapk-cta">
							<div class="wapk-cta-icon">
								<span class="dashicons dashicons-editor-help" aria-hidden="true"></span>
							</div>
							<div class="wapk-cta-content">
								<h2><?php _e( 'Still need help?', 'woo-feed' ); ?></h2>
								<p><?php _e( 'Have we not answered your question?<br>Don\'t worry, you can contact us for more information...', 'woo-feed') ?></p>
							</div>
							<div class="wapk-cta-action">
								<a href="https://webappick.com/support/" class="button button-primary" target="_blank"><?php _e( 'Get Support', 'woo-feed' ); ?></a>
							</div>
						</div>
					</div>
				<?php } else { ?>
					<div class="notice notice-warning">
						<p><?php printf(
								__( 'There\'s some problem loading the docs. Please Click <a href="%s">Here</a> To Fetch Again.', 'woo-feed' ),
								admin_url( 'admin.php?page=webappick-feed-docs&reload=1&_nonce=' . wp_create_nonce( 'webappick-feed-docs' ) )
							); ?></p>
						<p><?php printf( __( 'If the problem persist please contact <a href="%s">our support</a>.', 'woo-feed' ), 'https://webappick.com/support/' ); ?></p>
					</div>
				<?php } ?>
			</div>
			<?php
		}
		
		/**
		 * Get Docs Data
		 * @return array
		 */
		private function __get_feed_help() {
			// force fetch docs json.
			if( isset( $_GET['reload'], $_GET['_nonce'] ) && wp_verify_nonce( $_GET['_nonce'], 'webappick-feed-docs' ) ) {
				$help_docs = false;
			} else $help_docs = get_transient( 'webappick_feed_help_docs' );
			if ( false === $help_docs ) {
				// bitbucket cache-control: max-age=900 (15 minutes)
				$help_url  = 'https://api.bitbucket.org/2.0/snippets/woofeed/jLRxxB/files/woo-feed-docs.json';
				$response  = wp_remote_get( $help_url, array( 'timeout' => 15 ) );
				$help_docs = wp_remote_retrieve_body( $response );
				if ( is_wp_error( $response ) || $response['response']['code'] != 200 ) $help_docs = '[]';
				set_transient( 'webappick_feed_help_docs', $help_docs, 12 * HOUR_IN_SECONDS );
			}
			$help_docs = json_decode( trim( $help_docs ) );
			return $help_docs;
		}
		
		/**
		 * Add items to removable query args array
		 * @param array $removable_query_args
		 * @return array
		 */
		function filter_removable_query_args( $removable_query_args ) {
			global $pagenow, $plugin_page;
			if( $pagenow === 'admin.php' && $plugin_page === 'webappick-feed-docs' ) {
				$removable_query_args = array_merge( $removable_query_args, array( 'reload', '_nonce' ) );
			}
			return $removable_query_args;
		}
	}
}
// End of file class-woo-feed-docs.php