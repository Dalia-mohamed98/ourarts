<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://webappick.com
 * @since             1.0.0
 * @package           Woo_Feed
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce Product Feed
 * Plugin URI:        https://webappick.com/
 * Description:       This plugin generate WooCommerce product feed for Shopping Engines like Google Shopping,Facebook Product Feed,eBay,Amazon,Idealo and many more..
 * Version:           3.1.48
 * Author:            WebAppick
 * Author URI:        https://webappick.com/
 * License:           GPL v2
 * License URI:       http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain:       woo-feed
 * Domain Path:       /languages
 *
 * WP Requirement & Test
 * Requires at least: 4.4
 * Tested up to: 5.3
 *
 * WC Requirement & Test
 * WC requires at least: 3.2
 * WC tested up to: 3.8
 * 
 */
// Exit if accessed directly
if (!defined('ABSPATH')) die();
// Constants
if( ! defined( 'WOO_FEED_VERSION' ) ) {
	/**
	 * Plugin Version
	 * @var string
	 */
	define( 'WOO_FEED_VERSION', '3.1.48' );
}
if( ! defined( 'WOO_FEED_FREE_FILE') ) {
	/**
	 * Plugin Base File
	 * @since 3.1.41
	 * @var string
	 */
	define( 'WOO_FEED_FREE_FILE', __FILE__ );
}
if( ! defined( 'WOO_FEED_PATH' ) ) {
	/**
	 * Plugin Path with trailing slash
	 * @var string dirname( __FILE__ )
	 */
	/** @define "WOO_FEED_PATH" "./" */
	define( 'WOO_FEED_PATH', plugin_dir_path( __FILE__ ) );
}
if( ! defined( 'WOO_FEED_ADMIN_PATH' ) ) {
	/**
	 * Admin File Path with trailing slash
	 * @var string
	 */
	define( 'WOO_FEED_ADMIN_PATH', WOO_FEED_PATH . 'admin/' );
}
if( ! defined( 'WOO_FEED_LIBS_PATH' ) ) {
	/**
	 * Admin File Path with trailing slash
	 * @var string
	 */
	define( 'WOO_FEED_LIBS_PATH', WOO_FEED_PATH . 'libs/' );
}
if( ! defined( 'WOO_FEED_PLUGIN_URL' ) ) {
	/**
	 * Plugin Directory URL
	 * @var string
	 * @since 3.1.37
	 */
	define( 'WOO_FEED_PLUGIN_URL', trailingslashit( plugin_dir_url(__FILE__) ) );
}
if( ! defined( 'WOO_FEED_MIN_PHP_VERSION' ) ) {
	/**
	 * Minimum PHP Version Supported
	 * @var string
	 * @since 3.1.41
	 */
	define( 'WOO_FEED_MIN_PHP_VERSION', '5.6' );
}
if( ! defined( 'WOO_FEED_MIN_WC_VERSION' ) ) {
	/**
	 * Minimum PHP Version Supported
	 * @var string
	 * @since 3.1.45
	 */
	define( 'WOO_FEED_MIN_WC_VERSION', '3.2' );
}
if( ! defined( 'WOO_FEED_PLUGIN_BASE_NAME' ) ) {
	/**
	 * Plugin Base name..
	 * @var string
	 * @since 3.1.41
	 */
	define( 'WOO_FEED_PLUGIN_BASE_NAME', plugin_basename(__FILE__) );
}

/**
 * Load Helper functions
 */
require_once WOO_FEED_PATH . 'includes/helper.php';

/**
 * Load Uses Tracker
 */
require_once WOO_FEED_PATH . 'includes/classes/class-woo-feed-tracker.php';

if( ! class_exists( 'Woo_Feed' ) ) {
	/**
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
	 */
	require_once WOO_FEED_PATH . 'includes/class-woo-feed.php';
}

