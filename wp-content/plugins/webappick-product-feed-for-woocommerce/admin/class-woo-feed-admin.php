<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://webappick.com/
 * @since      1.0.0
 *
 * @package    Woo_Feed
 * @subpackage Woo_Feed/admin
 * @author     Ohidul Islam <wahid@webappick.com>
 */
class Woo_Feed_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $woo_feed The ID of this plugin.
     */
    private $woo_feed;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     *
     * @param      string $woo_feed The name of this plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($woo_feed, $version) {

        $this->woo_feed = $woo_feed;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     * @param string $hook
     * @since    1.0.0
     */
    public function enqueue_styles( $hook ) {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in woo_feed_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The woo_feed_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
	
	    wp_register_style( 'selectize',  plugin_dir_url(__FILE__) . 'css/selectize.css', array(),$this->version );
	    wp_register_style( 'slick',  plugin_dir_url(__FILE__) . 'css/slick.css', array(),$this->version );
	    wp_register_style( 'slick-theme',  plugin_dir_url(__FILE__) . 'css/slick-theme.css', array(),$this->version );
	    $mainDeps = array( 'selectize' );
	    if( $hook == 'woo-feed_page_webappick-feed-pro-vs-free' ) {
		    $mainDeps = array_merge( $mainDeps, array('slick', 'slick-theme') );
	    }
	    wp_register_style( $this->woo_feed, plugin_dir_url(__FILE__) . 'css/woo-feed-admin.css', $mainDeps, $this->version, 'all' );
	    
	    wp_enqueue_style( $this->woo_feed );
	
    }

    /**
     * Register the JavaScript for the admin area.
     * @param string $hook
     * @since    1.0.0
     */
    public function enqueue_scripts( $hook ) {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Woo_Feed_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The woo_feed_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        
	    wp_register_script( "jquery-selectize", plugin_dir_url(__FILE__) . 'js/selectize.min.js', array( 'jquery' ), $this->version, false );
        wp_register_script( "jquery-validate", plugin_dir_url(__FILE__) . 'js/jquery.validate.min.js', array( 'jquery' ), $this->version, false );
        wp_register_script( "jquery-validate-additional-methods", plugin_dir_url(__FILE__) . 'js/additional-methods.min.js', array( 'jquery', 'jquery-validate' ), $this->version, false );
        wp_register_script( "jquery-sortable", plugin_dir_url(__FILE__) . 'js/jquery-sortable.js', array( 'jquery' ), $this->version, false );
        wp_register_script( "jquery-slick", plugin_dir_url(__FILE__) . 'js/slick.js', array( 'jquery' ), $this->version, false );
	    if( ! wp_script_is( 'clipboard', 'registered' ) ) {
		    wp_register_script( 'clipboard', plugin_dir_url(__FILE__) . 'js/clipboard.min.js', [], '2.0.4', false);
	    }
	    
	    $mainDeps = array( 'jquery', 'clipboard', 'jquery-selectize', 'jquery-sortable', 'jquery-validate', 'jquery-validate-additional-methods' );
	    if( $hook == 'woo-feed_page_webappick-feed-pro-vs-free' ) {
		    $mainDeps[] = 'jquery-slick';
	    }
	    
        wp_register_script($this->woo_feed, plugin_dir_url(__FILE__) . 'js/woo-feed-admin.js', $mainDeps, $this->version, false);

        $wpf_feed_nonce = wp_create_nonce('wpf_feed_nonce');
        wp_localize_script($this->woo_feed, 'wpf_ajax_obj', array(
            'wpf_ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => $wpf_feed_nonce,
        ));

        wp_enqueue_script($this->woo_feed);

    }
	
	/**
	 * Add Go to Pro and Documentation link
	 * @param array $links
	 * @return array
	 */
	public function woo_feed_plugin_action_links( $links ) {
		
		$links[] = '<a style="color: #389e38; font-weight: bold;" href="https://webappick.com/plugin/woocommerce-product-feed-pro/?utm_source=freePlugin&utm_medium=go_premium&utm_campaign=free_to_pro&utm_term=wooFeed" target="_blank">' . __( 'Get Pro', 'woo-feed' ) . '</a>';
		/** @noinspection HtmlUnknownTarget */
		$links[] = sprintf( '<a style="color:#ce7304; font-weight: bold;" href="%s">%s</a>', esc_url( admin_url( 'admin.php?page=webappick-feed-docs' ) ), __( 'Docs', 'woo-feed' ) );
		/** @noinspection HtmlUnknownTarget */
		$links[] = sprintf( '<a href="%s">%s</a>', esc_url( admin_url( 'admin.php?page=webappick-feed-settings' ) ), __( 'Settings', 'woo-feed' ) );
		return $links;
	}

    /**
     * Register the Plugin's Admin Pages for the admin area.
     *
     * @since    1.0.0
     */
    public function load_admin_pages() {
        /**
         * This function is provided for making admin pages into admin area.
         *
         * An instance of this class should be passed to the run() function
         * defined in WOO_FEED_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The WOO_FEED_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        if ( function_exists('add_options_page') ) {
            add_menu_page( __('Woo Feed', 'woo-feed'), __('Woo Feed', 'woo-feed'), 'manage_woocommerce', 'webappick-manage-feeds', 'woo_feed_manage_feed', 'dashicons-rss' );
	        add_submenu_page( 'webappick-manage-feeds', __('Manage Feeds', 'woo-feed'), __('Manage Feeds', 'woo-feed'), 'manage_woocommerce', 'webappick-manage-feeds', 'woo_feed_manage_feed' );
	        add_submenu_page( 'webappick-manage-feeds', __('Make Feed', 'woo-feed'), __('Make Feed', 'woo-feed'), 'manage_woocommerce', 'webappick-new-feed', 'woo_feed_generate_feed' );
	        add_submenu_page( 'webappick-manage-feeds', __('Settings', 'woo-feed'), __('Settings', 'woo-feed'), 'manage_woocommerce', 'webappick-feed-settings', 'woo_feed_config_feed' );
	        add_submenu_page( 'webappick-manage-feeds', __('Documentation', 'woo-feed'), '<span class="woo-feed-docs">' . __('Docs', 'woo-feed') . '</span>', 'manage_woocommerce', 'webappick-feed-docs', array( WooFeedDocs::getInstance(), 'woo_feed_docs' ) );
	        add_submenu_page( 'webappick-manage-feeds', __('Premium', 'woo-feed'), __('Premium', 'woo-feed'), 'manage_woocommerce', 'webappick-feed-pro-vs-free', 'woo_feed_pro_vs_free' );
        }
    }

	/**
	 * Redirect user to with new menu slug (if user browser any bookmarked url)
	 * @since 3.1.7
	 * @return void
	 */
	public function handle_old_menu_slugs() {
		global $pagenow;
		// redirect user to new old slug => new slug
		$redirect_to = array(
			'webappick-product-feed-for-woocommerce/admin/class-woo-feed-admin.php' => 'webappick-new-feed',
			'woo_feed_manage_feed' => 'webappick-manage-feeds',
			'woo_feed_config_feed' => 'webappick-feed-settings',
			'woo_feed_pro_vs_free' => 'webappick-feed-pro-vs-free',
		);
		if( $pagenow === 'admin.php' && isset( $_GET['page'] ) && ! empty( $_GET['page'] ) ) {
			foreach( $redirect_to as $from => $to ) {
				if( $_GET['page'] !== $from ) continue;
				wp_redirect( admin_url( 'admin.php?page=' . $to ), 301 );
				die();
			}
		}
	}
}