if( ! function_exists( 'activate_woo_feed' ) ) {
	/**
	 * The code that runs during plugin activation.
	 * This action is documented in includes/class-woo-feed-activator.php
	 * @return void
	 */
	function activate_woo_feed() {
		// bail if unsupported php version
		if( ! wooFeed_is_supported_php() ) {
			echo '<div class="notice error"><p>'. sprintf( __( 'The Minimum PHP Version Requirement for <b>WooCommerce Product Feed</b> is %s. You are Running PHP %s', 'woo-feed' ), WOO_FEED_MIN_PHP_VERSION, phpversion() ) .'</p></div>';
			die();
		}
		if( wooFeed_is_plugin_active( "webappick-product-feed-for-woocommerce/woo-feed.php" ) ) {
			echo '<div class="notice error"><p>'. __( 'Please deactivate the <b>WooCommerce Product Feed Pro</b> version to activate free version again.', 'woo-feed' ) .'</p></div>';
			die();
		}
		require_once WOO_FEED_PATH . 'includes/class-woo-feed-activator.php';
		Woo_Feed_Activator::activate();
	}
	register_activation_hook(__FILE__, 'activate_woo_feed');
}
if( ! function_exists( 'deactivate_woo_feed' ) ) {
	/**
	 * The code that runs during plugin deactivation.
	 * This action is documented in includes/class-woo-feed-deactivator.php
	 */
	function deactivate_woo_feed() {
		require_once WOO_FEED_PATH . 'includes/class-woo-feed-deactivator.php';
		Woo_Feed_Deactivator::deactivate();
	}
	register_deactivation_hook(__FILE__, 'deactivate_woo_feed');
}
if( ! function_exists( 'run_woo_feed' ) ) {
	/**
	 * Begins execution of the plugin.
	 *
	 * Since everything within the plugin is registered via hooks,
	 * then kicking off the plugin from this point in the file does
	 * not affect the page life cycle.
	 *
	 * @since    1.0.0
	 */
	function run_woo_feed() {
		$plugin = new Woo_Feed();
		/**
		 * Ensure Feed Plugin runs only if WooCommerce loaded (installed and activated)
		 * @since 3.1.41
		 */
		add_action( 'woocommerce_loaded', array( $plugin, 'run' ) );
		add_action( 'admin_notices', 'wooFeed_Admin_Notices' );
		new WooFeedTracker();
	}
	run_woo_feed();
}
if( ! function_exists( 'custom_cron_job_custom_recurrence' ) ) {
	# Custom Cron Recurrences
	function custom_cron_job_custom_recurrence($schedules)
	{
		$interval = get_option('wf_schedule');
		$schedules['woo_feed_corn'] = array(
			'display' => __('Woo Feed Update Interval', 'woo-feed'),
			'interval' => $interval,
		);

		return $schedules;
	}
	add_filter('cron_schedules', 'custom_cron_job_custom_recurrence');
}
if( ! function_exists( 'feed_merchant_view' ) ) {
    # Load Feed Templates
	function feed_merchant_view()
	{
		check_ajax_referer('wpf_feed_nonce');
		/** @noinspection PhpUnusedLocalVariableInspection */
		$dropDown = new Woo_Feed_Dropdown();
		/** @noinspection PhpUnusedLocalVariableInspection */
		$product = new Woo_Feed_Products();
		/** @noinspection PhpUnusedLocalVariableInspection */
		$attributes = new Woo_Feed_Default_Attributes();
		/** @noinspection PhpUnusedLocalVariableInspection */
		$merchant = $provider   = sanitize_text_field($_POST['merchant']);
		if(strpos($merchant,'amazon')!==false){
			include WOO_FEED_ADMIN_PATH . "partials/templates/amazon_add-feed.php";
		} else if( $merchant == 'smartly.io' ) {
			include WOO_FEED_ADMIN_PATH . "partials/templates/google_add-feed.php";
		} else{
			/** @noinspection PhpIncludeInspection */
			include WOO_FEED_ADMIN_PATH . "partials/templates/" . $merchant . "_add-feed.php";
		}
		die();
	}
	add_action('wp_ajax_get_feed_merchant', 'feed_merchant_view');
}
if( ! function_exists( 'woo_feed_add_update' ) ) {
	/**
	 * Update Feed Information
	 * @param array $info
	 * @param string $name
	 * @return string|bool
	 */
	function woo_feed_add_update($info = array(), $name = "") {
		set_time_limit(0);
		if (count($info) && isset($info['provider'])) {
			# GEt Post data
			if ($info['provider'] == 'google' || $info['provider'] == 'adroll' || $info['provider'] == 'smartly.io') {
				$merchant = "Woo_Feed_Google";
			} elseif ($info['provider'] == 'pinterest') {
				$merchant = "Woo_Feed_Pinterest";
			} elseif ($info['provider'] == 'facebook') {
				$merchant = "Woo_Feed_Facebook";
			}elseif (strpos($info['provider'],'amazon') !==FALSE) {
				$merchant = "Woo_Feed_Amazon";
			}  else {
				$merchant = "Woo_Feed_Custom";
			}


			$feedService = sanitize_text_field($info['provider']);
			$fileName = str_replace(" ", "", sanitize_text_field($info['filename']));
			$type = sanitize_text_field($info['feedType']);

			$feedRules = $info;

			# Get Feed info
			$products = new Woo_Generate_Feed($merchant, $feedRules);
			$getString = $products->getProducts();

			if($type=='csv'){
				$csvHead[0]=$getString['header'];
				if(!empty($csvHead) && !empty($getString['body'])){
					$string=array_merge($csvHead,$getString['body']);
				}else{
					$string=array();
				}
			}else{
				$string=$getString['header'].$getString['body'].$getString['footer'];
			}

			# Check If any products founds
			if ($string && !empty($string)) {

				$upload_dir = wp_upload_dir();
				$base = $upload_dir['basedir'];

				# Save File
				$path = $base . "/woo-feed/" . $feedService . "/" . $type;
				$file = $path . "/" . $fileName . "." . $type;
				$save = new Woo_Feed_Savefile();
				if ($type == "csv") {
					$saveFile = $save->saveCSVFile($path, $file, $string, $info);
				} else {
					$saveFile = $save->saveFile($path, $file, $string);
				}

				# FTP File Upload Info
				$ftpHost = sanitize_text_field($info['ftphost']);
				$ftpUser = sanitize_text_field($info['ftpuser']);
				$ftpPassword = sanitize_text_field($info['ftppassword']);
				$ftpPath = sanitize_text_field($info['ftppath']);
				$ftpPort = isset($info['ftpport']) && !empty($info['ftpport'])?sanitize_text_field($info['ftpport']):21;
				$ftpEnabled = sanitize_text_field($info['ftpenabled']);

				$ftporsftp  = isset($info['ftporsftp'])?sanitize_text_field($info['ftporsftp']):"ftp";


				try{
					if ($type == "json") {
						$type="csv";
					}
					# Upload file to ftp server
					if ($ftpEnabled) {
						if($ftporsftp == "ftp") {
							$ftp = new FTPClient();
							if ($ftp->connect($ftpHost, $ftpUser, $ftpPassword,false,$ftpPort)){
								$ftp->uploadFile($file, $fileName . "." . $type);
							}
						} elseif ($ftporsftp == "sftp" && extension_loaded ( 'ssh2' )) {
							$sftp = new SFTPConnection($ftpHost, $ftpPort);
							$sftp->login($ftpUser, $ftpPassword);
							$sftp->uploadFile($file, "/".$fileName . "." . $type);
						}
					}
				}catch (Exception $e){

				}

				# Save Info into database
				$url = $upload_dir['baseurl'] . "/woo-feed/" . $feedService . "/" . $type . "/" . $fileName . "." . $type;
				$feedInfo = array(
					'feedrules' => $feedRules,
					'url' => $url,
					'last_updated' => date("Y-m-d H:i:s"),
					'status'=>1
				);


				if (!empty($name) && $name != "wf_feed_" . $fileName) {
					delete_option($name);
				}

				$update = update_option('wf_feed_' . $fileName, serialize($feedInfo));
				if ($saveFile) {
					$getInfo = unserialize(get_option('wf_feed_' . $fileName));
					$url = $getInfo['url'];
					return $url;
				} else {
					return false;
				}
			}
		}
		return false;
	}
}
if( ! function_exists( 'woo_feed_array_sanitize' ) ) {
	/**
	 * Sanitize array post
	 *
	 * @param $array
	 *
	 * @return array
	 */
	function woo_feed_array_sanitize($array)
	{
		$newArray = array();
		if (count($array)) {
			foreach ($array as $key => $value) {
				if (is_array($value)) {
					foreach ($value as $key2 => $value2) {
						if (is_array($value2)) {
							foreach ($value2 as $key3 => $value3) {
								$newArray[$key][$key2][$key3] = sanitize_text_field($value3);
							}
						} else {
							$newArray[$key][$key2] = sanitize_text_field($value2);
						}
					}
				} else {
					$newArray[$key] = sanitize_text_field($value);
				}
			}
		}
		return $newArray;
	}
}
#======================================================================================================================*
#
#   Ajax Feed Making Development Start
#
#======================================================================================================================*
if( ! function_exists( 'woo_feed_get_product_information' ) ) {
	/**
	 * Count Total Products
	 */
	function woo_feed_get_product_information(){
		check_ajax_referer('wpf_feed_nonce');

		if(woo_feed_wc_version_check(3.2)){
			$query=new WC_Product_Query(array(
				'limit'   => -1,
				'status'  => 'publish',
				'orderby' => 'date',
				'order'   => 'DESC',
				'type'    => array('variable','variation','simple','grouped','external'),
				'return'  => 'ids',
			));
			$products=$query->get_products();
			$totalProducts=count($products);
		}else{
			$products=wp_count_posts('product');
			$variations=wp_count_posts('product_variation');
			$totalProducts=$products->publish + $variations->publish;
		}


		$data=array('product'=>$totalProducts);

		if($totalProducts>0){
			$data['success']=true;
			wp_send_json_success($data);
		}else{
			$data['success']=false;
			$data['message']='No products found. Add product before generating the feed.';
			wp_send_json_error($data);
		}

		wp_die();
	}
	add_action('wp_ajax_get_product_information', 'woo_feed_get_product_information');
}
if( ! function_exists( 'woo_feed_generate_feed_data' ) ) {
	function woo_feed_generate_feed_data($info){

		try{
			if (count($info) && isset($info['provider'])) {
				# GEt Post data
				if ($info['provider'] == 'google' || $info['provider'] == 'adroll' || $info['provider'] == 'smartly.io') {
					$merchant = "Woo_Feed_Google";
				} elseif ($info['provider'] == 'pinterest') {
					$merchant = "Woo_Feed_Pinterest";
				} elseif ($info['provider'] == 'facebook') {
					$merchant = "Woo_Feed_Facebook";
				}elseif (strpos($info['provider'],'amazon') !==FALSE) {
					$merchant = "Woo_Feed_Amazon";
				}elseif ($info['provider'] == 'custom2') {
					$merchant = "Woo_Feed_Custom2";
				} else {
					$merchant = "Woo_Feed_Custom";
				}

				$feedService = sanitize_text_field($info['provider']);
				$fileName = str_replace(" ", "", sanitize_text_field($info['filename']));
				$type = sanitize_text_field($info['feedType']);

				$feedRules = $info;

				# Get Feed info
				$products = new Woo_Generate_Feed($merchant, $feedRules);
				$feed = $products->getProducts();
				if(isset($feed['body']) && !empty($feed['body'])){
					$feedHeader="wf_store_feed_header_info_".$fileName;
					$feedBody="wf_store_feed_body_info_".$fileName;
					$feedFooter="wf_store_feed_footer_info_".$fileName;
					$prevFeed= woo_feed_get_batch_feed_info($feedService,$type,$feedBody);
					if($prevFeed){
						if($type=='csv'){
							if(!empty($prevFeed)){
								$newFeed=array_merge($prevFeed, $feed['body']);
								woo_feed_save_batch_feed_info($feedService,$type,$newFeed,$feedBody);
							}
						}else{
							$newFeed=$prevFeed.$feed['body'];
							woo_feed_save_batch_feed_info($feedService,$type,$newFeed,$feedBody);
						}

					}else{
						woo_feed_save_batch_feed_info($feedService,$type,$feed['body'],$feedBody);
					}
					woo_feed_save_batch_feed_info($feedService,$type,$feed['header'],$feedHeader);
					woo_feed_save_batch_feed_info($feedService,$type,$feed['footer'],$feedFooter);

					return true;
				}else{
					return false;
				}
			}
		}catch (Exception $e){
			return false;
		}
		return false;
	}
}
if( ! function_exists( 'woo_feed_save_batch_feed_info' ) ) {
	/**
	 * Save batch feed info into file
	 * @param $feedService
	 * @param $type
	 * @param $string
	 * @param $fileName
	 * @return bool
	 */
	function woo_feed_save_batch_feed_info($feedService,$type,$string,$fileName){

		$upload_dir = wp_upload_dir();
		$base = $upload_dir['basedir'];
		$ext=$type;
		if ($type == "csv") {
			$string=json_encode($string);
			$ext="json";
		}
		# Save File
		$path = $base . "/woo-feed/" . $feedService . "/" . $type;
		$file = $path . "/" . $fileName . "." . $ext;
		$save = new Woo_Feed_Savefile();
		return $save->saveFile($path, $file, $string);
	}
}
if( ! function_exists( 'woo_feed_get_batch_feed_info' ) ) {
	function woo_feed_get_batch_feed_info($feedService,$type,$fileName){

		$upload_dir = wp_upload_dir();
		$base = $upload_dir['basedir'];
		$ext=$type;
		if ($type == "csv") {
			$ext="json";
		}
		# Save File
		$path = $base . "/woo-feed/" . $feedService . "/" . $type;
		$file = $path . "/" . $fileName . "." . $ext;

		if ($type == "csv" && file_exists($file)) {
			return (file_get_contents($file))?json_decode(file_get_contents($file),true):false;
		}else if(file_exists($file)){
			return file_get_contents($file);
		}
		return false;
	}
}
if( ! function_exists( 'woo_feed_unlink_tempFiles' ) ) {
	/**
	 * Remove temporary feed files
	 * @param $files
	 * @return bool
	 */
	function woo_feed_unlink_tempFiles($files){
		if(!empty($files)){
			foreach ($files as $key=>$file){
				if(file_exists($file)){
					unlink($file);
				}
			}
			return true;
		}
		return false;
	}
}
if( ! function_exists( 'woo_feed_make_batch_feed' ) ) {
	function woo_feed_make_batch_feed(){
		check_ajax_referer('wpf_feed_nonce');

		$limit=sanitize_text_field($_POST['limit']);
		$offset=sanitize_text_field($_POST['offset']);
		$feedName=sanitize_text_field(str_replace("wf_feed_","",$_POST['feed']));
		$feedInfo=get_option($feedName);

		if(!$feedInfo){
			$getFeedConfig=unserialize(get_option($feedName));
			$feedInfo=$getFeedConfig['feedrules'];
		}

		if($offset==0){
			$fileName = str_replace(" ", "",$feedInfo['filename']);
			$type = $feedInfo['feedType'];
			$feedService = $feedInfo['provider'];
			if ($type == "csv") {
				$type="json";
			}

			$upload_dir = wp_upload_dir();
			$base = $upload_dir['basedir'];
			$path = $base . "/woo-feed/" . $feedService . "/" . $type;

			$tempFiles['headerFile']    =$path . "/" . "wf_store_feed_header_info_".$fileName . "." . $type;
			$tempFiles['bodyFile']      =$path . "/" . "wf_store_feed_body_info_".$fileName . "." . $type;
			$tempFiles['footerFile']    =$path . "/" . "wf_store_feed_footer_info_".$fileName . "." . $type;

			woo_feed_unlink_tempFiles($tempFiles);
		}


		$feedInfo['Limit']=$limit;
		$feedInfo['Offset']=$offset;

		$feed_data=woo_feed_generate_feed_data($feedInfo);
		if($feed_data){
			$data=array(
				"success"=>true,
				"products"=>"yes",
			);
			wp_send_json_success($data);
			die();
		}else{
			$data=array(
				"success"=>true,
				"products"=>"no",
			);
			wp_send_json_success($data);
			die();
		}
	}
	add_action('wp_ajax_make_batch_feed', 'woo_feed_make_batch_feed');
	add_action('wp_ajax_nopriv_make_batch_feed', 'woo_feed_make_batch_feed');
}
if( ! function_exists( 'woo_feed_save_feed_file' ) ) {
	add_action('wp_ajax_save_feed_file', 'woo_feed_save_feed_file');
	add_action('wp_ajax_nopriv_save_feed_file', 'woo_feed_save_feed_file');
	function woo_feed_save_feed_file(){

		check_ajax_referer('wpf_feed_nonce');
		$feed = str_replace("wf_feed_", "",$_REQUEST['feed']);
		$info = get_option($feed);
		if(!$info){
			$getInfo = unserialize(get_option($_REQUEST['feed']));
			$info = $getInfo['feedrules'];
		}

		$feedService = $info['provider'];
		$fileName = str_replace(" ", "",$info['filename']);
		$type = $info['feedType'];

		$feedHeader=woo_feed_get_batch_feed_info($feedService,$type,"wf_store_feed_header_info_".$fileName);
		$feedBody=woo_feed_get_batch_feed_info($feedService,$type,"wf_store_feed_body_info_".$fileName);
		$feedFooter=woo_feed_get_batch_feed_info($feedService,$type,"wf_store_feed_footer_info_".$fileName);

		if($type=='csv'){
			$csvHead[0]=$feedHeader;
			if(!empty($csvHead) && !empty($feedBody)){
				$string=array_merge($csvHead,$feedBody);
			}else{
				$string=array();
			}
		}else{
			$string=$feedHeader.$feedBody.$feedFooter;
		}

		$upload_dir = wp_upload_dir();
		$base = $upload_dir['basedir'];
		$path = $base . "/woo-feed/" . $feedService . "/" . $type;
		$saveFile = false;
		# Check If any products founds
		if ($string && !empty($string)) {
			# Save File
			$file = $path . "/" . $fileName . "." . $type;
			$save = new Woo_Feed_Savefile();
			if ($type == "csv") {
				$saveFile = $save->saveCSVFile($path, $file, $string, $info);
			} else {
				$saveFile = $save->saveFile($path, $file, $string);
			}
		}else{
			$data=array("success"=>false,"message"=>"No Product Found with your feed configuration. Please configure the feed properly.");
			wp_send_json_error($data);
			wp_die();
		}


		# Save Info into database
		$url = $upload_dir['baseurl'] . "/woo-feed/" . $feedService . "/" . $type . "/" . $fileName . "." . $type;
		$feedInfo = array(
			'feedrules' => $info,
			'url' => $url,
			'last_updated' => date("Y-m-d H:i:s"),
		);

		$feedOldInfo = unserialize( get_option( "wf_feed_".$fileName ) );
		if( isset( $feedOldInfo['status'] ) ) {
			$feedInfo['status'] = $feedOldInfo['status'];
		}else{
			$feedInfo['status']=1;
		}

		if (!empty($name) && $name != "wf_feed_" . $fileName) {
			delete_option($name);
		}

		//delete_option("wf_config".$fileName);
		delete_option("wf_store_feed_header_info_".$fileName);
		delete_option("wf_store_feed_body_info_".$fileName);
		delete_option("wf_store_feed_footer_info_".$fileName);

		if ($type == "csv") $type="json";

		# Remove Temp feed files
		$tempFiles['headerFile']    =$path . "/" . "wf_store_feed_header_info_".$fileName . "." . $type;
		$tempFiles['bodyFile']      =$path . "/" . "wf_store_feed_body_info_".$fileName . "." . $type;
		$tempFiles['footerFile']    =$path . "/" . "wf_store_feed_footer_info_".$fileName . "." . $type;

		woo_feed_unlink_tempFiles($tempFiles);
		update_option('wf_feed_' . $fileName, serialize($feedInfo));
		if ($saveFile) {

			# FTP File Upload Info
			$ftpHost        = $info['ftphost'];
			$ftpUser        = $info['ftpuser'];
			$ftpPassword    = $info['ftppassword'];
			$ftpPath        = $info['ftppath'];
			$ftpEnabled     = $info['ftpenabled'];
			$ftporsftp      = isset($info['ftporsftp'])?$info['ftporsftp']:"ftp";
			$ftpPort        = isset($info['ftpport']) && !empty($info['ftpport'])?$info['ftpport']:21;
			try{
				if ($type == "json") $type="csv";
				# Upload file to ftp server
				if ($ftpEnabled) {
					if($ftporsftp == "ftp") {
						$ftp = new FTPClient();
						if ($ftp->connect($ftpHost, $ftpUser, $ftpPassword,false,$ftpPort)){
							$ftp->uploadFile($file, $fileName . "." . $type);
						}
					} elseif ($ftporsftp == "sftp" && extension_loaded ( 'ssh2' )) {
						$sftp = new SFTPConnection($ftpHost, $ftpPort);
						$sftp->login($ftpUser, $ftpPassword);
						$sftp->uploadFile($file, "/".$fileName . "." . $type);
					}
				}
			}catch (Exception $e){}
			$getInfo = unserialize(get_option('wf_feed_' . $fileName));
			$url = $getInfo['url'];

			$cat=woo_feed_check_google_category($feedInfo);

			$data=array(
				"info"=>$feedInfo,
				"url"=>$url,
				"cat"=>$cat,
				"message"=>"Feed Making Complete",
			);
			wp_send_json_success($data);
		} else {
			$data=array("success"=>false,"message"=>"Failed to save feed file. Please confirm that your WordPress directory have Read and Write permission.");
			wp_send_json_error($data);
		}

		wp_die();
	}
}
if( ! function_exists( 'woo_feed_check_google_category' ) ) {
	/** Check google product category added or not after making a feed.
	 * @param $feedInfo
	 * @return string
	 */
	function woo_feed_check_google_category($feedInfo){

		# Check Google Product Category for Google & Facebook Template and show message
		$checkCategory=$feedInfo['feedrules']['mattributes'];
		$checkCategoryType=$feedInfo['feedrules']['type'];
		$merchant=$feedInfo['feedrules']['provider'];
		$cat="yes";

		if(in_array($merchant,array('google','facebook')) && in_array("current_category",$checkCategory)){
			$catKey=array_search('current_category',$checkCategory);
			if($checkCategoryType[$catKey]=="pattern"){
				$checkCategoryValue=$feedInfo['feedrules']['default'];
			}else{
				$checkCategoryValue=$feedInfo['feedrules']['attributes'];
			}

			if(empty($checkCategoryValue[$catKey])){
				$cat="no";
			}
		}
		return $cat;
	}
}
if( ! function_exists( 'woo_feed_get_file_dir' ) ) {
	/**
	 * Get Feed Directory
	 * @param string $provider
	 * @param string $feedType
	 * @return string
	 */
	function woo_feed_get_file_dir( $provider, $feedType ) {
		$upload_dir = wp_upload_dir();
		$base = $upload_dir['basedir'];
		return $base . "/woo-feed/" . $provider . "/" . $feedType;
	}
}
if( ! function_exists( 'woo_feed_sanitize_form_fields' ) ) {
	/**
	 * Sanitize Form Fields ($_POST Array)
	 * @param array $data
	 * @return array
	 */
	function woo_feed_sanitize_form_fields( $data ) {
		foreach( $data as $k => $v ) {
			if( true === apply_filters( 'woo_feed_sanitize_form_fields', true, $k, $v, $data ) ) {
				if( is_array( $v ) ) {
                    $v = woo_feed_sanitize_form_fields( $v );
                }else{
					//$v = sanitize_text_field( $v ); #TODO should not trim Prefix and Suffix field
				}
			}
			$data[$k] = apply_filters( 'woo_feed_sanitize_form_fields', $v, $k );
		}
		return $data;
	}
}
if( ! function_exists( 'woo_feed_unique_feed_slug' ) ) {
	/**
	 * Generate Unique slug for feed.
	 * @see wp_unique_post_slug()
	 * @param string $slug
     * @param string $prefix
	 * @param int    $feedId
	 * @return string
	 */
	function woo_feed_unique_feed_slug( $slug, $prefix = '', $feedId = null ) {
		global $wpdb;
		/** @noinspection SpellCheckingInspection */
		$disallowed = array( 'siteurl', 'home', 'blogname', 'blogdescription', 'users_can_register', 'admin_email' );
		if( $feedId && $feedId > 0 ) {
			$checkSql  = "SELECT option_name FROM $wpdb->options WHERE option_name = %s AND option_id != %d LIMIT 1";
			$nameCheck = $wpdb->get_var( $wpdb->prepare( $checkSql, $prefix .$slug, $feedId ) );
		} else {
			$checkSql  = "SELECT option_name FROM $wpdb->options WHERE option_name = %s LIMIT 1";
			$nameCheck = $wpdb->get_var( $wpdb->prepare( $checkSql, $prefix .$slug ) );
		}
		if( $nameCheck || in_array( $slug, $disallowed ) ) {
			$suffix = 2;
			do {
				$altName   = _truncate_post_slug( $slug, 200 - ( strlen( $suffix ) + 1 ) ) . "-$suffix";
				if( $feedId && $feedId > 0 ) {
					$nameCheck = $wpdb->get_var( $wpdb->prepare( $checkSql, $prefix .$altName, $feedId ) );
				} else {
					$nameCheck = $wpdb->get_var( $wpdb->prepare( $checkSql, $prefix .$altName ) );
				}
				$suffix++;
			} while ( $nameCheck );
			$slug = $altName;
		}
		return $slug;
	}
}
if( ! function_exists( 'woo_feed_generate_feed' ) ) {
	/**
	 * Generate Feed
	 */
	function woo_feed_generate_feed() {
		if ( isset( $_POST['provider'], $_POST['_wpnonce'], $_POST['filename'], $_POST['feedType'] ) ) {
			# Verify Nonce
			if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'woo_feed_form_nonce' ) ) {
				wp_die( 'Failed security check' );
			}
			# Check feed type (file ext)
			if( ! in_array( $_POST['feedType'], array( 'xml', 'csv', 'txt', 'tsv' ) ) ) {
				wp_die( 'Invalid Feed Type!' );
			}
			// Sanitize Fields
			$_POST    = woo_feed_sanitize_form_fields( $_POST );
			//@TODO simplefy feed db, combine wf_config & wf_feed_ prefix...
			$feedDir  = woo_feed_get_file_dir( $_POST['provider'], $_POST['feedType'] );
			$_POST['filename'] = sanitize_title( $_POST['filename'], '', 'save' );
			// check option name uniqueness ...
			$_POST['filename'] = woo_feed_unique_feed_slug( $_POST['filename'], null );
			$_POST['filename'] = sanitize_file_name( $_POST['filename'] . '.' . $_POST['feedType'] );
			$_POST['filename'] = wp_unique_filename( $feedDir, $_POST['filename'] );
			$_POST['filename'] = str_replace( '.' . $_POST['feedType'] , '', $_POST['filename'] );
			# Option Name
			$fileName = "wf_config" . $_POST['filename'];
			# Store Config
			update_option( $fileName, $_POST );

			# Schedule Cron
			$arg = array( sanitize_text_field( $_POST['filename'] ) );
			wp_schedule_event(time(), 'woo_feed_corn', 'woo_feed_update_single_feed',$arg);


			require WOO_FEED_ADMIN_PATH . "partials/woo-feed-manage-list.php";
		} else {
			echo "<div class='notice notice-warning is-dismissible'><p>" . __("You are awesome for using <b>WooCommerce Product Feed</b>. Free version works great for up to <b>2000 products including variations.</b>", 'woo-feed') . "</p></div>";
			require WOO_FEED_ADMIN_PATH . "partials/woo-feed-admin-display.php";
		}
	}
}
if( ! function_exists( 'woo_feed_manage_feed' ) ) {
	/**
	 * Manage Feeds
	 */
	function woo_feed_manage_feed() {
		// Manage action for category mapping
		if (isset($_GET['action']) && $_GET['action'] == 'edit-feed') {
			if ( count( $_POST ) && isset( $_POST['provider'], $_POST['edit-feed'], $_POST['feed_id'], $_POST['filename'], $_POST['feedType'] ) ) {
				# Verify Nonce
				if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'wf_edit_feed' ) ) {
					wp_die( 'Failed security check' );
				}
				# Check feed type (file ext)
				if( ! in_array( $_POST['feedType'], array( 'xml', 'csv', 'txt', 'tsv' ) ) ) {
					wp_die( 'Invalid Feed Type!' );
				}
				// Sanitize Fields
				$_POST    = woo_feed_sanitize_form_fields( $_POST );
				//@TODO simplefy feed db, combine wf_config & wf_feed_ prefix...
//				$feedDir  = woo_feed_get_file_dir( $_POST['provider'], $_POST['feedType'] );
//				$_POST['filename'] = sanitize_title( $_POST['filename'], '', 'save' );
				// check option name uniqueness ...
//				$_POST['filename'] = woo_feed_unique_feed_slug( $_POST['filename'], $_POST['feed_id'] );
//				$_POST['filename'] = sanitize_file_name( $_POST['filename'] . '.' . $_POST['feedType'] );
//				$_POST['filename'] = wp_unique_filename( $feedDir, $_POST['filename'] );
//				$_POST['filename'] = str_replace( '.' . $_POST['feedType'] , '', $_POST['filename'] );
				# Option Name
				$fileName = "wf_config" . $_POST['filename'];
				# Store Config
				update_option( $fileName, $_POST );

				require WOO_FEED_ADMIN_PATH . "partials/woo-feed-manage-list.php";
				wp_die();
			}

			$merchants=array(
				"custom","fruugo",
				"twenga","pricespy",
				"prisjakt","amazon",
				"adwords","polyvore",
				"bol","pricerunner",
				"adform","bonanza",
				"leeguide","real",
				"crowdfox","jet",
				"wish","google_local_inventory",
				"google_local", "zap.co.il",
				"fruugo.au", "myshopping.com.au",
				"smartly.io", "stylight.com",
				"nextad", "skinflint.co.uk",
				"yahoo_nfa",
				"comparer.be",
				"dooyoo",
				"hintaseuranta.fi",
				"incurvy",
				"kijiji.ca",
				"marktplaats.nl",
				"rakuten",
				"shopalike.fr",
				"spartoo.fi",
				"webmarchand",
				"fashiola",
				'vergelijk_comparer'
			,"kieskeurig.nl",
				'beslist.nl',
				"billiger.de",
				"vertaa.fi",
				"cdiscount.fr",
				"fnac.fr",
				"idealo",
				"miinto.nl",
				"fyndiq.se",
				"criteo",
				"avantlink",
				"shareasale",
				"walmart",
				"modina.de"
			);

			if ( isset( $_GET['feed'] ) && ! empty( $_GET['feed'] ) ) {
				global $wpdb;
				$fname = sanitize_text_field( $_GET['feed'] );
				$feedInfo = unserialize( get_option( $fname ) );
				$feedId   = $wpdb->get_row( $wpdb->prepare( "SELECT option_id FROM $wpdb->options WHERE option_name = %s LIMIT 1", $fname ) );
				if( $feedId ) {
					/** @noinspection PhpUnusedLocalVariableInspection */
					$feedId = $feedId->option_id;
				}
				$provider = strtolower( $feedInfo['feedrules']['provider'] );
				/** @noinspection PhpUnusedLocalVariableInspection */
				$feedRules = $feedInfo['feedrules'];
				//$provider == "custom" ||$provider == "twenga" || $provider == "pricespy" || $provider == "prisjakt" || $provider == "amazon" || $provider == "adwords"
				if ( in_array( $provider, $merchants ) ) {
					require WOO_FEED_ADMIN_PATH . "partials/templates/custom_edit-feed.php";
				} else {
					require WOO_FEED_ADMIN_PATH . "partials/woo-feed-edit-template.php";
				}
			}
		} else {
			# Update Interval
			if ( isset( $_POST['wf_schedule'] ) ) {
				if ( update_option('wf_schedule', sanitize_text_field($_POST['wf_schedule'] ) ) ) {
					wp_clear_scheduled_hook('woo_feed_update');
					add_filter( 'cron_schedules', 'custom_cron_job_custom_recurrence' );
					wp_schedule_event( time(), 'woo_feed_corn', 'woo_feed_update' );
				}
			}

			require WOO_FEED_ADMIN_PATH . "partials/woo-feed-manage-list.php";
		}
	}
}
if( ! function_exists( 'woo_feed_pro_features_page_remove_admin_notices' ) ) {
	add_action( 'admin_head', 'woo_feed_pro_features_page_remove_admin_notices', 9999 );
	/**
	 * Remove Admin Notice in pro features page.
	 * @global string $pagenow
	 * @global string $plugin_page
	 * @return void
	 */
	function woo_feed_pro_features_page_remove_admin_notices() {
		global $pagenow, $plugin_page;
		if( 'admin.php' == $pagenow && 'webappick-feed-pro-vs-free' == $plugin_page ) {
			remove_all_actions( 'admin_notices' );
		}
	}
}
if( ! function_exists( 'woo_feed_pro_vs_free' ) ) {
	/**
	 * Difference between free and premium plugin
	 */
	function woo_feed_pro_vs_free(){
		require WOO_FEED_ADMIN_PATH . "partials/woo-feed-pro-vs-free.php";
	}
}
if( ! function_exists( 'woo_feed_config_feed' ) ) {
	/**
	 * Feed config
	 */
	function woo_feed_config_feed(){
		if(isset($_POST['wa_woo_feed_config'])) {
			update_option("woo_feed_per_batch",sanitize_text_field($_POST['limit']));
			if(isset($_POST['enable_error_debugging'])) {
				update_option("woo_feed_enable_error_debugging", sanitize_text_field($_POST['enable_error_debugging']));
			} else {
				update_option("woo_feed_enable_error_debugging", "off");
			}
		}
		require WOO_FEED_ADMIN_PATH . "partials/woo-feed-config.php";
	}
}
if( ! function_exists( 'woo_feed_getFeedInfoForCronUpdate' ) ) {
	/**
     * Scheduled Action Hook
     */
	function woo_feed_getFeedInfoForCronUpdate(){

		check_ajax_referer('wpf_feed_nonce');
		global $wpdb;
		$var = "wf_feed_";
		$query = $wpdb->prepare("SELECT * FROM $wpdb->options WHERE option_name LIKE %s;", $var . "%");
		$result = $wpdb->get_results($query, 'ARRAY_A');
		$feeds=array();
		foreach ($result as $key => $value) {
			$feedInfo = unserialize(get_option($value['option_name']));
			$feeds["wf_config".$value['option_name']]=$feedInfo['last_updated'];
		}

		$return = array(
			'data'	=> $feeds,
		);

		wp_send_json($return);
	}
	add_action('wp_ajax_getFeedInfoForCronUpdate', 'woo_feed_getFeedInfoForCronUpdate');
	add_action('wp_ajax_nopriv_getFeedInfoForCronUpdate', 'woo_feed_getFeedInfoForCronUpdate');
}
if( ! function_exists( 'woo_feed_update_feed_status' ) ) {
	/**
	 * Update feed status
	 */
	function woo_feed_update_feed_status(){
		if(!empty($_POST['feedName'])){
			$feedInfo = unserialize(get_option($_POST['feedName']));
			$feedInfo['status'] = $_POST['status'];
			$data = array('status' => true);
			update_option($_POST['feedName'],serialize($feedInfo));
			wp_send_json_success($data);
		}else{
			$data = array('status' => false);
			wp_send_json_error($data);
		}
		wp_die();
	}
	add_action('wp_ajax_update_feed_status', 'woo_feed_update_feed_status');
}
if( ! function_exists( 'woo_feed_cron_update_feed' ) ) {
	/*
 * Scheduled Action Hook
 */
	function woo_feed_cron_update_feed() {
		global $wpdb;
		$var = "wf_feed_";
		$query = $wpdb->prepare("SELECT * FROM $wpdb->options WHERE option_name LIKE %s;", $var . "%");
		$result = $wpdb->get_results($query, 'ARRAY_A');
		foreach ($result as $key => $value) {
			$feedInfo = unserialize(get_option($value['option_name']));
			if(!isset($feedInfo['status']) || $feedInfo['status'] != "0") {
				woo_feed_add_update( $feedInfo['feedrules'] );
			}
		}
	}
	add_action('woo_feed_update', 'woo_feed_cron_update_feed');
}
if( ! function_exists( 'woo_feed_cron_update_single_feed' ) ) {
	/*
 * Scheduled Action Hook
 */
	function woo_feed_cron_update_single_feed($feedname) {
		global $wpdb;

		$feedname = "wf_feed_".$feedname[0];



		$result = $wpdb->get_results(
			$wpdb->prepare( "
        SELECT * FROM $wpdb->options 
        WHERE option_name = %s",
				$feedname
			)
			,'ARRAY_A');


		if(!empty($result)){
			foreach ($result as $key => $value) {
				$feedInfo = unserialize(get_option($value['option_name']));
				if(!isset($feedInfo['status']) || $feedInfo['status'] != "0") {
					woo_feed_add_update( $feedInfo['feedrules'] );
				}
			}
		}
	}
	add_action('woo_feed_update_single_feed', 'woo_feed_cron_update_single_feed');
}
if( ! function_exists( 'woo_feed_get_ssh2_status' ) ) {
	function woo_feed_get_ssh2_status() {
		check_ajax_referer( 'wpf_feed_nonce' );
		/** @noinspection PhpUnusedLocalVariableInspection */
		$php_extension = get_loaded_extensions();
		if(extension_loaded ( 'ssh2' )) {
			wp_send_json_success('exists');
		} else {
			wp_send_json_success('not exists');
		}
	}
	add_action('wp_ajax_get_ssh2_status', 'woo_feed_get_ssh2_status');
}
if( ! function_exists( 'woo_feed_review_notice' ) ) {
	function woo_feed_review_notice() {
		$options = get_option('woo_feed_review_notice');
		$installDate=get_option('woo-feed-activation-time');
		$installDate=strtotime('-16 days',$installDate);

		$notice = '<div class="woo-feed-review-notice notice notice-info is-dismissible">';
		$notice .= '<p><b>:) We have spent countless hours developing this free plugin for you, and we would really appreciate it if you dropped us a quick rating. Your opinion matters a lot to us. It helps us to get better. Thanks for using <i>WooCommerce Product Feed</i>.</b></p>';
		$notice .= '<ul>';
		$notice .= '<li><a val="later" href="#">Remind me later</a></li>';
		$notice .= '<li><a val="never" href="#">I would not</a></li>';
		$notice .= '<li><a val="given" href="#" target="_blank">Review Here</a></li>';
		$notice .= '</ul>';
		$notice .= '</div>';

		if(!$options && time()>= $installDate + (60*60*24*15)){
			echo $notice;
		} else if(is_array($options)) {
			if((!array_key_exists('review_notice',$options)) || ($options['review_notice'] =='later' && time()>=($options['updated_at'] + (60*60*24*30) ))){
				echo $notice;
			}
		}
	}
	add_action( 'admin_notices', 'woo_feed_review_notice' );
}
if( ! function_exists( 'woo_feed_save_review_notice' ) ) {
	/**
	 * Show Review request admin notice
	 */
	function woo_feed_save_review_notice() {
		$notice = sanitize_text_field($_POST['notice']);
		$value['review_notice'] = $notice;
		$value['updated_at'] = time();

		update_option('woo_feed_review_notice',$value);
		wp_send_json_success($value);
	}
	add_action('wp_ajax_woo_feed_save_review_notice', 'woo_feed_save_review_notice');
}
if( ! function_exists( 'woo_feed_wpml_notice' ) ) {
	/**
	 *  Show notice if WPML installed
	 */
	function woo_feed_wpml_notice() {
		if (class_exists('SitePress') && get_option('woo_feed_wpml_notice') == false) {
			$wpml_notice = '<div class="woo-feed-wpml-notice notice notice-success is-dismissible">';
			$wpml_notice .= '<p>You are awesome for using <b>WooCommerce Product Feed</b>. 
                    Using the <b><a href="https://webappick.com/plugin/woocommerce-product-feed-pro/" target="_blank">Premium</a></b> version you can make multilingual feed for your WPML languages.</p>';
			$wpml_notice .= '<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>';
			$wpml_notice .= '</div>';
			echo $wpml_notice;
		}
	}
	add_action( 'admin_notices', 'woo_feed_wpml_notice' );
}
if( ! function_exists( 'woo_feed_save_wpml_notice' ) ) {
	add_action('wp_ajax_woo_feed_save_wpml_notice', 'woo_feed_save_wpml_notice');
	function woo_feed_save_wpml_notice() {
		$notice = true;
		$value['wpml_notice'] = $notice;
		update_option('woo_feed_wpml_notice',$value);

		wp_send_json_success();
	}
}
// End of file woo-feed.php